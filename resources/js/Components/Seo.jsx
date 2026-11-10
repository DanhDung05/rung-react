import React from 'react';
import { Head, usePage } from '@inertiajs/react';

/**
 * Thẻ <head> cho mọi trang Inertia: title, description, canonical, OG, Twitter, JSON-LD.
 *
 * Dùng:
 *   <Seo title="Tour khám phá" description="..." />
 */
export default function Seo({ title = null, description = null, image = null, type = 'website', noindex = false, jsonLd = null }) {
    const page = usePage();
    const settings = page.props.settings || {};
    const appUrl = String(page.props.app?.url || '').replace(/\/+$/, '');

    const siteName = settings.site_name || 'Rung Adventure';
    const fullTitle = title ? `${title} — ${siteName}` : settings.seo_title || siteName;
    const desc = description || settings.seo_description || settings.site_description || '';
    const canonical = appUrl + String(page.url || '/').split('?')[0];

    const absolute = (value) => {
        if (!value) return null;
        if (/^https?:\/\//i.test(value)) return value;
        return appUrl + (value.startsWith('/') ? value : `/${value}`);
    };

    const ogImage = absolute(image || settings.logo_url);
    const favicon = settings.favicon_url || null;

    return (
        <Head>
            <title>{fullTitle}</title>
            <meta name="description" content={desc} />
            {settings.seo_keywords && <meta name="keywords" content={settings.seo_keywords} />}
            <meta name="robots" content={noindex ? 'noindex, nofollow' : 'index, follow'} />
            <link rel="canonical" href={canonical} />

            <meta property="og:site_name" content={siteName} />
            <meta property="og:title" content={fullTitle} />
            <meta property="og:description" content={desc} />
            <meta property="og:type" content={type} />
            <meta property="og:url" content={canonical} />
            {ogImage && <meta property="og:image" content={ogImage} />}

            <meta name="twitter:card" content={ogImage ? 'summary_large_image' : 'summary'} />
            <meta name="twitter:title" content={fullTitle} />
            <meta name="twitter:description" content={desc} />
            {ogImage && <meta name="twitter:image" content={ogImage} />}

            {favicon && <link rel="icon" href={favicon} />}
            {jsonLd && (
                <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
            )}
        </Head>
    );
}
