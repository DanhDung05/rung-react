import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Trash2, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, date } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function PagesIndex({ pages }) {
    const { confirm, dialog } = useConfirm();
    const rows = pages?.data || [];

    const remove = async (page) => {
        const ok = await confirm({ title: 'Chuyển trang vào thùng rác?', message: `Trang “${page.title}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.pages.destroy(page.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý trang tĩnh"
            description="Giới thiệu, liên hệ, FAQ và các trang nội dung khác."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Trang tĩnh' }]}
            actions={<><Button href={routes.admin.pages.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.pages.create} icon={<Plus size={16} />}>Thêm trang</Button></>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.pages.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tiêu đề…"
                    filters={[{ name: 'status', label: 'Trạng thái', options: [{ value: 'draft', label: 'Bản nháp' }, { value: 'published', label: 'Đã xuất bản' }] }]}
                />
                <DataTable
                    empty="Chưa có trang nào"
                    rows={rows}
                    columns={[
                        { key: 'image', label: '', width: 64, render: (page) => (page.featured_image ? <img className="ui-thumb" src={mediaUrl(page.featured_image)} alt="" /> : <span className="ui-thumb ui-thumb--empty" />) },
                        { key: 'title', label: 'Tiêu đề', render: (page) => (<div><Link href={routes.admin.pages.show(page.id)} className="ui-link">{page.title}</Link><div className="ui-sub">{page.slug}</div></div>) },
                        { key: 'template', label: 'Template', render: (page) => <code>{page.template}</code> },
                        { key: 'status', label: 'Trạng thái', render: (page) => <Badge tone={STATUS[page.status]?.tone}>{STATUS[page.status]?.label || page.status}</Badge> },
                        { key: 'published_at', label: 'Ngày đăng', render: (page) => date(page.published_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 150,
                            render: (page) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.pages.edit(page.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <Link href={routes.page(page.slug)} target="_blank" className="ui-icon-btn" title="Xem ngoài website"><Eye size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(page)}><Trash2 size={15} /></button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={pages?.links} from={pages?.from} to={pages?.to} total={pages?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
