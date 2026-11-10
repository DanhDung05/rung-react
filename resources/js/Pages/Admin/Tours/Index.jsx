import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Plus, Trash2, Pencil, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { money, mediaUrl, number, date } from '../../../lib/format';
import { DIFFICULTY, STATUS } from '../../../lib/constants';

export default function ToursIndex({ tours, categories = [] }) {
    const { confirm, dialog } = useConfirm();
    const rows = tours?.data || [];

    const remove = async (tour) => {
        const ok = await confirm({
            title: 'Chuyển tour vào thùng rác?',
            message: `Tour “${tour.name}” sẽ được chuyển vào thùng rác và có thể khôi phục.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.tours.destroy(tour.id), { preserveScroll: true });
    };

    const columns = [
        {
            key: 'image',
            label: '',
            width: 64,
            render: (tour) =>
                tour.featured_image ? (
                    <img className="ui-thumb" src={mediaUrl(tour.featured_image)} alt="" />
                ) : (
                    <span className="ui-thumb ui-thumb--empty" />
                ),
        },
        {
            key: 'name',
            label: 'Tour',
            render: (tour) => (
                <div>
                    <Link href={routes.admin.tours.show(tour.id)} className="ui-link">
                        {tour.name}
                    </Link>
                    <div className="ui-sub">{tour.slug}</div>
                </div>
            ),
        },
        { key: 'category', label: 'Danh mục', render: (tour) => tour.category?.name || '—' },
        {
            key: 'difficulty',
            label: 'Độ khó',
            render: (tour) => (tour.difficulty ? DIFFICULTY[tour.difficulty] || tour.difficulty : '—'),
        },
        {
            key: 'status',
            label: 'Trạng thái',
            render: (tour) => <Badge tone={STATUS[tour.status]?.tone}>{STATUS[tour.status]?.label || tour.status}</Badge>,
        },
        { key: 'price', label: 'Giá', render: (tour) => money(tour.price_reference) },
        { key: 'view_count', label: 'Lượt xem', render: (tour) => number(tour.view_count) },
        { key: 'created_at', label: 'Ngày tạo', render: (tour) => date(tour.created_at) },
        {
            key: 'actions',
            label: 'Thao tác',
            width: 150,
            render: (tour) => (
                <div className="ui-row-actions">
                    <Link href={routes.admin.tours.edit(tour.id)} className="ui-icon-btn" title="Sửa">
                        <Pencil size={15} />
                    </Link>
                    <Link href={routes.tours.show(tour.slug)} target="_blank" className="ui-icon-btn" title="Xem ngoài website">
                        <Eye size={15} />
                    </Link>
                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(tour)}>
                        <Trash2 size={15} />
                    </button>
                </div>
            ),
        },
    ];

    return (
        <AdminLayout
            title="Quản lý tour"
            description="Danh sách toàn bộ hành trình của Rung Adventure."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tour' }]}
            actions={
                <>
                    <Button href={routes.admin.tours.trash} variant="outline">Thùng rác</Button>
                    <Button href={routes.admin.tours.create} icon={<Plus size={16} />}>Thêm tour</Button>
                </>
            }
        >
            <Card>
                <FilterBar
                    url={routes.admin.tours.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên tour…"
                    filters={[
                        {
                            name: 'status',
                            label: 'Trạng thái',
                            options: [
                                { value: 'draft', label: 'Bản nháp' },
                                { value: 'published', label: 'Đã xuất bản' },
                            ],
                        },
                        {
                            name: 'category_id',
                            label: 'Danh mục',
                            options: categories.map((category) => ({ value: category.id, label: category.name })),
                        },
                        {
                            name: 'difficulty',
                            label: 'Độ khó',
                            options: Object.entries(DIFFICULTY).map(([value, label]) => ({ value, label })),
                        },
                    ]}
                />

                <DataTable columns={columns} rows={rows} empty="Chưa có tour nào" />

                <Pagination links={tours?.links} from={tours?.from} to={tours?.to} total={tours?.total} />
            </Card>

            {dialog}
        </AdminLayout>
    );
}
