import React from 'react';
import { router } from '@inertiajs/react';
import { RotateCcw, Trash2, FileImage } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import EmptyState from '../../../Components/UI/EmptyState';
import Pagination from '../../../Components/UI/Pagination';
import FilterBar from '../../../Components/Data/FilterBar';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime } from '../../../lib/format';
import { COLLECTION } from '../../../lib/constants';

export default function MediaTrash({ media, collections = [] }) {
    const { confirm, dialog } = useConfirm();
    const items = media?.data || [];

    const forceDelete = async (item) => {
        const ok = await confirm({
            title: 'Xóa vĩnh viễn?',
            message: `Ảnh “${item.file_name}” và file vật lý sẽ bị xóa vĩnh viễn. Không thể hoàn tác.`,
            confirmLabel: 'Xóa vĩnh viễn',
        });
        if (ok) router.delete(routes.admin.media.forceDestroy(item.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thùng rác — Thư viện ảnh"
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Thư viện ảnh', href: routes.admin.media.index }, { label: 'Thùng rác' }]}
            actions={<Button href={routes.admin.media.index} variant="outline">← Thư viện</Button>}
        >
            <Card>
                <FilterBar
                    url={routes.admin.media.trash}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên file…"
                    filters={[{ name: 'collection', label: 'Bộ sưu tập', options: collections.map((value) => ({ value, label: COLLECTION[value] || value })) }]}
                />

                {items.length === 0 ? (
                    <EmptyState title="Thùng rác trống" />
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
                                    <span className="ui-sub">{COLLECTION[item.collection] || item.collection}</span>
                                    <span className="ui-sub">Xóa: {datetime(item.deleted_at)}</span>
                                    <div className="ui-media-card__actions">
                                        <Button size="sm" variant="outline" icon={<RotateCcw size={13} />} onClick={() => router.patch(routes.admin.media.restore(item.id), {}, { preserveScroll: true })}>
                                            Khôi phục
                                        </Button>
                                        <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xóa vĩnh viễn" onClick={() => forceDelete(item)}>
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
