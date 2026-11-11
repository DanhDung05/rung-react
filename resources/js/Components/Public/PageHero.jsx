import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { placeholderImage } from '../../lib/placeholder';

/**
 * Hero công khai dùng chung cho mọi trang con.
 *
 * Hero chỉ còn ảnh nền + overlay (giữ nguyên chiều cao).
 * Breadcrumb, tiêu đề và mô tả được đưa xuống khối PageHead ngay bên dưới.
 *
 * Props:
 *  - title, lead, eyebrow (eyebrow không còn hiển thị trong hero)
 *  - image (URL), size: default | compact | tall
 *  - breadcrumbs: [{ label, href? }]
 *  - actions: ReactNode (nút CTA)
 */
export default function PageHero({
    title,
    lead = null,
    image = null,
    size = 'default',
    heroKey = null,
    breadcrumbs = [],
    actions = null,
    align = 'left',
    children = null,
}) {
    const { settings } = usePage().props;
    const sizeClass = size === 'compact' ? 'rp-hero--compact' : size === 'tall' ? 'rp-hero--tall' : '';
    const heroImage = image || settings?.hero_images?.[heroKey] || placeholderImage(title || 'rung-adventure');

    return (
        <>
            <section
                className={`rp-hero rp-hero--media ${sizeClass}`.trim()}
                style={!image ? { background: 'linear-gradient(140deg, #102c24, #1c4034)' } : undefined}
                aria-hidden="true"
            >
                <img className="rp-hero__media" src={heroImage} alt="" fetchPriority="high" decoding="async" />
                <div className="rp-hero__overlay" />
            </section>

            <header className="rp-pagehead">
                <div className="rp-container" style={align === 'center' ? { textAlign: 'center' } : undefined}>
                    {breadcrumbs.length > 0 && (
                        <nav className="rp-breadcrumb rp-breadcrumb--dark" aria-label="Breadcrumb">
                            {breadcrumbs.map((crumb, index) => (
                                <React.Fragment key={`${crumb.label}-${index}`}>
                                    {index > 0 && <span className="rp-breadcrumb__sep">/</span>}
                                    {crumb.href ? <Link href={crumb.href}>{crumb.label}</Link> : <span>{crumb.label}</span>}
                                </React.Fragment>
                            ))}
                        </nav>
                    )}

                    <h1 className="rp-pagehead__title">{title}</h1>
                    {lead && <p className="rp-lead rp-pagehead__lead">{lead}</p>}
                    {actions && <div className="rp-pagehead__actions">{actions}</div>}
                    {children}
                </div>
            </header>
        </>
    );
}
