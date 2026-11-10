import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';

/**
 * Tiêu đề section + link "xem tất cả".
 */
export default function SectionHeading({ eyebrow = null, title, description = null, link = null, align = 'left', light = false }) {
    return (
        <div
            className="rp-spread"
            style={{
                marginBottom: 34,
                alignItems: align === 'center' ? 'center' : 'flex-end',
                flexDirection: align === 'center' ? 'column' : undefined,
                textAlign: align === 'center' ? 'center' : undefined,
            }}
        >
            <div>
                {eyebrow && <div className="rp-eyebrow">{eyebrow}</div>}
                <h2 className="rp-h2">{title}</h2>
                {description && <p className="rp-lead">{description}</p>}
            </div>

            {link && (
                <Link href={link.href} className="rp-link-arrow">
                    {link.label} <ArrowRight size={15} />
                </Link>
            )}
        </div>
    );
}
