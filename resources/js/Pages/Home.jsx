import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, ArrowUpRight, Phone, Mail } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import Reveal from '../Components/Public/Reveal';
import HeroSlider from '../Components/Public/HeroSlider';
import TourCard from '../Components/Public/TourCard';
import Gallery from '../Components/Public/Gallery';
import { routes } from '../lib/routes';
import { mediaUrl, date } from '../lib/format';
import { placeholderImage } from '../lib/placeholder';

export default function Home({ sliders = [], featuredTours = [], latestPosts = [], settings = {} }) {
    const tours = Array.isArray(featuredTours) ? featuredTours : featuredTours?.data || [];
    const posts = Array.isArray(latestPosts) ? latestPosts : latestPosts?.data || [];
    const about = settings?.about || {};

    const heroImage = mediaUrl(sliders[0]?.image) || null;
    const bandImage = heroImage;
    const ctaImage = settings?.hero_images?.contact || heroImage;

    const galleryImages = [
        ...sliders.map((item) => ({ id: `s-${item.id}`, url: mediaUrl(item.image), alt: item.title || '' })),
        ...tours
            .filter((item) => item.featured_image)
            .map((item) => ({ id: `t-${item.id}`, url: mediaUrl(item.featured_image), alt: item.name })),
    ]
        .filter((item) => item.url)
        .filter((item, index, list) => list.findIndex((candidate) => candidate.url === item.url) === index);

    while (galleryImages.length < 5) {
        galleryImages.push({
            id: `ph-${galleryImages.length}`,
            url: placeholderImage(`gallery-${galleryImages.length}`),
            alt: 'Rừng Adventure',
        });
    }

    return (
        <AppLayout>
            <Seo description={settings?.site_description} />

            <HeroSlider
                sliders={sliders}
                fallbackImage={heroImage}
                fallbackTitle={about.hero_title}
                fallbackLead={about.hero_subtitle || settings?.site_description}
            />

            {/* 1. Welcome — Warm Cream */}
            <section className="rp-section rp-section--cream">
                <div className="rp-container">
                    <Reveal>
                        <div className="h-welcome">
                            <div className="rp-eyebrow">Rừng Adventure · Việt Nam</div>
                            <h2 className="h-welcome__title">
                                Chào mừng đến <em>Rừng Adventure</em>
                            </h2>
                            <p className="h-welcome__lead">
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
                <section className="rp-section">
                    <div className="rp-container">
                        <div className="h-tours-head">
                            <div>
                                <div className="rp-eyebrow">Chọn hành trình của bạn</div>
                                <h2 className="rp-h2">
                                    Hành trình <em>nổi bật</em>
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
                            <Link href={routes.tours.index} className="rp-btn rp-btn--outline">
                                Xem tất cả hành trình <ArrowRight size={15} />
                            </Link>
                        </div>
                    </div>
                </section>
            )}

            {/* 3. Banner ảnh thiên nhiên */}
            <section className="h-band">
                {bandImage && <img className="h-band__image" src={bandImage} alt="" decoding="async" />}

                <div className="rp-container">
                    <div className="h-band__inner">
                        <div className="rp-eyebrow">Triết lý hành trình</div>
                        <blockquote className="h-band__quote">
                            “Chúng tôi không đưa bạn đi thật nhanh. Chúng tôi đưa bạn đi thật sâu.”
                        </blockquote>
                        <span className="h-band__source">Rừng Adventure</span>
                    </div>
                </div>
            </section>

            {/* 4. Thư viện — Warm Cream */}
            <section className="rp-section rp-section--cream">
                <div className="rp-container">
                    <div className="rp-spread" style={{ marginBottom: 34 }}>
                        <div>
                            <div className="rp-eyebrow">Thư viện</div>
                            <h2 className="rp-h2">
                                Những khoảnh khắc <em>đường rừng</em>
                            </h2>
                        </div>
                        <Link href={routes.posts.index} className="rp-link-arrow">
                            Đọc cẩm nang <ArrowRight size={15} />
                        </Link>
                    </div>

                    <Reveal>
                        <Gallery images={galleryImages.slice(0, 5)} layout="collage" />
                    </Reveal>
                </div>
            </section>

            {/* 5. Cẩm nang — White */}
            {posts.length > 0 && (
                <section className="rp-section">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 36 }}>
                            <div>
                                <div className="rp-eyebrow">Cẩm nang</div>
                                <h2 className="rp-h2">
                                    Ghi chép <em>từ rừng</em>
                                </h2>
                            </div>
                            <Link href={routes.posts.index} className="rp-link-arrow">
                                Tất cả bài viết <ArrowRight size={15} />
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
            <section className="h-cta">
                {ctaImage && <img className="h-cta__image" src={ctaImage} alt="" decoding="async" />}

                <div className="rp-container">
                    <Reveal>
                        <div className="h-cta__inner">
                            <div>
                                <div className="rp-eyebrow">Bắt đầu từ đây</div>
                                <h2 className="h-cta__title">
                                    Sẵn sàng cho
                                    <br />
                                    <em>hành trình tiếp theo?</em>
                                </h2>
                                <p className="h-cta__lead">
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

                                <Link href={routes.contact} className="rp-btn rp-btn--gold">
                                    Liên hệ tư vấn <ArrowRight size={16} />
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
