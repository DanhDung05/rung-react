import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Trash2, ListTree } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { number } from '../../../lib/format';

export default function MenusIndex({ menus }) {
    const { confirm, dialog } = useConfirm();
    const rows = menus?.data || [];

    const remove = async (menu) => {
        const ok = await confirm({ title: 'Chuyển menu vào thùng rác?', message: `Menu “${menu.name}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.menus.destroy(menu.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý menu"
            description="Điều hướng header và footer của website."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Menu' }]}
            actions={<><Button href={routes.admin.menus.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.menus.create} icon={<Plus size={16} />}>Thêm menu</Button></>}
        >
            <Card>
                <FilterBar url={routes.admin.menus.index} values={Object.fromEntries(new URLSearchParams(window.location.search))} searchPlaceholder="Tìm theo tên menu…" />

                <DataTable
                    empty="Chưa có menu nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'name', label: 'Menu',
                            render: (menu) => (
                                <div>
                                    <Link href={routes.admin.menus.show(menu.id)} className="ui-link">{menu.name}</Link>
                                    <div className="ui-sub">{menu.description}</div>
                                </div>
                            ),
                        },
                        { key: 'location', label: 'Vị trí', render: (menu) => <code>{menu.location}</code> },
                        { key: 'items', label: 'Số item', render: (menu) => <Badge tone="muted">{number(menu.all_items_count)}</Badge> },
                        { key: 'is_active', label: 'Hoạt động', render: (menu) => <Badge tone={menu.is_active ? 'success' : 'muted'}>{menu.is_active ? 'Có' : 'Không'}</Badge> },
                        {
                            key: 'actions', label: 'Thao tác', width: 190,
                            render: (menu) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.menus.items(menu.id)} className="ui-btn ui-btn--outline ui-btn--sm" title="Quản lý item">
                                        <ListTree size={14} /> Items
                                    </Link>
                                    <Link href={routes.admin.menus.edit(menu.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(menu)}><Trash2 size={15} /></button>
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
