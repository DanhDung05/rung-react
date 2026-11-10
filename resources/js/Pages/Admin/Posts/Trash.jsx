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

export default function PostsTrash({ posts }) {
    const { confirm, dialog } = useConfirm();
    const rows = posts?.data || [];

    const forceDelete = async (post) => {
        const ok = await confirm({
            title: 'Xóa vĩnh viễn?',
            message: `Bài viết “${post.title}” sẽ bị xóa vĩnh viễn. Nếu bài viết còn tag, hệ thống sẽ chặn và yêu cầu gỡ tag trước.`,
            confirmLabel: 'Xóa vĩnh viễn',
        });
        if (ok) router.delete(routes.admin.posts.forceDestroy(post.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Bài viết"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Bài viết', href: routes.admin.posts.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.posts.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'title', label: 'Tiêu đề', render: (post) => post.title },
                        { key: 'category', label: 'Danh mục', render: (post) => post.category?.name || '—' },
                        { key: 'author', label: 'Tác giả', render: (post) => post.author?.name || '—' },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (post) => datetime(post.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (post) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.posts.restore(post.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(post)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={posts?.links} from={posts?.from} to={posts?.to} total={posts?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
