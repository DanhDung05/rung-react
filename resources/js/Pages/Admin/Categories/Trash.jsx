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

export default function CategoriesTrash({ categories }) {
    const { confirm, dialog } = useConfirm();
    const rows = categories?.data || [];

    const forceDelete = async (category) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: `Danh mục “${category.name}” sẽ bị xóa vĩnh viễn. Hệ thống chặn nếu còn tour/bài viết/danh mục con.`, confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.categories.forceDestroy(category.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Danh mục"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Danh mục', href: routes.admin.categories.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.categories.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Danh mục', render: (category) => category.name },
                        { key: 'type', label: 'Loại', render: (category) => category.type },
                        { key: 'parent', label: 'Cấp cha', render: (category) => category.parent?.name || '—' },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (category) => datetime(category.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (category) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.categories.restore(category.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(category)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={categories?.links} from={categories?.from} to={categories?.to} total={categories?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
