import React, { useState } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { ArrowRight, CalendarDays, Clock, MapPin, Mountain, Phone, Star, Users } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import Reveal from '../../Components/Public/Reveal';
import Modal from '../../Components/UI/Modal';
import { routes } from '../../lib/routes';
import { mediaUrl, money, date } from '../../lib/format';
import { DIFFICULTY } from '../../lib/constants';
import { placeholderImage } from '../../lib/placeholder';
import { tourSchema, breadcrumbSchema } from '../../lib/schema';

function Stars({ value = 0, onChange = null }) {
    return (
        <span className={`rp-stars ${onChange ? 'rp-stars--input' : ''}`.trim()}>
            {[1, 2, 3, 4, 5].map((star) =>
                onChange ? (
                    <button key={star} type="button" onClick={() => onChange(star)} aria-label={`${star} sao`}>
                        <Star size={18} fill={star <= value ? 'currentColor' : 'none'} />
                    </button>
                ) : (
                    <Star key={star} size={16} fill={star <= Math.round(value) ? 'currentColor' : 'none'} />
                )
            )}
        </span>
    );
}

function ReviewForm({ slug, guest = false }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        rating: 5,
        comment: '',
        author_name: '',
        author_email: '',
        website: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post(routes.tours.review(slug), {
            preserveScroll: true,
            onSuccess: () => reset('comment'),
        });
    };

    return (
        <form className="rp-review-form" onSubmit={submit}>
            <h3>Viết đánh giá</h3>

            {guest && (
                <div className="rp-form-grid">
                    <label className="rp-field">
                        <span>Họ tên</span>
                        <input value={data.author_name} onChange={(event) => setData('author_name', event.target.value)} placeholder="Nguyễn Văn A" />
                        {errors.author_name && <small className="rp-error">{errors.author_name}</small>}
                    </label>
                    <label className="rp-field">
                        <span>Email</span>
                        <input type="email" value={data.author_email} onChange={(event) => setData('author_email', event.target.value)} placeholder="email@example.com" />
                        {errors.author_email && <small className="rp-error">{errors.author_email}</small>}
                    </label>
                </div>
            )}

            <div className="rp-field">
                <span>Chấm điểm</span>
                <Stars value={data.rating} onChange={(rating) => setData('rating', rating)} />
                {errors.rating && <small className="rp-error">{errors.rating}</small>}
            </div>

            <label className="rp-field">
                <span>Cảm nhận của bạn</span>
                <textarea
                    rows={4}
                    value={data.comment}
                    onChange={(event) => setData('comment', event.target.value)}
                    placeholder="Chia sẻ trải nghiệm của bạn về hành trình này..."
                />
                {errors.comment && <small className="rp-error">{errors.comment}</small>}
            </label>

            <div className="hp-field" aria-hidden="true">
                <label>Website<input type="text" name="website" value={data.website} onChange={(event) => setData('website', event.target.value)} tabIndex={-1} autoComplete="off" /></label>
            </div>

            <button type="submit" className="rp-btn rp-btn--gold" disabled={processing}>
                {processing ? 'Đang gửi...' : 'Gửi đánh giá'}
            </button>
        </form>
    );
}

