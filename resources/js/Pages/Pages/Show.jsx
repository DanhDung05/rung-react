import React from 'react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import { routes } from '../../lib/routes';
import { mediaUrl } from '../../lib/format';
import { placeholderImage } from '../../lib/placeholder';

export default function PageShow({ page }) {
    const image = mediaUrl(page?.featured_image) || placeholderImage(page?.slug || 'trang-tinh');

    return (
        <AppLayout>
            <Seo
                title={page?.meta_title || `${page?.title} — Rừng Adventure`}
                description={page?.meta_description}
                image={image}
            />

            <PageHero
                title={page?.title}
                image={image}
                size="tall"
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: page?.title }]}
            />

            <section className="rp-section">
                <div className="rp-container rp-article">
                    <div className="rp-prose" dangerouslySetInnerHTML={{ __html: page?.content || '' }} />
                </div>
            </section>
        </AppLayout>
    );
}
