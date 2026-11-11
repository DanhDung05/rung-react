import React from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { ArrowRight, CalendarDays, Clock, MapPin, Mountain, Phone, Star, Users } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';
import { mediaUrl, money, date } from '../../lib/format';
import { DIFFICULTY } from '../../lib/constants';
import { placeholderImage } from '../../lib/placeholder';

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

function ReviewForm({ slug }) {
    const { data, setData, post, processing, errors, reset } = useForm({ rating: 5, comment: '' });

    const submit = (event) => {
        event.preventDefault();
        post(`/tour/${slug}/review`, {
            preserveScroll: true,
            onSuccess: () => reset('comment'),
        });
    };

    return (
        <form className="rp-review-form" onSubmit={submit}>
            <h3>Viết đánh giá</h3>

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

            <button type="submit" className="rp-btn rp-btn--gold" disabled={processing}>
                {processing ? 'Đang gửi...' : 'Gửi đánh giá'}
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
            <Seo title={`${tour.name} — Rừng Adventure`} description={tour.excerpt || tour.meta_description} image={heroImage} />

            <PageHero
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
                                        <strong>{review.user?.name || 'Khách hàng'}</strong>
                                        <span>{review.created_at ? date(review.created_at) : ''}</span>
                                    </div>
                                    <Stars value={review.rating} />
                                    {review.comment && <p>{review.comment}</p>}
                                </article>
                            ))}

                            {auth?.user ? (
                                <ReviewForm slug={tour.slug} />
                            ) : (
                                <p className="rp-muted">
                                    <Link href="/login" className="rp-link-arrow">Đăng nhập</Link> để gửi đánh giá.
                                </p>
                            )}
                        </div>
                    </div>

                    <aside className="rp-detail__side">
                        <div className="rp-price-card">
                            <div className="rp-price-card__label">Giá tham khảo</div>
                            <div className="rp-price-card__value">{money(tour.price_reference)}</div>
                            <div className="rp-price-card__unit">/ khách</div>

                            <Link href={routes.contact} className="rp-btn rp-btn--gold rp-btn--block">
                                Đặt hành trình <ArrowRight size={15} />
                            </Link>

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
        </AppLayout>
    );
}
