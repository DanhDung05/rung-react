import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Pencil, Trash2 } from 'lucide-react';
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

export default function SlidersIndex({ sliders, positions = [] }) {
    const { confirm, dialog } = useConfirm();
    const rows = sliders?.data || [];

    const remove = async (slider) => {
        const ok = await confirm({ title: 'Chuyển slider vào thùng rác?', message: 'Slider này sẽ được chuyển vào thùng rác.', confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.sliders.destroy(slider.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý slider"
            description="Banner hiển thị trên trang chủ."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Slider' }]}
            actions={<><Button href={routes.admin.sliders.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.sliders.create} icon={<Plus size={16} />}>Thêm slider</Button></>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.sliders.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tiêu đề…"
                    filters={[{ name: 'position', label: 'Vị trí', options: positions.map((position) => ({ value: position, label: position })) }]}
                />
                <DataTable
                    empty="Chưa có slider nào"
                    rows={rows}
                    columns={[
                        { key: 'image', label: '', width: 90, render: (slider) => (slider.image ? <img className="ui-thumb ui-thumb--wide" src={mediaUrl(slider.image)} alt="" /> : <span className="ui-thumb ui-thumb--empty" />) },
                        { key: 'title', label: 'Tiêu đề', render: (slider) => (<div><Link href={routes.admin.sliders.show(slider.id)} className="ui-link">{slider.title || '(Không tiêu đề)'}</Link><div className="ui-sub">{slider.subtitle}</div></div>) },
                        { key: 'position', label: 'Vị trí', render: (slider) => <code>{slider.position}</code> },
                        { key: 'is_active', label: 'Hiển thị', render: (slider) => <Badge tone={slider.is_active ? 'success' : 'muted'}>{slider.is_active ? 'Có' : 'Không'}</Badge> },
                        { key: 'sort_order', label: 'Thứ tự', render: (slider) => slider.sort_order },
                        { key: 'start_at', label: 'Bắt đầu', render: (slider) => date(slider.start_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 100,
                            render: (slider) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.sliders.edit(slider.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(slider)}><Trash2 size={15} /></button>
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
