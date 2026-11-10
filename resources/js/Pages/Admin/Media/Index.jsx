import React from 'react';
import { router } from '@inertiajs/react';
import { Trash2, FileImage, Info } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import EmptyState from '../../../Components/UI/EmptyState';
import Pagination from '../../../Components/UI/Pagination';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime } from '../../../lib/format';
import { COLLECTION } from '../../../lib/constants';

function humanSize(bytes) {
    const size = Number(bytes || 0);
    if (size >= 1048576) return `${(size / 1048576).toFixed(1)} MB`;
    if (size >= 1024) return `${Math.round(size / 1024)} KB`;
    return `${size} B`;
}

export default function MediaIndex({ media, collections = [], mediableTypes = [], trashedCount = 0 }) {
    const { confirm, dialog } = useConfirm();
    const items = media?.data || [];

    const remove = async (item) => {
        const ok = await confirm({
            title: 'Chuyển ảnh vào thùng rác?',
            message: `Ảnh “${item.file_name}” sẽ được chuyển vào thùng rác (file vật lý vẫn giữ).`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.media.destroy(item.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thư viện ảnh"
            description="Quản lý toàn bộ ảnh đã upload."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Thư viện ảnh' }]}
            actions={
                <>
                    <Button href={routes.admin.media.trash} variant="outline">
                        Thùng rác {trashedCount > 0 ? `(${trashedCount})` : ''}
                    </Button>
                </>
            }
        >
            <div className="ui-notice">
                <Info size={15} /> Backend hiện chưa có endpoint upload vào Media Library. Ảnh được upload qua form Tour/Bài viết/Điểm đến/Slider và tự động lưu vào đây.
            </div>

            <Card>
                <FilterBar
                    url={routes.admin.media.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên file…"
                    filters={[
                        { name: 'collection', label: 'Bộ sưu tập', options: collections.map((value) => ({ value, label: COLLECTION[value] || value })) },
                        { name: 'mediable_type', label: 'Đối tượng', options: mediableTypes.map((value) => ({ value, label: String(value).split('\\').pop() })) },
                    ]}
                />

                {items.length === 0 ? (
                    <EmptyState title="Chưa có ảnh nào" description="Ảnh sẽ xuất hiện khi bạn upload trong form nội dung." />
                ) : (
                    <div className="ui-media-grid">
                        {items.map((item) => (
                            <figure className="ui-media-card" key={item.id}>
                                <div className="ui-media-card__thumb">
                                    {String(item.mime_type || '').startsWith('image/') ? (
                                        <img src={mediaUrl(item.file_path)} alt={item.alt_text || item.file_name} loading="lazy" />
                                    ) : (
                                        <FileImage size={28} />
                                    )}
                                </div>
                                <figcaption>
                                    <strong title={item.file_name}>{item.file_name}</strong>
                                    <span className="ui-sub">
                                        {humanSize(item.file_size)} · {COLLECTION[item.collection] || item.collection}
                                    </span>
                                    <span className="ui-sub">{datetime(item.created_at)}</span>
                                    <div className="ui-media-card__actions">
                                        <Badge tone="muted">{item.mediable_type ? String(item.mediable_type).split('\\').pop() : 'Tự do'}</Badge>
                                        <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa" onClick={() => remove(item)}>
                                            <Trash2 size={14} />
                                        </button>
                                    </div>
                                </figcaption>
                            </figure>
                        ))}
                    </div>
                )}

                <Pagination links={media?.links} from={media?.from} to={media?.to} total={media?.total} />
            </Card>

            {dialog}
        </AdminLayout>
    );
}
