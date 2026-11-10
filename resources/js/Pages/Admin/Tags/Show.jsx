import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';

export default function TagsShow({ tag }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển tag vào thùng rác?', message: `Tag “${tag.name}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.tags.destroy(tag.id));
    };

    return (
        <AdminLayout
            title={tag.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tag', href: routes.admin.tags.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.tags.index} variant="outline">← Danh sách</Button><Button href={routes.admin.tags.edit(tag.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin tag">
                        <div className="ui-detail-row"><span>Slug</span><div><code>{tag.slug}</code></div></div>
                        <div className="ui-detail-row"><span>Mô tả</span><div>{tag.description || '—'}</div></div>
                        <div className="ui-detail-row"><span>Số bài viết</span><div><Badge tone="muted">{tag.posts_count ?? 0}</Badge></div></div>
                        <div className="ui-detail-row"><span>Ngày tạo</span><div>{datetime(tag.created_at)}</div></div>
                    </Card>
                </div>
                <aside>
                    <Card title="Bài viết gần đây">
                        {(tag.posts || []).length === 0 ? <p className="ui-hint">Chưa có bài viết.</p> : (
                            <ul className="ui-feed">
                                {tag.posts.map((post) => (
                                    <li key={post.id}><Link href={routes.admin.posts.show(post.id)}><strong>{post.title}</strong><small>{datetime(post.created_at)}</small></Link></li>
                                ))}
                            </ul>
                        )}
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