function TourInquiryForm({ tour, onDone }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        phone: '',
        people_count: 2,
        preferred_date: '',
        message: '',
        website: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post(routes.tours.inquiry(tour.slug), {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                onDone();
            },
        });
    };

    return (
        <form className="rp-inquiry-form" onSubmit={submit}>
            <div className="rp-form-grid">
                <label className="rp-field">
                    <span>Họ tên *</span>
                    <input value={data.name} onChange={(event) => setData('name', event.target.value)} />
                    {errors.name && <small className="rp-error">{errors.name}</small>}
                </label>
                <label className="rp-field">
                    <span>Email *</span>
                    <input type="email" value={data.email} onChange={(event) => setData('email', event.target.value)} />
                    {errors.email && <small className="rp-error">{errors.email}</small>}
                </label>
                <label className="rp-field">
                    <span>Số điện thoại *</span>
                    <input value={data.phone} onChange={(event) => setData('phone', event.target.value)} placeholder="0912345678" />
                    {errors.phone && <small className="rp-error">{errors.phone}</small>}
                </label>
                <label className="rp-field">
                    <span>Số người *</span>
                    <input type="number" min="1" max="50" value={data.people_count} onChange={(event) => setData('people_count', event.target.value)} />
                    {errors.people_count && <small className="rp-error">{errors.people_count}</small>}
                </label>
                <label className="rp-field">
                    <span>Ngày dự kiến</span>
                    <input type="date" min={new Date().toISOString().slice(0, 10)} value={data.preferred_date} onChange={(event) => setData('preferred_date', event.target.value)} />
                    {errors.preferred_date && <small className="rp-error">{errors.preferred_date}</small>}
                </label>
            </div>

            <label className="rp-field">
                <span>Ghi chú</span>
                <textarea rows={3} value={data.message} onChange={(event) => setData('message', event.target.value)} placeholder="Bạn cần hỗ trợ thêm điều gì?" />
                {errors.message && <small className="rp-error">{errors.message}</small>}
            </label>

            <div className="hp-field" aria-hidden="true">
                <label>Website<input type="text" name="website" value={data.website} onChange={(event) => setData('website', event.target.value)} tabIndex={-1} autoComplete="off" /></label>
            </div>

            <button type="submit" className="rp-btn rp-btn--gold rp-btn--block" disabled={processing}>
                {processing ? 'Đang gửi...' : 'Gửi yêu cầu tư vấn'}
            </button>
        </form>
    );
}

