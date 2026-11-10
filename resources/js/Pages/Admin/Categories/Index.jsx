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
import { number } from '../../../lib/format';
import { CATEGORY_TYPE } from '../../../lib/constants';

export default function CategoriesIndex({ categories }) {
    const { confirm, dialog } = useConfirm();
    const rows = categories?.data || [];

    const remove = async (category) => {
        const ok = await confirm({
            title: 'Chuyển danh mục vào thùng rác?',
            message: `Danh mục “${category.name}” sẽ được chuyển vào thùng rác.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.categories.destroy(category.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý danh mục"
            description="Danh mục dùng chung cho tour và bài viết."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Danh mục' }]}
            actions={<><Button href={routes.admin.categories.trash} variant="outline">Thùng rác</Button><Button href={routes.admin.categories.create} icon={<Plus size={16} />}>Thêm danh mục</Button></>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.categories.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên danh mục…"
                    filters={[{ name: 'type', label: 'Loại', options: [{ value: 'tour', label: 'Danh mục tour' }, { value: 'post', label: 'Danh mục bài viết' }] }]}
                />

                <DataTable
                    empty="Chưa có danh mục nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'name', label: 'Danh mục',
                            render: (category) => (
                                <div>
                                    <Link href={routes.admin.categories.show(category.id)} className="ui-link">{category.name}</Link>
                                    <div className="ui-sub">{category.slug}</div>
                                </div>
                            ),
                        },
                        { key: 'type', label: 'Loại', render: (category) => <Badge tone={category.type === 'tour' ? 'info' : 'gold'}>{CATEGORY_TYPE[category.type] || category.type}</Badge> },
                        { key: 'parent', label: 'Cấp cha', render: (category) => category.parent?.name || '—' },
                        { key: 'tours_count', label: 'Tour', render: (category) => number(category.tours_count) },
                        { key: 'posts_count', label: 'Bài viết', render: (category) => number(category.posts_count) },
                        { key: 'is_active', label: 'Hoạt động', render: (category) => <Badge tone={category.is_active ? 'success' : 'muted'}>{category.is_active ? 'Có' : 'Không'}</Badge> },
                        {
                            key: 'actions', label: 'Thao tác', width: 100,
                            render: (category) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.categories.edit(category.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(category)}><Trash2 size={15} /></button>
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
