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
import { mediaUrl, number, date } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function PostsIndex({ posts, categories = [] }) {
    const { confirm, dialog } = useConfirm();
    const rows = posts?.data || [];

    const remove = async (post) => {
        const ok = await confirm({
            title: 'Chuyển bài viết vào thùng rác?',
            message: `Bài viết “${post.title}” sẽ được chuyển vào thùng rác.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.posts.destroy(post.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý bài viết"
            description="Cẩm nang và tin tức của Rung Adventure."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Bài viết' }]}
            actions={
                <>
                    <Button href={routes.admin.posts.trash} variant="outline">Thùng rác</Button>
                    <Button href={routes.admin.posts.create} icon={<Plus size={16} />}>Thêm bài viết</Button>
                </>
            }
        >
            <Card>
                <FilterBar
                    url={routes.admin.posts.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tiêu đề…"
                    filters={[
                        { name: 'status', label: 'Trạng thái', options: [{ value: 'draft', label: 'Bản nháp' }, { value: 'published', label: 'Đã xuất bản' }] },
                        { name: 'category_id', label: 'Danh mục', options: categories.map((category) => ({ value: category.id, label: category.name })) },
                    ]}
                />

                <DataTable
                    empty="Chưa có bài viết nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'image', label: '', width: 64,
                            render: (post) => (post.featured_image ? <img className="ui-thumb" src={mediaUrl(post.featured_image)} alt="" /> : <span className="ui-thumb ui-thumb--empty" />),
                        },
                        {
                            key: 'title', label: 'Tiêu đề',
                            render: (post) => (
                                <div>
                                    <Link href={routes.admin.posts.show(post.id)} className="ui-link">{post.title}</Link>
                                    <div className="ui-sub">{post.slug}</div>
                                </div>
                            ),
                        },
                        { key: 'category', label: 'Danh mục', render: (post) => post.category?.name || '—' },
                        { key: 'author', label: 'Tác giả', render: (post) => post.author?.name || '—' },
                        { key: 'status', label: 'Trạng thái', render: (post) => <Badge tone={STATUS[post.status]?.tone}>{STATUS[post.status]?.label || post.status}</Badge> },
                        { key: 'view_count', label: 'Lượt xem', render: (post) => number(post.view_count) },
                        { key: 'published_at', label: 'Ngày đăng', render: (post) => date(post.published_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 150,
                            render: (post) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.posts.edit(post.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <Link href={routes.posts.show(post.slug)} target="_blank" className="ui-icon-btn" title="Xem ngoài website"><Eye size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(post)}><Trash2 size={15} /></button>
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
