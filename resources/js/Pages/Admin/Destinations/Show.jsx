import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { mediaUrl, datetime, number } from '../../../lib/format';
import { STATUS } from '../../../lib/constants';

export default function DestinationsShow({ destination }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({ title: 'Chuyển điểm đến vào thùng rác?', message: `Điểm đến “${destination.name}” sẽ được chuyển vào thùng rác.`, confirmLabel: 'Chuyển vào thùng rác' });
        if (ok) router.delete(routes.admin.destinations.destroy(destination.id));
    };

    return (
        <AdminLayout
            title={destination.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Điểm đến', href: routes.admin.destinations.index }, { label: 'Chi tiết' }]}
            actions={<><Button href={routes.admin.destinations.index} variant="outline">← Danh sách</Button><Button href={routes.admin.destinations.edit(destination.id)} icon={<Pencil size={15} />}>Sửa</Button><Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button></>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin điểm đến">
                        <div className="ui-detail-row"><span>Điểm đến cha</span><div>{destination.parent?.name || '—'}</div></div>
                        <div className="ui-detail-row"><span>Slug</span><div><code>{destination.slug}</code></div></div>
                        <div className="ui-detail-row"><span>Vùng / tỉnh</span><div>{[destination.region, destination.province].filter(Boolean).join(' · ') || '—'}</div></div>
                        <div className="ui-detail-row"><span>Tọa độ</span><div>{destination.latitude && destination.longitude ? `${destination.latitude}, ${destination.longitude}` : '—'}</div></div>
                        <div className="ui-detail-row"><span>Độ cao</span><div>{destination.altitude ? `${destination.altitude} m` : '—'}</div></div>
                        <div className="ui-detail-row"><span>Trạng thái</span><div><Badge tone={STATUS[destination.status]?.tone}>{STATUS[destination.status]?.label || destination.status}</Badge>{destination.is_featured && <Badge tone="gold">Nổi bật</Badge>}</div></div>
                        <div className="ui-detail-row"><span>Mô tả</span><div>{destination.description || '—'}</div></div>
                        <div className="ui-detail-row"><span>Cập nhật</span><div>{datetime(destination.updated_at)}</div></div>
                    </Card>
                    <Card title="Nội dung">
                        {destination.content ? <div className="ui-prose" dangerouslySetInnerHTML={{ __html: destination.content }} /> : <p className="ui-hint">Chưa có nội dung.</p>}
                    </Card>
                </div>
                <aside>
                    <Card title="Ảnh đại diện">
                        {destination.image ? <img className="ui-detail-image" src={mediaUrl(destination.image)} alt={destination.name} /> : <p className="ui-hint">Chưa có ảnh.</p>}
                    </Card>
                    <Card title={`Điểm đến con (${number(destination.children_count)})`}>
                        {(destination.children || []).length === 0 ? <p className="ui-hint">Không có điểm con.</p> : (
                            <ul className="ui-chip-list">{destination.children.map((child) => <li key={child.id}><Link href={routes.admin.destinations.show(child.id)}>{child.name}</Link></li>)}</ul>
                        )}
                    </Card>
                    <Card title={`Tour tại đây (${number(destination.tours_count)})`}>
                        {(destination.tours || []).length === 0 ? <p className="ui-hint">Chưa có tour.</p> : (
                            <ul className="ui-feed">{destination.tours.map((tour) => <li key={tour.id}><Link href={routes.admin.tours.show(tour.id)}><strong>{tour.name}</strong><small>{tour.slug}</small></Link></li>)}</ul>
                        )}
                    </Card>
                    <Card title="Liên kết">
                        <Link href={routes.destinations.show(destination.slug)} target="_blank" className="ui-btn ui-btn--outline ui-btn--block"><Eye size={15} /> Xem trên website</Link>
                    </Card>
                </aside>
            </div>
            {dialog}
        </AdminLayout>
    );
}
