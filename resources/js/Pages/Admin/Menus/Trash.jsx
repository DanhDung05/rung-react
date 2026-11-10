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

export default function MenusTrash({ menus }) {
    const { confirm, dialog } = useConfirm();
    const rows = menus?.data || [];

    const forceDelete = async (menu) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: `Menu “${menu.name}” sẽ bị xóa vĩnh viễn. Hệ thống chặn nếu còn item.`, confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.menus.forceDestroy(menu.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Menu"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Menu', href: routes.admin.menus.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.menus.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Menu', render: (menu) => menu.name },
                        { key: 'location', label: 'Vị trí', render: (menu) => <code>{menu.location}</code> },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (menu) => datetime(menu.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (menu) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.menus.restore(menu.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(menu)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={menus?.links} from={menus?.from} to={menus?.to} total={menus?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
