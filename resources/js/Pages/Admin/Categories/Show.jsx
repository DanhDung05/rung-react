import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime, number } from '../../../lib/format';
import { CATEGORY_TYPE } from '../../../lib/constants';

export default function CategoriesShow({ category }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển danh mục vào thùng rác?', message: `Danh mục “${category.name}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.categories.destroy(category.id));
    };

    return (
        <AdminLayout
            title={category.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Danh mục', href: routes.admin.categories.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.categories.index} variant="outline">← Danh sách</Button><Button href={routes.admin.categories.edit(category.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin danh mục">
                        <div className="ui-detail-row"><span>Loại</span><div><Badge tone={category.type === 'tour' ? 'info' : 'gold'}>{CATEGORY_TYPE[category.type] || category.type}</Badge></div></div>
                        <div className="ui-detail-row"><span>Danh mục cha</span><div>{category.parent?.name || '—'}</div></div>
                        <div className="ui-detail-row"><span>Slug</span><div><code>{category.slug}</code></div></div>
                        <div className="ui-detail-row"><span>Mô tả</span><div>{category.description || '—'}</div></div>
                        <div className="ui-detail-row"><span>Số tour</span><div>{number(category.tours_count)}</div></div>
                        <div className="ui-detail-row"><span>Số bài viết</span><div>{number(category.posts_count)}</div></div>
                        <div className="ui-detail-row"><span>Cập nhật</span><div>{datetime(category.updated_at)}</div></div>
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh đại diện">
                        {category.image ? <img className="ui-detail-image" src={mediaUrl(category.image)} alt={category.name} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                    <Card title="Danh mục con">
                        {(category.children || []).length === 0 ? <p className="ui-hint">Không có danh mục con.</p> : (
                            <ul className="ui-chip-list">{category.children.map((child) => <li key={child.id}><Link href={routes.admin.categories.show(child.id)}>{child.name}</Link></li>)}</ul>
                        )}
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
