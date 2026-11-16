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
import { datetime, truncate } from '../../../lib/format';

export default function ReviewsTrash({ reviews }) {
    const { confirm, dialog } = useConfirm();
    const rows = reviews?.data || [];

    const forceDelete = async (review) => {
        const ok = await confirm({ title: 'Xóa vĩnh viễn?', message: 'Đánh giá này sẽ bị xóa vĩnh viễn.', confirmLabel: 'Xóa vĩnh viễn' });
        if (ok) router.delete(routes.admin.reviews.forceDestroy(review.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Đánh giá"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Đánh giá', href: routes.admin.reviews.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.reviews.index} variant="outline">← Danh sách</Button>}
        >
            <Card>
                <DataTable
                    empty="Thùng rác trống"
                    rows={rows}
                    columns={[
                        { key: 'tour', label: 'Tour', render: (review) => review.tour?.name || '—' },
                        { key: 'user', label: 'Người đánh giá', render: (review) => (
                            <div>
                                <strong>{review.author_name || review.user?.name || 'Khách hàng'}</strong>
                                <div className="ui-sub">{review.author_email || review.user?.email || '—'}</div>
                            </div>
                        ) },
                        { key: 'comment', label: 'Nội dung', render: (review) => truncate(review.comment, 70) },
                        { key: 'deleted_at', label: 'Đã xóa lúc', render: (review) => datetime(review.deleted_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 180,
                            render: (review) => (
                                <div className="ui-row-actions">
                                    <Button size="sm" variant="outline" icon={<RotateCcw size={14} />} onClick={() => router.patch(routes.admin.reviews.restore(review.id), {}, { preserveScroll: true })}>Khôi phục</Button>
                                    <Button size="sm" variant="danger" icon={<Trash2 size={14} />} onClick={() => forceDelete(review)}>Xóa</Button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={reviews?.links} from={reviews?.from} to={reviews?.to} total={reviews?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
