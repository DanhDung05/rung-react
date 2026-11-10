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

export default function DestinationsTrash({ destinations }) {
    const { confirm, dialog } = useConfirm();
    const rows = destinations?.data || [];

    const forceDelete = async (item) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: `Điểm đến “${item.name}” sẽ bị xóa vĩnh viễn. Hệ thống chặn nếu còn tour hoặc điểm đến con liên kết.`, confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.destinations.forceDestroy(item.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Điểm đến"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Điểm đến', href: routes.admin.destinations.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.destinations.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Điểm đến', render: (item) => item.name },
                        { key: 'parent', label: 'Cấp cha', render: (item) => item.parent?.name || '—' },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (item) => datetime(item.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (item) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.destinations.restore(item.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(item)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={destinations?.links} from={destinations?.from} to={destinations?.to} total={destinations?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
