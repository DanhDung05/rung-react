import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, Compass, MapPin, Mountain, Route } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import Gallery from '../../Components/Public/Gallery';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';
import { mediaUrl } from '../../lib/format';
import { placeholderImage } from '../../lib/placeholder';
import { breadcrumbSchema, destinationSchema } from '../../lib/schema';

export default function DestinationShow({ destination }) {
    const heroImage = mediaUrl(destination.image) || placeholderImage(destination.slug);
    const tours = destination.tours || [];
    const children = destination.children || [];

    const galleryImages = [
        { id: 'main', url: mediaUrl(destination.image), alt: destination.name },
        ...tours.map((tour) => ({ id: `t-${tour.id}`, url: mediaUrl(tour.featured_image), alt: tour.name })),
    ]
        .filter((image) => image.url)
        .filter((image, index, list) => list.findIndex((candidate) => candidate.url === image.url) === index);

    const breadcrumbs = [
        { label: 'Trang chủ', href: routes.home },
        { label: 'Điểm đến', href: routes.destinations.index },
        ...(destination.parent ? [{ label: destination.parent.name, href: routes.destinations.show(destination.parent.slug) }] : []),
        { label: destination.name },
    ];

    const facts = [
        destination.region && { icon: Compass, label: `Khu vực: ${destination.region}` },
        destination.province && { icon: MapPin, label: `Tỉnh thành: ${destination.province}` },
        destination.altitude && { icon: Mountain, label: `Cao độ: ${Number(destination.altitude).toLocaleString('vi-VN')} m` },
        { icon: Route, label: `${tours.length} hành trình` },
    ].filter(Boolean);

    return (
        <AppLayout>
            <Seo
                title={`${destination.name} — Rừng Adventure`}
                description={destination.description || destination.meta_description}
                image={heroImage}
                jsonLd={[
                    destinationSchema(destination),
                    breadcrumbSchema(breadcrumbs.map((crumb) => ({ name: crumb.label, url: crumb.href }))),
                ]}
            />

            <PageHero heroKey="destination" title={destination.name} lead={destination.description} image={heroImage} size="tall" breadcrumbs={breadcrumbs} />

            <section className="rp-section">
                <div className="rp-container rp-detail">
                    <div className="rp-detail__body">
                        {destination.content ? (
                            <div className="rp-prose" dangerouslySetInnerHTML={{ __html: destination.content }} />
                        ) : (
                            <p className="rp-prose">{destination.description}</p>
                        )}

                        {children.length > 0 && (
                            <div className="rp-tags" style={{ marginTop: 34 }}>
                                {children.map((child) => (
                                    <Link key={child.id} href={routes.destinations.show(child.slug)} className="rp-chip">
                                        {child.name}
                                    </Link>
                                ))}
                            </div>
                        )}
                    </div>

                    <aside className="rp-detail__side">
                        <div className="rp-price-card">
                            <div className="rp-price-card__label">Thông tin điểm đến</div>

                            <ul className="rp-price-card__list" style={{ borderTop: 0, paddingTop: 4, marginTop: 10 }}>
                                {facts.map((fact) => (
                                    <li key={fact.label}>
                                        <fact.icon size={15} /> {fact.label}
                                    </li>
                                ))}
                            </ul>

                            <Link href={routes.tours.index} className="rp-btn rp-btn--gold rp-btn--block" style={{ marginTop: 18 }}>
                                Xem hành trình <ArrowRight size={15} />
                            </Link>
                            <Link href={routes.contact} className="rp-btn rp-btn--outline rp-btn--block">
                                Liên hệ tư vấn
                            </Link>
                        </div>
                    </aside>
                </div>
            </section>

            {galleryImages.length > 1 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 30 }}>
                            <div>
                                <div className="rp-eyebrow">Thư viện</div>
                                <h2 className="rp-h2">Hình ảnh {destination.name}</h2>
                            </div>
                        </div>
                        <Reveal>
                            <Gallery images={galleryImages.slice(0, 6)} columns={3} />
                        </Reveal>
                    </div>
                </section>
            )}

            {tours.length > 0 && (
                <section className="rp-section">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 32 }}>
                            <div>
                                <div className="rp-eyebrow">Hành trình</div>
                                <h2 className="rp-h2">Tour tại {destination.name}</h2>
                            </div>
                            <Link href={routes.tours.index} className="rp-link-arrow">Tất cả tour <ArrowRight size={15} /></Link>
                        </div>

                        <Reveal>
                            <div className="h-tour-grid">
                                {tours.map((tour) => <TourCard key={tour.id} tour={tour} />)}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}
        </AppLayout>
    );
}
