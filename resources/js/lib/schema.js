/**
 * Bộ tạo JSON-LD cho các trang public.
 * Tất cả hàm trả về object thuần, dùng với <Seo jsonLd={...} />.
 */

function absolute(path, appUrl) {
    if (!path) return undefined;
    if (/^https?:\/\//i.test(path)) return path;
    const base = String(appUrl || '').replace(/\/+$/, '');
    return base + (path.startsWith('/') ? path : `/${path}`);
}

export function breadcrumbSchema(items = [], appUrl = '') {
    return {
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: items.map((item, index) => ({
            '@type': 'ListItem',
            position: index + 1,
            name: item.label,
            item: item.href ? absolute(item.href, appUrl) : undefined,
        })),
    };
}

export function tourSchema(tour, appUrl = '') {
    if (!tour) return null;

    return {
        '@context': 'https://schema.org',
        '@type': 'TouristTrip',
        name: tour.name,
        description: tour.excerpt || tour.meta_description || undefined,
        image: absolute(tour.featured_image ? `/storage/${tour.featured_image}` : null, appUrl),
        url: absolute(`/tour/${tour.slug}`, appUrl),
        touristType: 'Adventure travel',
        itinerary: (tour.itineraries || []).map((item) => ({
            '@type': 'TouristAttraction',
            name: item.title,
            description: item.description || undefined,
        })),
        offers: tour.price_reference
            ? {
                  '@type': 'Offer',
                  price: tour.price_reference,
                  priceCurrency: 'VND',
                  availability: 'https://schema.org/InStock',
              }
            : undefined,
    };
}

export function articleSchema(post, appUrl = '') {
    if (!post) return null;

    return {
        '@context': 'https://schema.org',
        '@type': 'Article',
        headline: post.title,
        description: post.excerpt || post.meta_description || undefined,
        image: absolute(post.featured_image ? `/storage/${post.featured_image}` : null, appUrl),
        datePublished: post.published_at || undefined,
        dateModified: post.updated_at || undefined,
        author: post.author?.name ? { '@type': 'Person', name: post.author.name } : undefined,
        publisher: { '@type': 'Organization', name: 'Rung Adventure' },
        mainEntityOfPage: absolute(`/bai-viet/${post.slug}`, appUrl),
    };
}

export function faqSchema(faqs = []) {
    const list = Array.isArray(faqs) ? faqs : Object.values(faqs || {}).flat();

    if (!list.length) return null;

    return {
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        mainEntity: list.map((faq) => ({
            '@type': 'Question',
            name: faq.question,
            acceptedAnswer: { '@type': 'Answer', text: faq.answer },
        })),
    };
}

export function destinationSchema(destination, appUrl = '') {
    if (!destination) return null;

    return {
        '@context': 'https://schema.org',
        '@type': 'TouristDestination',
        name: destination.name,
        description: destination.description || undefined,
        image: absolute(destination.image ? `/storage/${destination.image}` : null, appUrl),
        url: absolute(`/diem-den/${destination.slug}`, appUrl),
        geo:
            destination.latitude && destination.longitude
                ? {
                      '@type': 'GeoCoordinates',
                      latitude: destination.latitude,
                      longitude: destination.longitude,
                  }
                : undefined,
    };
}