export default function TourShow({ tour, relatedTours = [] }) {
    const { auth, settings } = usePage().props;
    const heroImage = mediaUrl(tour.featured_image) || placeholderImage(tour.slug);
    const reviews = tour.reviews || [];
    const itinerary = tour.itineraries || [];
    const destinations = tour.destinations || [];
    const [inquiryOpen, setInquiryOpen] = useState(false);

    const average = reviews.length
        ? reviews.reduce((sum, review) => sum + Number(review.rating || 0), 0) / reviews.length
        : 0;

    const facts = [
        tour.duration_days && { icon: Clock, label: `${tour.duration_days} ngày${tour.duration_nights ? ` ${tour.duration_nights} đêm` : ''}` },
        tour.difficulty && { icon: Mountain, label: DIFFICULTY[tour.difficulty] || tour.difficulty },
        tour.start_point && { icon: MapPin, label: `Khởi hành: ${tour.start_point}` },
        tour.distance_km && { icon: CalendarDays, label: `${Number(tour.distance_km)} km` },
    ].filter(Boolean);

    return (
        <AppLayout>
            <Seo
                title={`${tour.name} — Rừng Adventure`}
                description={tour.excerpt || tour.meta_description}
                image={heroImage}
                jsonLd={[
                    tourSchema(tour),
                    breadcrumbSchema([
                        { name: 'Trang chủ', url: routes.home },
                        { name: 'Hành trình', url: routes.tours.index },
                        { name: tour.name, url: `/tour/${tour.slug}` },
                    ]),
                ]}
            />

            <PageHero heroKey="tour"
                title={tour.name}
                lead={tour.excerpt}
                image={heroImage}
                size="tall"
                breadcrumbs={[
                    { label: 'Trang chủ', href: routes.home },
                    { label: 'Hành trình', href: routes.tours.index },
                    { label: tour.name },
                ]}
            />

            <section className="rp-section">
                <div className="rp-container rp-detail">
                    <div className="rp-detail__body">
                        <div className="rp-facts">
                            {facts.map((fact) => (
                                <div className="rp-fact" key={fact.label}>
                                    <fact.icon size={17} />
                                    <span>{fact.label}</span>
                                </div>
                            ))}
                        </div>

                        {tour.content && (
                            <div className="rp-prose" dangerouslySetInnerHTML={{ __html: tour.content }} />
                        )}

                        {itinerary.length > 0 && (
                            <div className="rp-itinerary">
                                <h2 className="rp-h2">Lịch trình chi tiết</h2>

                                {itinerary.map((day) => (
                                    <details className="rp-itinerary__day" key={day.id} open={day.day_number === 1}>
                                        <summary>
                                            <span className="rp-itinerary__num">Ngày {day.day_number}</span>
                                            <span className="rp-itinerary__title">{day.title}</span>
                                        </summary>
                                        <div className="rp-itinerary__body">
                                            {day.description && <p>{day.description}</p>}
                                            <div className="rp-itinerary__meta">
                                                {day.distance_km && <span>{Number(day.distance_km)} km</span>}
                                                {day.altitude && <span>Cao độ {day.altitude} m</span>}
                                                {day.meals && <span>Bữa ăn: {day.meals}</span>}
                                                {day.accommodation && <span>Nghỉ: {day.accommodation}</span>}
                                            </div>
                                        </div>
                                    </details>
                                ))}
                            </div>
                        )}

                        {destinations.length > 0 && (
                            <div className="rp-tags">
                                {destinations.map((destination) => (
                                    <Link key={destination.id} href={routes.destinations.show(destination.slug)} className="rp-chip">
                                        {destination.name}
                                    </Link>
                                ))}
                            </div>
                        )}

                        <div className="rp-reviews">
                            <div className="rp-spread">
                                <h2 className="rp-h2">Đánh giá hành trình</h2>
                                {reviews.length > 0 && (
                                    <span className="rp-reviews__score">
                                        <Stars value={average} /> {average.toFixed(1)}/5 · {reviews.length} đánh giá
                                    </span>
                                )}
                            </div>

                            {reviews.length === 0 && <p className="rp-muted">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>}

                            {reviews.map((review) => (
                                <article className="rp-review" key={review.id}>
                                    <div className="rp-review__head">
                                        <strong>{review.author_name || review.user?.name || 'Khách hàng'}</strong>
                                        <span>{review.created_at ? date(review.created_at) : ''}</span>
                                    </div>
                                    <Stars value={review.rating} />
                                    {review.comment && <p>{review.comment}</p>}
                                </article>
                            ))}

                            <ReviewForm slug={tour.slug} guest={!auth?.user} />
                        </div>
                    </div>

                    <aside className="rp-detail__side">
                        <div className="rp-price-card">
                            <div className="rp-price-card__label">Giá tham khảo</div>
                            <div className="rp-price-card__value">{money(tour.price_reference)}</div>
                            <div className="rp-price-card__unit">/ khách</div>

                            <button type="button" className="rp-btn rp-btn--gold rp-btn--block" onClick={() => setInquiryOpen(true)}>
                                Yêu cầu tư vấn <ArrowRight size={15} />
                            </button>

                            <a href={`tel:${usePage().props.settings?.contact_phone || ''}`} className="rp-btn rp-btn--outline rp-btn--block">
                                <Phone size={15} /> Gọi tư vấn
                            </a>

                            <ul className="rp-price-card__list">
                                {tour.duration_days && <li><Clock size={15} /> {tour.duration_days} ngày {tour.duration_nights ? `${tour.duration_nights} đêm` : ''}</li>}
                                {tour.start_point && <li><MapPin size={15} /> {tour.start_point}{tour.end_point ? ` → ${tour.end_point}` : ''}</li>}
                                {tour.best_time && <li><CalendarDays size={15} /> Thời điểm: {tour.best_time}</li>}
                                {tour.category?.name && <li><Users size={15} /> {tour.category.name}</li>}
                            </ul>
                        </div>
                    </aside>
                </div>
            </section>

            {relatedTours.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 32 }}>
                            <h2 className="rp-h2">Hành trình tương tự</h2>
                            <Link href={routes.tours.index} className="rp-link-arrow">Tất cả tour <ArrowRight size={15} /></Link>
                        </div>

                        <Reveal>
                            <div className="h-tour-grid">
                                {relatedTours.map((item) => <TourCard key={item.id} tour={item} />)}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            <Modal open={inquiryOpen} onClose={() => setInquiryOpen(false)} title={`Yêu cầu tư vấn: ${tour.name}`} size="md">
                <TourInquiryForm tour={tour} onDone={() => setInquiryOpen(false)} />
            </Modal>
        </AppLayout>
    );
}
