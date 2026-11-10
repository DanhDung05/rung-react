import React from 'react';
import { Link } from '@inertiajs/react';

/**
 * Hero công khai dùng chung cho mọi trang.
 *
 * Props:
 *  - title, lead, eyebrow
 *  - image (URL), size: default | compact | tall
 *  - breadcrumbs: [{ label, href? }]
 *  - actions: ReactNode (nút CTA)
 */
export default function PageHero({
    title,
    lead = null,
    eyebrow = null,
    image = null,
    size = 'default',
    breadcrumbs = [],
    actions = null,
    align = 'left',
    children = null,
}) {
    const sizeClass = size === 'compact' ? 'rp-hero--compact' : size === 'tall' ? 'rp-hero--tall' : '';

    return (
        <section className={`rp-hero ${sizeClass}`.trim()} style={!image ? { background: 'linear-gradient(140deg, #102c24, #1c4034)' } : undefined}>
            {image && (
                <img className="rp-hero__media" src={image} alt="" fetchPriority="high" decoding="async" />
            )}
            <div className="rp-hero__overlay" />
            <div className="rp-container rp-hero__content" style={align === 'center' ? { textAlign: 'center', marginInline: 'auto' } : undefined}>
                {breadcrumbs.length > 0 && (
                    <nav className="rp-breadcrumb" aria-label="Breadcrumb">
                        {breadcrumbs.map((crumb, index) => (
                            <React.Fragment key={`${crumb.label}-${index}`}>
                                {index > 0 && <span className="rp-breadcrumb__sep">/</span>}
                                {crumb.href ? <Link href={crumb.href}>{crumb.label}</Link> : <span>{crumb.label}</span>}
                            </React.Fragment>
                        ))}
                    </nav>
                )}

                {eyebrow && <div className="rp-eyebrow">{eyebrow}</div>}
                <h1 className="rp-display">{title}</h1>
                {lead && <p className="rp-lead">{lead}</p>}
                {actions && <div className="rp-hero__actions">{actions}</div>}
                {children}
            </div>
        </section>
    );
}
