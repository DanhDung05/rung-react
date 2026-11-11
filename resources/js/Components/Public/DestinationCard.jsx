import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { MapPin } from 'lucide-react';
import { mediaUrl } from '../../lib/format';
import { placeholderImage, resolveImage } from '../../lib/placeholder';
import { routes } from '../../lib/routes';

export default function DestinationCard({ destination }) {
    const [failed, setFailed] = useState(false);
    const image = failed ? placeholderImage(destination.slug || destination.name) : resolveImage(mediaUrl(destination.image), destination.slug || destination.name);
    const place = [destination.province, destination.region].filter(Boolean).join(' · ');

    return (
        <Link href={routes.destinations.show(destination.slug)} className="rp-card">
            <div className="rp-card__media" style={{ backgroundImage: `url("${placeholderImage(destination.slug || destination.name)}")`, backgroundSize: 'cover', backgroundPosition: 'center' }}>
                <img src={image} alt={destination.name} loading="lazy" decoding="async" onError={() => setFailed(true)} />
                {destination.region && <span className="rp-card__badge">{destination.region}</span>}
            </div>

            <div className="rp-card__body">
                <h3 className="rp-card__title">{destination.name}</h3>
                {destination.description && <p className="rp-card__desc">{destination.description}</p>}

                <div className="rp-card__meta">
                    <span className="rp-card__facts">
                        {place && (
                            <span>
                                <MapPin size={13} /> {place}
                            </span>
                        )}
                    </span>
                    {destination.tours_count !== undefined && <span>{destination.tours_count} tour</span>}
                </div>
            </div>
        </Link>
    );
}
