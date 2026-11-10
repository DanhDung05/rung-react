import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Pencil, Trash2, Eye } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { useConfirm } from '../../../hooks/useConfirm';
import { routes } from '../../../lib/routes';
import { money, mediaUrl, date, datetime } from '../../../lib/format';
import { DIFFICULTY, STATUS } from '../../../lib/constants';

function Row({ label, children }) {
    return (
        <div className="ui-detail-row">
            <span>{label}</span>
            <div>{children ?? '—'}</div>
        </div>
    );
}

export default function ToursShow({ tour }) {
    const { confirm, dialog } = useConfirm();

    const remove = async () => {
        const ok = await confirm({
            title: 'Chuyển tour vào thùng rác?',
            message: `Tour “${tour.name}” sẽ được chuyển vào thùng rác.`,
            confirmLabel: 'Chuyển vào thùng rác',
        });
        if (ok) router.delete(routes.admin.tours.destroy(tour.id));
    };

    const gallery = (tour.media || []).filter((item) => item.collection === 'gallery');

    return (
        <AdminLayout
            title={tour.name}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tour', href: routes.admin.tours.index }, { label: 'Chi tiết' }]}
            actions={
                <>
                    <Button href={routes.admin.tours.index} variant="outline">← Danh sách</Button>
                    <Button href={routes.admin.tours.edit(tour.id)} icon={<Pencil size={15} />}>Sửa</Button>
                    <Button variant="danger" icon={<Trash2 size={15} />} onClick={remove}>Xóa</Button>
                </>
            }
        >
            <div className="admin-form-layout">
                <div>
                    <Card title="Thông tin tour">
                        <Row label="Danh mục">{tour.category?.name}</Row>
                        <Row label="Slug"><code>{tour.slug}</code></Row>
                        <Row label="Trạng thái">
                            <Badge tone={STATUS[tour.status]?.tone}>{STATUS[tour.status]?.label || tour.status}</Badge>
                            {tour.is_featured && <Badge tone="gold">Nổi bật</Badge>}
                        </Row>
                        <Row label="Mô tả ngắn">{tour.excerpt}</Row>
                        <Row label="Thời gian">{tour.duration_days || 0} ngày / {tour.duration_nights || 0} đêm</Row>
                        <Row label="Độ khó">{tour.difficulty ? DIFFICULTY[tour.difficulty] : null}</Row>
                        <Row label="Giá tham khảo">{money(tour.price_reference)}</Row>
                        <Row label="Khởi hành / kết thúc">{[tour.start_point, tour.end_point].filter(Boolean).join(' → ')}</Row>
                        <Row label="Quãng đường">{tour.distance_km ? `${tour.distance_km} km` : null}</Row>
                        <Row label="Độ cao">{tour.altitude_min || tour.altitude_max ? `${tour.altitude_min || 0} – ${tour.altitude_max || 0} m` : null}</Row>
                        <Row label="Thời điểm đẹp nhất">{tour.best_time}</Row>
                        <Row label="Ngày tạo">{datetime(tour.created_at)}</Row>
                        <Row label="Cập nhật">{datetime(tour.updated_at)}</Row>
                    </Card>

                    <Card title="Lịch trình">
                        {(tour.itineraries || []).length === 0 ? (
                            <p className="ui-hint">Chưa có lịch trình.</p>
                        ) : (
                            tour.itineraries.map((item) => (
                                <div className="ui-itinerary" key={item.id}>
                                    <strong>Ngày {item.day_number}: {item.title}</strong>
                                    {item.description && <p>{item.description}</p>}
                                    <div className="ui-itinerary__meta">
                                        {item.distance_km && <span>{item.distance_km} km</span>}
                                        {item.altitude && <span>{item.altitude} m</span>}
                                        {item.meals && <span>{item.meals}</span>}
                                        {item.accommodation && <span>{item.accommodation}</span>}
                                    </div>
                                </div>
                            ))
                        )}
                    </Card>

                    <Card title="Nội dung chi tiết">
                        {tour.content ? (
                            <div className="ui-prose" dangerouslySetInnerHTML={{ __html: tour.content }} />
                        ) : (
                            <p className="ui-hint">Chưa có nội dung.</p>
                        )}
                    </Card>
                </div>

                <aside>
                    <Card title="Ảnh đại diện">
                        {tour.featured_image ? (
                            <img className="ui-detail-image" src={mediaUrl(tour.featured_image)} alt={tour.name} />
                        ) : (
                            <p className="ui-hint">Chưa có ảnh.</p>
                        )}
                    </Card>

                    <Card title={`Thư viện ảnh (${gallery.length})`}>
                        {gallery.length === 0 ? (
                            <p className="ui-hint">Chưa có ảnh phụ.</p>
                        ) : (
                            <div className="ui-gallery-grid">
                                {gallery.map((item) => (
                                    <img key={item.id} src={mediaUrl(item.file_path)} alt={item.alt_text || ''} />
                                ))}
                            </div>
                        )}
                    </Card>

                    <Card title="Điểm đến">
                        {(tour.destinations || []).length === 0 ? (
                            <p className="ui-hint">Chưa gắn điểm đến.</p>
                        ) : (
                            <ul className="ui-chip-list">
                                {tour.destinations.map((destination) => (
                                    <li key={destination.id}>
                                        <Link href={routes.admin.destinations.show(destination.id)}>{destination.name}</Link>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </Card>

                    <Card title="Liên kết">
                        <Link href={routes.tours.show(tour.slug)} target="_blank" className="ui-btn ui-btn--outline ui-btn--block">
                            <Eye size={15} /> Xem trên website
                        </Link>
                    </Card>
                </aside>
            </div>

            {dialog}
        </AdminLayout>
    );
}
