import React from 'react';
import { router } from '@inertiajs/react';
import { RotateCcw, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';

export default function PagesTrash({ pages }) {
    const { confirm, dialog } = useConfirm();
    const rows = pages?.data || [];

    const forceDelete = async (page) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: `Trang “${page.title}” sẽ bị xóa vĩnh viễn.`, confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.pages.forceDestroy(page.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Trang tĩnh"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Trang tĩnh', href: routes.admin.pages.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.pages.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'title', label: 'Tiêu đề', render: (page) => page.title },
                        { key: 'slug', label: 'Slug', render: (page) => <code>{page.slug}</code> },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (page) => datetime(page.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (page) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.pages.restore(page.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(page)}>Xóa</Button>
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
