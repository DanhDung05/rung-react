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

export default function ToursTrash({ tours }) {
    const { confirm, dialog } = useConfirm();
    const rows = tours?.data || [];

    const restore = (tour) => router.patch(routes.admin.tours.restore(tour.id), {}, { preserveScroll: true });

    const forceDelete = async (tour) => {
        const ok = await confirm({
            title: 'Xóa vĩnh viễn?',
            message: `Tour “${tour.name}” sẽ bị xóa vĩnh viễn cùng lịch trình và ảnh. Không thể hoàn tác.`,
            confirmLabel: 'Xóa vĩnh viễn',
        });
        if (ok) router.delete(routes.admin.tours.forceDestroy(tour.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Tour"
            description="Các tour đã xóa mềm. Có thể khôi phục hoặc xóa vĩnh viễn."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tour', href: routes.admin.tours.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.tours.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Tour', render: (tour) => tour.name },
                        { key: 'slug', label: 'Slug', render: (tour) => <code>{tour.slug}</code> },
                        { key: 'category', label: 'Danh mục', render: (tour) => tour.category?.name || '—' },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (tour) => datetime(tour.deleted_at) },
                        {
                            key: 'actions',
                            label: 'Thao tác',
                            width: 170,
                            render: (tour) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => restore(tour)}>
                                        Khôi phục
                                    </Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(tour)}>
                                        Xóa
                                    </Button>
                                </div>
                            ),
                        },
                    ]}
                />

                <Pagination links={tours?.links} from={tours?.from} to={tours?.to} total={tours?.total} />
            </Card>

            {dialog}
        </AdminLayout>
    );
}
