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
import { mediaUrl, number } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function DestinationsIndex({ destinations, regions = [] }) {
    const { confirm, dialog } = useConfirm();
    const rows = destinations?.data || [];

    const remove = async (destination) => {
        const ok = await confirm({
            title: 'Chuyển điểm đến vào thùng rác?',
            message: `Điểm đến “${destination.name}” sẽ được chuyển vào thùng rác.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.destinations.destroy(destination.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý điểm đến"
            description="Các địa danh du lịch của Rung Adventure."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Điểm đến' }]}
            actions={<><Button href={routes.admin.destinations.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.destinations.create} icon={<Plus size={16} />}>Thêm điểm đến</Button></>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.destinations.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên điểm đến…"
                    filters={[
                        { name: 'status', label: 'Trạng thái', options: [{ value: 'draft', label: 'Bản nháp' }, { value: 'published', label: 'Đã xuất bản' }] },
                        { name: 'region', label: 'Vùng miền', options: regions.map((region) => ({ value: region, label: region })) },
                    ]}
                />

                <DataTable
                    empty="Chưa có điểm đến nào"
                    rows={rows}
                    columns={[
                        { key: 'image', label: '', width: 64, render: (item) => (item.image ? <img className="ui-thumb" src={mediaUrl(item.image)} alt="" /> : <span className="ui-thumb ui-thumb--empty" />) },
                        {
                            key: 'name', label: 'Điểm đến',
                            render: (item) => (
                                <div>
                                    <Link href={routes.admin.destinations.show(item.id)} className="ui-link">{item.name}</Link>
                                    <div className="ui-sub">{[item.province, item.region].filter(Boolean).join(' · ') || item.slug}</div>
                                </div>
                            ),
                        },
                        { key: 'parent', label: 'Cấp cha', render: (item) => item.parent?.name || '—' },
                        { key: 'tours_count', label: 'Tour', render: (item) => <Badge tone="muted">{number(item.tours_count)}</Badge> },
                        { key: 'children_count', label: 'Điểm con', render: (item) => number(item.children_count) },
                        { key: 'status', label: 'Trạng thái', render: (item) => <Badge tone={STATUS[item.status]?.tone}>{STATUS[item.status]?.label || item.status}</Badge> },
                        {
                            key: 'actions', label: 'Thao tác', width: 150,
                            render: (item) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.destinations.edit(item.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <Link href={routes.destinations.show(item.slug)} target="_blank" className="ui-icon-btn" title="Xem ngoài website"><Eye size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(item)}><Trash2 size={15} /></button>
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
