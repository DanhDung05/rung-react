import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function PagesShow({ page }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển trang vào thùng rác?', message: `Trang “${page.title}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.pages.destroy(page.id));
    };

    return (
        <AdminLayout
            title={page.title}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Trang tĩnh', href: routes.admin.pages.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.pages.index} variant="outline">← Danh sách</Button><Button href={routes.admin.pages.edit(page.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin trang">
                        <div className="ui-detail-row"><span>Slug</span><div><code>{page.slug}</code></div></div>
                        <div className="ui-detail-row"><span>Template</span><div><code>{page.template}</code></div></div>
                        <div className="ui-detail-row"><span>Trạng thái</span><div><Badge tone={STATUS[page.status]?.tone}>{STATUS[page.status]?.label || page.status}</Badge></div></div>
                        <div className="ui-detail-row"><span>Ngày đăng</span><div>{datetime(page.published_at)}</div></div>
                        <div className="ui-detail-row"><span>Cập nhật</span><div>{datetime(page.updated_at)}</div></div>
                    </Card>
                    <Card title="Nội dung">
                        {page.content ? <div className="ui-prose" dangerouslySetInnerHTML={{ __html: page.content }} /> : <p className="ui-hint">Chưa có nội dung.</p>}
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh đại diện">
                        {page.featured_image ? <img className="ui-detail-image" src={mediaUrl(page.featured_image)} alt={page.title} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                    <Card title="Liên kết">
                        <Link href={routes.page(page.slug)} target="_blank" className="ui-btn ui-btn--outline ui-btn--block"><Eye size={15} /> Xem trên website</Link>
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
