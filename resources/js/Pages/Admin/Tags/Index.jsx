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

export default function TagsIndex({ tags }) {
    const { confirm, dialog } = useConfirm();
    const rows = tags?.data || [];

    const remove = async (tag) => {
        const ok = await confirm({
            title: 'Chuyển tag vào thùng rác?',
            message: `Tag “${tag.name}” sẽ được chuyển vào thùng rác.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.tags.destroy(tag.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý tag"
            description="Tag dùng để phân loại bài viết."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tag' }]}
            actions={
                <>
                    <Button href={routes.admin.tags.trash} variant="outline">Thùng rác</Button>
                    <Button href={routes.admin.tags.create} icon={<Plus size={16} />}>Thêm tag</Button>
                </>
            }
        >
            <Card>
                <FilterBar url={routes.admin.tags.index} values={Object.fromEntries(new URLSearchParams(window.location.search))} searchPlaceholder="Tìm theo tên tag…" />
                <DataTable
                    empty="Chưa có tag nào"
                    rows={rows}
                    columns={[
                        { key: 'name', label: 'Tên tag', render: (tag) => <Link href={routes.admin.tags.show(tag.id)} className="ui-link">{tag.name}</Link> },
                        { key: 'slug', label: 'Slug', render: (tag) => <code>{tag.slug}</code> },
                        { key: 'posts_count', label: 'Bài viết', render: (tag) => <Badge tone="muted">{tag.posts_count ?? 0}</Badge> },
                        {
                            key: 'actions',
                            label: 'Thao tác',
                            width: 100,
                            render: (tag) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.tags.edit(tag.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(tag)}><Trash2 size={15} /></button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={tags?.links} from={tags?.from} to={tags?.to} total={tags?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
