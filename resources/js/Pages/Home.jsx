import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, ArrowUpRight, Phone, Mail } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import Reveal from '../Components/Public/Reveal';
import HeroSlider from '../Components/Public/HeroSlider';
import TourCard from '../Components/Public/TourCard';
import MediaCarousel from '../Components/Public/MediaCarousel';
import { routes } from '../lib/routes';
import { mediaUrl, date } from '../lib/format';
import { placeholderImage } from '../lib/placeholder';

export default function Home({ sliders = [], featuredTours = [], latestPosts = [], settings = {}, visual = {} }) {
    const tours = Array.isArray(featuredTours) ? featuredTours : featuredTours?.data || [];
    const posts = Array.isArray(latestPosts) ? latestPosts : latestPosts?.data || [];
    const about = settings?.about || {};
    const gallery = settings?.home_gallery || {};
    const galleryOverride = gallery.override_enabled === true;

    const ve = (id) => visual?.home?.[id] || {};
    const sectionOn = (name) => ve(`home.${name}.enabled`).enabled !== false;
    const customSections = visual?.home?.['home.custom']?.sections || [];
    const DEFAULT_ORDER = ['welcome', 'tours', 'band', 'gallery', 'posts', 'cta'];
    const sectionOrder = visual?.home?.['home.layout']?.order || DEFAULT_ORDER;
    const orderOf = (key) => {
        const index = sectionOrder.indexOf(key);
        if (index !== -1) return index;
        const group = sectionOrder.indexOf('custom');
        if (group !== -1) return group;
        if (key.startsWith('custom:')) {
            const band = sectionOrder.indexOf('band');
            if (band !== -1) return band;
        }
        return 99;
    };

    const heroImage = mediaUrl(sliders[0]?.image) || null;
    const bandImage = ve('home.band.image').image || heroImage;
    const ctaImage = ve('home.cta.image').image || settings?.hero_images?.contact || heroImage;

    const curatedGallery = Array.isArray(settings?.home_gallery_media) ? settings.home_gallery_media : [];

    const galleryImages = (curatedGallery.length > 0
        ? curatedGallery.map((item, index) => ({ id: `g-${index}`, url: item.url, alt: item.alt || '', caption: item.caption || '' }))
        : [
            ...sliders.map((item) => ({ id: `s-${item.id}`, url: mediaUrl(item.image), alt: item.title || '' })),
            ...tours
                .filter((item) => item.featured_image)
                .map((item) => ({ id: `t-${item.id}`, url: mediaUrl(item.featured_image), alt: item.name })),
        ])
        .filter((item) => item.url)
        .filter((item, index, list) => list.findIndex((candidate) => candidate.url === item.url) === index);

    if (curatedGallery.length === 0) {
        while (galleryImages.length < 5) {
            galleryImages.push({
                id: `ph-${galleryImages.length}`,
                url: placeholderImage(`gallery-${galleryImages.length}`),
                alt: 'Rừng Adventure',
            });
        }
    }

    return (
        <AppLayout className="h-home">
            <Seo description={settings?.site_description} />

            <HeroSlider
                sliders={sliders}
                fallbackImage={heroImage}
                fallbackTitle={about.hero_title}
                fallbackLead={about.hero_subtitle || settings?.site_description}
                overlay={settings?.hero_overlay_enabled !== false}
            />

            {/* 1. Welcome — Warm Cream */}
            <section className="rp-section rp-section--cream" data-ui="home.welcome.section" style={{ display: sectionOn('welcome') ? undefined : 'none', order: orderOf('welcome') }}>
                <div className="rp-container">
                    <Reveal>
                        <div className="h-welcome">
                            <div className="rp-eyebrow" data-ui="home.welcome.eyebrow">Rừng Adventure · Việt Nam</div>
                            <h2 className="h-welcome__title">
                                Chào mừng đến <em data-ui="home.welcome.accent">Rừng Adventure</em>
                            </h2>
                            <p className="h-welcome__lead" data-ui="home.welcome.lead">
                                {settings?.site_description ||
                                    'Những hành trình trekking và khám phá thiên nhiên Việt Nam dành cho người muốn đi chậm, hiểu sâu và tôn trọng vùng đất mình đi qua.'}
                            </p>

                            <div className="h-welcome__chips">
                                <Link href={routes.about} className="rp-chip">Về chúng tôi</Link>
                                <Link href={routes.tours.index} className="rp-chip">Các hành trình</Link>
                                <Link href={routes.faq} className="rp-chip">An toàn &amp; FAQ</Link>
                                <Link href={routes.contact} className="rp-chip">Liên hệ</Link>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>

            {/* 2. Hành trình nổi bật — White, một lưới duy nhất */}
            {tours.length > 0 && (
                <section className="rp-section" data-ui="home.tours.section" style={{ display: sectionOn('tours') ? undefined : 'none', order: orderOf('tours') }}>
                    <div className="rp-container">
                        <div className="h-tours-head">
                            <div>
                                <div className="rp-eyebrow" data-ui="home.tours.eyebrow">Chọn hành trình của bạn</div>
                                <h2 className="rp-h2">
                                    <span data-ui="home.tours.title">Hành trình</span> <em data-ui="home.tours.accent">nổi bật</em>
                                </h2>
                            </div>
                            <Link href={routes.tours.index} className="rp-link-arrow">
                                Tất cả tour <ArrowRight size={15} />
                            </Link>
                        </div>

                        <Reveal>
                            <div className="h-tour-grid">
                                {tours.map((tour) => (
                                    <TourCard key={tour.id} tour={tour} />
                                ))}
                            </div>
                        </Reveal>

                        <div className="h-tours-foot">
                            <Link href={ve('home.tours.button').url || routes.tours.index} className="rp-btn rp-btn--outline" data-ui="home.tours.button">
                                <span data-ui="home.tours.button.text">Xem tất cả hành trình</span> <ArrowRight size={15} />
                            </Link>
                        </div>
                    </div>
                </section>
            )}

            {/* 3. Banner ảnh thiên nhiên */}
            <section className="h-band" data-ui="home.band.section" style={{ display: sectionOn('band') ? undefined : 'none', order: orderOf('band') }}>
                {bandImage && <img className="h-band__image" data-ui="home.band.image" src={bandImage} alt="" decoding="async" />}

                <div className="rp-container">
                    <div className="h-band__inner">
                        <div className="rp-eyebrow" data-ui="home.band.eyebrow">Triết lý hành trình</div>
                        <blockquote className="h-band__quote" data-ui="home.band.quote">
                            “Chúng tôi không đưa bạn đi thật nhanh. Chúng tôi đưa bạn đi thật sâu.”
                        </blockquote>
                        <span className="h-band__source" data-ui="home.band.source">Rừng Adventure</span>
                    </div>
                </div>
            </section>

            {/* Section bổ sung (quote band) — thêm từ Visual Editor */}
            {customSections.filter((item) => item.enabled !== false).map((item) => (
                <section className="h-band" key={item.id} data-ui="home.custom" data-custom-id={item.id} style={{ order: orderOf(`custom:${item.id}`) }}>
                    {item.image && <img className="h-band__image" src={item.image} alt="" decoding="async" />}
                    <div className="rp-container">
                        <div className="h-band__inner">
                            {item.eyebrow && <div className="rp-eyebrow">{item.eyebrow}</div>}
                            <blockquote className="h-band__quote">{item.quote}</blockquote>
                            {item.source && <span className="h-band__source">{item.source}</span>}
                        </div>
                    </div>
                </section>
            ))}

            {/* 4. Thư viện — cấu hình từ Admin */}
            {gallery.enabled !== false && (
                <section className={`rp-section${gallery.background === 'white' ? '' : ' rp-section--cream'}`} data-ui="home.gallery.section" style={{ display: sectionOn('gallery') ? undefined : 'none', order: orderOf('gallery') }}>
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 34 }}>
                            <div>
                                {gallery.label_on !== false && (
                                    <div className="rp-eyebrow" style={galleryOverride ? { color: gallery.label_color } : undefined}>
                                        {gallery.label_text || 'Thư viện'}
                                    </div>
                                )}
                                <h2 className="rp-h2" style={galleryOverride ? { color: gallery.title_color } : undefined}>
                                    {gallery.title || 'Những khoảnh khắc'}{' '}
                                    <em style={galleryOverride ? { color: gallery.accent_color } : undefined}>{gallery.accent ?? 'đường rừng'}</em>
                                </h2>
                                {gallery.lead && (
                                    <p className="rp-lead" style={{ color: gallery.lead_color || undefined }}>{gallery.lead}</p>
                                )}
                            </div>

                            {gallery.button_on !== false && (
                                <Link
                                    href={ve('home.gallery.button').url || gallery.button_url || routes.posts.index}
                                    className="rp-btn rp-btn--outline"
                                    data-ui="home.gallery.button"
                                    style={galleryOverride ? {
                                        color: gallery.button_color,
                                        background: gallery.button_bg,
                                        borderColor: gallery.button_color,
                                    } : undefined}
                                >
                                    <span data-ui="home.gallery.button.text">{gallery.button_text || 'Đọc cẩm nang'}</span> <ArrowRight size={15} />
                                </Link>
                            )}
                        </div>

                        <Reveal>
                            <MediaCarousel images={galleryImages} />
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 5. Cẩm nang — White */}
            {posts.length > 0 && (
                <section className="rp-section" data-ui="home.posts.section" style={{ display: sectionOn('posts') ? undefined : 'none', order: orderOf('posts') }}>
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 36 }}>
                            <div>
                                <div className="rp-eyebrow" data-ui="home.posts.eyebrow">Cẩm nang</div>
                                <h2 className="rp-h2">
                                    <span data-ui="home.posts.title">Ghi chép</span> <em data-ui="home.posts.accent">từ rừng</em>
                                </h2>
                            </div>
                            <Link href={ve('home.posts.button').url || routes.posts.index} className="rp-link-arrow" data-ui="home.posts.button">
                                <span data-ui="home.posts.button.text">Tất cả bài viết</span> <ArrowRight size={15} />
                            </Link>
                        </div>

                        <Reveal>
                            <div className="h-journal">
                                <Link href={routes.posts.show(posts[0].slug)} className="h-journal__feature">
                                    <div className="h-journal__meta">
                                        {posts[0].category?.name && <span>{posts[0].category.name}</span>}
                                        {posts[0].published_at && <span>{date(posts[0].published_at)}</span>}
                                    </div>

                                    <h3>{posts[0].title}</h3>
                                    {posts[0].excerpt && <p>{posts[0].excerpt}</p>}

                                    <span className="rp-link-arrow">
                                        Đọc bài viết <ArrowRight size={14} />
                                    </span>
                                </Link>

                                {posts.length > 1 && (
                                    <div className="h-journal__list">
                                        {posts.slice(1, 4).map((post) => (
                                            <Link key={post.id} href={routes.posts.show(post.slug)} className="h-journal__item">
                                                <div>
                                                    <small>
                                                        {[post.category?.name, post.published_at ? date(post.published_at) : null]
                                                            .filter(Boolean)
                                                            .join(' · ')}
                                                    </small>
                                                    <h4>{post.title}</h4>
                                                </div>
                                                <ArrowUpRight size={17} className="h-journal__arrow" />
                                            </Link>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 6. CTA — ảnh thiên nhiên */}
            <section className="h-cta" data-ui="home.cta.section" style={{ display: sectionOn('cta') ? undefined : 'none', order: orderOf('cta') }}>
                {ctaImage && <img className="h-cta__image" data-ui="home.cta.image" src={ctaImage} alt="" decoding="async" />}

                <div className="rp-container">
                    <Reveal>
                        <div className="h-cta__inner">
                            <div>
                                <div className="rp-eyebrow" data-ui="home.cta.eyebrow">Bắt đầu từ đây</div>
                                <h2 className="h-cta__title">
                                    Sẵn sàng cho
                                    <br />
                                    <em data-ui="home.cta.accent">hành trình tiếp theo?</em>
                                </h2>
                                <p className="h-cta__lead" data-ui="home.cta.lead">
                                    Cho chúng tôi biết bạn muốn đi đâu, bao lâu và cùng ai — đội ngũ Rừng Adventure sẽ thiết kế
                                    lịch trình phù hợp với bạn.
                                </p>
                            </div>

                            <div className="h-cta__side">
                                <div className="h-cta__contact">
                                    {settings?.contact_phone && (
                                        <a href={`tel:${settings.contact_phone}`}>
                                            <Phone size={15} style={{ verticalAlign: '-2px', marginRight: 7 }} />
                                            {settings.contact_phone}
                                        </a>
                                    )}
                                    {settings?.contact_email && (
                                        <a href={`mailto:${settings.contact_email}`}>
                                            <Mail size={15} style={{ verticalAlign: '-2px', marginRight: 7 }} />
                                            {settings.contact_email}
                                        </a>
                                    )}
                                </div>

                                <Link href={ve('home.cta.button').url || routes.contact} className="rp-btn rp-btn--gold" data-ui="home.cta.button">
                                    <span data-ui="home.cta.button.text">Liên hệ tư vấn</span> <ArrowRight size={16} />
                                </Link>

                                <Link href={routes.tours.index} className="rp-btn rp-btn--ghost-light">
                                    Xem tất cả tour
                                </Link>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>
        </AppLayout>
    );
}
