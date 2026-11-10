import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { mediaUrl, money } from '../../lib/format';
import { resolveImage, placeholderImage } from '../../lib/placeholder';
import { routes } from '../../lib/routes';
import { DIFFICULTY } from '../../lib/constants';

export default function TourCard({ tour, tall = false }) {
    const [failed, setFailed] = useState(false);
    const realImage = mediaUrl(tour.featured_image);
    const image = failed ? placeholderImage(tour.slug || tour.name) : resolveImage(realImage, tour.slug || tour.name);
    const duration = tour.duration_days ? `${tour.duration_days} ngày` : null;
    const nights = tour.duration_nights ? `${tour.duration_nights} đêm` : null;

    return (
        <Link href={routes.tours.show(tour.slug)} className={`rp-card ${tall ? 'rp-card--tall' : ''}`}>
            <div className="rp-card__media" style={{ backgroundImage: `url("${placeholderImage(tour.slug || tour.name)}")`, backgroundSize: 'cover', backgroundPosition: 'center' }}>
                {image && (
                    <img
                        src={image}
                        alt={tour.name}
                        loading="lazy"
                        decoding="async"
                        onError={() => setFailed(true)}
                    />
                )}
                {tour.difficulty && <span className="rp-card__badge">{DIFFICULTY[tour.difficulty] || tour.difficulty}</span>}
            </div>

            <div className="rp-card__body">
                {tour.category?.name && <span className="rp-card__eyebrow">{tour.category.name}</span>}
                <h3 className="rp-card__title">{tour.name}</h3>
                {tour.excerpt && <p className="rp-card__desc">{tour.excerpt}</p>}

                <div className="rp-card__meta">
                    <span className="rp-card__facts">
                        {duration && <span>{duration}</span>}
                        {nights && <span>{nights}</span>}
                    </span>
                    <span className="rp-card__price">{money(tour.price_reference)}</span>
                </div>
            </div>
        </Link>
    );
}
