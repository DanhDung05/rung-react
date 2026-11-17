import React from 'react';
import { Link, router } from '@inertiajs/react';
import { ArrowDown, ArrowUp, ExternalLink, Pencil, Plus, Trash2 } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';

export default function GallerySlidesIndex({ slides }) {
    const { confirm, dialog } = useConfirm();
    const rows = slides?.data || [];

    const move = (slide, direction) => {
        router.patch(routes.admin.gallerySlides.move(slide.id, direction), {}, { preserveScroll: true });
    };

    const remove = async (slide) => {
        const ok = await confirm({
            title: 'Xoá vĩnh viễn ảnh này?',
            message: 'Ảnh slide và ảnh gốc sẽ bị xoá khỏi ổ đĩa. Thao tác này không thể hoàn tác.',
            confirmLabel: 'Xoá vĩnh viễn',
        });
        if (ok) router.delete(routes.admin.gallerySlides.destroy(slide.id), { preserveScroll: true });
    };

    return (
        <AdminLayout
            title="Thư viện ảnh"
            description="Quản lý ảnh carousel 3D ở trang chủ."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Thư viện ảnh' }]}
            actions={
                <>
                    <a className="ui-btn ui-btn--outline" href="/gallery-carousel" target="_blank" rel="noreferrer">
                        <ExternalLink size={15} /> Xem carousel
                    </a>
                    <Button href={routes.admin.gallerySlides.create} icon={<Plus size={16} />}>Thêm ảnh</Button>
                </>
            }
        >
            <Card>
                <DataTable
                    empty="Chưa có ảnh thư viện nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'image',
                            label: '',
                            width: 76,
                            render: (slide) => (
                                <span style={{ display: 'block', width: 48, height: 64, borderRadius: 5, overflow: 'hidden' }}>
                                    <img
                                        src={slide.slide_url}
                                        alt=""
                                        style={{ width: '100%', height: '100%', objectFit: 'cover', display: 'block' }}
                                    />
                                </span>
                            ),
                        },
                        {
                            key: 'title',
                            label: 'Ảnh',
                            render: (slide) => (
                                <div>
                                    <strong>{slide.title}</strong>
                                    <div className="ui-sub">{slide.category}</div>
                                    {slide.accent && <div className="ui-sub">{slide.accent}</div>}
                                </div>
                            ),
                        },
                        {
                            key: 'is_active',
                            label: 'Hiển thị',
                            render: (slide) => (
                                <Badge tone={slide.is_active ? 'success' : 'muted'}>{slide.is_active ? 'Có' : 'Ẩn'}</Badge>
                            ),
                        },
                        { key: 'sort_order', label: 'Thứ tự', width: 80, render: (slide) => slide.sort_order },
                        {
                            key: 'move',
                            label: 'Sắp xếp',
                            width: 100,
                            render: (slide) => (
                                <div className="ui-row-actions">
                                    <button type="button" className="ui-icon-btn" title="Lên" onClick={() => move(slide, 'up')}><ArrowUp size={15} /></button>
                                    <button type="button" className="ui-icon-btn" title="Xuống" onClick={() => move(slide, 'down')}><ArrowDown size={15} /></button>
                                </div>
                            ),
                        },
                        {
                            key: 'actions',
                            label: 'Thao tác',
                            width: 100,
                            render: (slide) => (
                                <div className="ui-row-actions">
                                    <Link href={routes.admin.gallerySlides.edit(slide.id)} className="ui-icon-btn" title="Sửa"><Pencil size={15} /></Link>
                                    <button type="button" className="ui-icon-btn ui-icon-btn--danger" title="Xoá" onClick={() => remove(slide)}><Trash2 size={15} /></button>
                                </div>
                            ),
                        },
                    ]}
                />
                <Pagination links={slides?.links} from={slides?.from} to={slides?.to} total={slides?.total} />
            </Card>
            {dialog}
        </AdminLayout>
    );
}
