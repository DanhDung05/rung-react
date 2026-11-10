import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Check, X, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { datetime, truncate } from '../../../lib/format';
import { REVIEW_STATUS } from '../../../lib/constants';

function Stars({ rating = 0 }) {
    return (
        <span className="ui-stars" aria-label={`${rating}/5 sao`}>
            {Array.from({ length: 5 }).map((_, index) => (
                <span key={index} className={index < rating ? 'is-on' : ''}>★</span>
            ))}
        </span>
    );
}

export default function ReviewsIndex({ reviews }) {
    const { confirm, dialog } = useConfirm();
    const rows = reviews?.data || [];

    const approve = (review) => router.patch(routes.admin.reviewApprove(review.id), {}, { preserveScroll: true });
    const reject = (review) => router.patch(routes.admin.reviewReject(review.id), {}, { preserveScroll: true });

    const remove = async (review) => {
        const ok = await confirm({ title: 'Chuyển đánh giá vào thùng rác?', message: 'Đánh giá này sẽ được chuyển vào thùng rác.', confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.reviews.destroy(review.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Quản lý đánh giá"
            description="Duyệt đánh giá của khách trước khi hiển thị."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Đánh giá' }]}
            actions={<Button href={routes.admin.reviews.trash} variant="outline">Thùng rác</Button>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.reviews.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo nội dung…"
                    filters={[{ name: 'status', label: 'Trạng thái', options: Object.entries(REVIEW_STATUS).map(([value, meta]) => ({ value, label: meta.label })) }]}
                />

                <DataTable
                    empty="Chưa có đánh giá nào"
                    rows={rows}
                    columns={[
                        { key: 'tour', label: 'Tour', render: (review) => (review.tour ? <Link className="ui-link" href={routes.admin.tours.show(review.tour.id)}>{truncate(review.tour.name, 40)}</Link> : '—') },
                        { key: 'user', label: 'Người đánh giá', render: (review) => review.user?.name || '—' },
                        { key: 'rating', label: 'Sao', render: (review) => <Stars rating={review.rating} /> },
                        { key: 'comment', label: 'Nội dung', render: (review) => truncate(review.comment, 70) },
                        { key: 'status', label: 'Trạng thái', render: (review) => <Badge tone={REVIEW_STATUS[review.status]?.tone}>{REVIEW_STATUS[review.status]?.label || review.status}</Badge> },
                        { key: 'created_at', label: 'Thời gian', render: (review) => datetime(review.created_at) },
                        {
                            key: 'actions', label: 'Thao tác', width: 150,
                            render: (review) => (
                                <div className="ui-row-actions">
                                    {review.status !== 'approved' && (
                                        <button type="button" className="ui-icon-btn" title="Duyệt" onClick={() => approve(review)}><Check size={15} /></button>
                                    )}
                                    {review.status !== 'rejected' && (
                                        <button type="button" className="ui-icon-btn" title="Từ chối" onClick={() => reject(review)}><X size={15} /></button>
                                    )}
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(review)}><Trash2 size={15} /></button>
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
