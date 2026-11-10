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

export default function SlidersTrash({ sliders }) {
    const { confirm, dialog } = useConfirm();
    const rows = sliders?.data || [];

    const forceDelete = async (slider) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: 'Slider và ảnh sẽ bị xóa vĩnh viễn.', confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.sliders.forceDestroy(slider.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Slider"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Slider', href: routes.admin.sliders.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.sliders.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'title', label: 'Tiêu đề', render: (slider) => slider.title || '(Không tiêu đề)' },
                        { key: 'position', label: 'Vị trí', render: (slider) => <code>{slider.position}</code> },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (slider) => datetime(slider.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (slider) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.sliders.restore(slider.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(slider)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={sliders?.links} from={sliders?.from} to={sliders?.to} total={sliders?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
