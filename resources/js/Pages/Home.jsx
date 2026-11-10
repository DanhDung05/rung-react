import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, ArrowUpRight, Phone, Mail } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import Reveal from '../Components/Public/Reveal';
import HeroSlider from '../Components/Public/HeroSlider';
import TourCard from '../Components/Public/TourCard';
import Gallery from '../Components/Public/Gallery';
import { routes } from '../lib/routes';
import { mediaUrl, money, date } from '../lib/format';
import { placeholderImage } from '../lib/placeholder';
import { DIFFICULTY } from '../lib/constants';

const VALUES = [
    ['01', 'Trải nghiệm bản địa', 'Gặp gỡ con người, văn hoá và nhịp sống thật của mỗi vùng đất.'],
    ['02', 'An toàn là ưu tiên', 'Lịch trình chuẩn bị kỹ, đồng hành cùng hướng dẫn viên giàu kinh nghiệm.'],
    ['03', 'Tôn trọng tự nhiên', 'Du lịch có trách nhiệm, giữ nguyên vẻ đẹp vốn có của rừng và núi.'],
    ['04', 'Kết nối cộng đồng', 'Mỗi chuyến đi là cơ hội gắn kết người đi và người ở lại.'],
];

function SafeImage({ src, alt = '', className = '' }) {
    const [failed, setFailed] = useState(false);

    if (!src || failed) return null;

    return <img src={src} alt={alt} className={className} decoding="async" onError={() => setFailed(true)} />;
}

function AboutMedia({ src, alt, caption }) {
    const [failed, setFailed] = useState(false);

    if (!src || failed) return null;

    return (
        <figure className="h-about__media">
            <img src={src} alt={alt} decoding="async" onError={() => setFailed(true)} />
            {caption && <figcaption>{caption}</figcaption>}
        </figure>
    );
}

const durationText = (tour) => {
    const parts = [];
    if (tour.duration_days) parts.push(`${tour.duration_days} ngày`);
    if (tour.duration_nights) parts.push(`${tour.duration_nights} đêm`);
    return parts.join(' · ');
};

export default function Home({ sliders = [], featuredTours = [], featuredDestinations = [], latestPosts = [], settings = {} }) {
    const tours = Array.isArray(featuredTours) ? featuredTours : featuredTours?.data || [];
    const destinations = Array.isArray(featuredDestinations) ? featuredDestinations : featuredDestinations?.data || [];
    const posts = Array.isArray(latestPosts) ? latestPosts : latestPosts?.data || [];
    const about = settings?.about || {};

    const heroFallback = mediaUrl(tours.find((item) => item.featured_image)?.featured_image) || mediaUrl(sliders[0]?.image) || null;
    const ctaImage = settings?.hero_images?.contact || mediaUrl(sliders[0]?.image) || null;

    // Chỉ dùng ảnh thật: slider + ảnh hero từ settings + tour có ảnh; loại trùng.
    const galleryImages = [
        ...sliders.map((item) => ({ id: `s-${item.id}`, url: mediaUrl(item.image), alt: item.title || '' })),
        ...Object.entries(settings?.hero_images || {}).map(([key, url]) => ({
            id: `h-${key}`,
            url,
            alt: settings?.site_name || 'Rừng Adventure',
        })),
    ]
        .filter((item) => item.url)
        .filter((item, index, list) => list.findIndex((candidate) => candidate.url === item.url) === index);

    const galleryFinal = [...galleryImages];
    while (galleryFinal.length < 5) {
        galleryFinal.push({
            id: `ph-${galleryFinal.length}`,
            url: placeholderImage(`gallery-${galleryFinal.length}`),
            alt: 'Rừng Adventure',
        });
    }

    const feature = tours[0] || null;
    const tourRows = tours.slice(1, 5);

    const journeyTypes = [
        ...new Map(tours.filter((item) => item.category).map((item) => [item.category.id, item.category])).values(),
    ];

    const bandImage = mediaUrl(sliders[0]?.image) || null;

    const tourGroups = [
        { label: 'Hành trình nhiều ngày', items: tours.filter((tour) => Number(tour.duration_days || 1) >= 2).slice(0, 3) },
        { label: 'Tour trong ngày', items: tours.filter((tour) => Number(tour.duration_days || 1) < 2) },
    ].filter((group) => group.items.length > 0);

    return (
        <AppLayout>
            <Seo description={settings?.site_description} />

            <HeroSlider
                sliders={sliders}
                fallbackImage={heroFallback}
                fallbackTitle={about.hero_title}
                fallbackLead={about.hero_subtitle || settings?.site_description}
            />

            {/* 0. Welcome — giới thiệu thương hiệu trung tâm + lối tắt chính sách */}
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

            {/* 1. Giới thiệu */}
            <section className="rp-section">
                <div className="rp-container">
                    <Reveal>
                        <div className="h-about">
                            <div>
                                <div className="rp-eyebrow">Về Rừng Adventure</div>
                                <h2 className="h-about__title">
                                    {about.story_title || 'Không chỉ là chuyến đi.'}
                                    <em>Là một cách sống.</em>
                                </h2>
                                <p style={{ fontSize: '1.02rem', lineHeight: 1.85, color: '#45544c', margin: 0 }}>
                                    {settings?.site_description ||
                                        'Những hành trình trekking và khám phá thiên nhiên Việt Nam, được thiết kế cho người muốn đi chậm và hiểu sâu.'}
                                </p>

                                <blockquote className="h-about__quote" style={{ marginTop: 30 }}>
                                    “Đi để trở về biết ơn — với núi rừng, với con người và với chính mình.”
                                </blockquote>

                                <div className="rp-about__sign" style={{ marginBottom: 24 }}>
                                    <span>Nhóm Rừng Adventure</span>
                                </div>

                                <Link href={routes.about} className="rp-link-arrow">
                                    Đọc câu chuyện đầy đủ <ArrowRight size={15} />
                                </Link>
                            </div>

                            <div>
                                <div className="h-about__text">
                                    <p>
                                        {about.mission_content ||
                                            'Chúng tôi tin rằng một chuyến đi tốt không nằm ở số điểm đến, mà ở chiều sâu của trải nghiệm: buổi sáng trong bản làng, bữa cơm cùng người địa phương, và những cung đường không có trên bản đồ.'}
                                    </p>
                                    {about.vision_content && <p>{about.vision_content}</p>}
                                </div>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>

            {/* 2. Lựa chọn hành trình — nhóm tour thật từ dữ liệu */}
            {journeyTypes.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 30 }}>
                            <div>
                                <div className="rp-eyebrow">Lựa chọn hành trình</div>
                                <h2 className="rp-h2">
                                    Bạn muốn đi <em>theo cách nào?</em>
                                </h2>
                            </div>
                            <Link href={routes.tours.index} className="rp-link-arrow">
                                Xem tất cả <ArrowRight size={15} />
                            </Link>
                        </div>

                        <div className="h-journey">
                            {journeyTypes.map((category, index) => (
                                <Link
                                    key={category.id}
                                    href={`${routes.tours.index}?category=${category.slug}`}
                                    className="h-journey__row"
                                >
                                    <span className="h-journey__num">{String(index + 1).padStart(2, '0')}</span>
                                    <h3 className="h-journey__name">{category.name}</h3>
                                    <span className="h-journey__go">
                                        Khám phá <ArrowRight size={15} />
                                    </span>
                                </Link>
                            ))}
                        </div>
                    </div>
                </section>
            )}

                        {/* 3. Tour nổi bật — lưới card theo nhóm thời lượng */}
            {tourGroups.length > 0 && (
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

                        {tourGroups.map((group) => (
                            <div className="h-tour-group" key={group.label}>
                                <div className="h-tour-group__head">
                                    <h3>{group.label}</h3>
                                    <span>{group.items.length} hành trình</span>
                                </div>

                                <div className="h-tour-grid">
                                    {group.items.map((tour) => (
                                        <TourCard key={tour.id} tour={tour} />
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </section>
            )}
            {/* 4. Điểm đến */}
            {destinations.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="h-dest__head">
                            <div>
                                <div className="rp-eyebrow">Điểm đến</div>
                                <h2 className="rp-h2">
                                    Nơi rừng gọi <em>tên bạn</em>
                                </h2>
                            </div>
                            <Link href={routes.destinations.index} className="rp-link-arrow">
                                Khám phá điểm đến <ArrowRight size={15} />
                            </Link>
                        </div>

                        <Reveal>
                            <div className="h-dest__list">
                                {destinations.slice(0, 6).map((destination, index) => (
                                    <Link key={destination.id} href={routes.destinations.show(destination.slug)} className="h-dest__row">
                                        <span className="h-dest__num">{String(index + 1).padStart(2, '0')}</span>
                                        <h3 className="h-dest__name">{destination.name}</h3>
                                        <span className="h-dest__region">
                                            {[destination.province, destination.region].filter(Boolean).join(' · ')}
                                        </span>
                                        <span className="h-dest__count">
                                            {destination.tours_count !== undefined ? `${destination.tours_count} tour` : ''}
                                        </span>
                                        <ArrowUpRight size={18} className="h-dest__arrow" />
                                    </Link>
                                ))}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 5. Full-width quote band */}
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

            {/* 5. Giá trị */}
            <section className="rp-section" style={{ paddingTop: 0 }}>
                <div className="rp-container">
                    <Reveal>
                        <div className="h-values">
                            <div>
                                <div className="rp-eyebrow">Vì sao chọn chúng tôi</div>
                                <h2 className="h-about__title" style={{ fontSize: 'clamp(2rem, 3.4vw, 2.8rem)' }}>
                                    {about.values_title || 'Giá trị chúng tôi'}
                                    <em> theo đuổi</em>
                                </h2>
                                <p style={{ fontSize: '0.98rem', lineHeight: 1.8, maxWidth: '40ch', margin: 0, color: '#52625a' }}>
                                    {about.values_content ||
                                        'Bốn nguyên tắc định hình mọi hành trình: bản địa, an toàn, bền vững và kết nối.'}
                                </p>
                            </div>

                            <div className="h-values__list">
                                {VALUES.map(([num, title, desc]) => (
                                    <div className="h-value" key={num}>
                                        <span className="h-value__num">{num}</span>
                                        <h3>{title}</h3>
                                        <p>{desc}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>

            {/* 5. Thư viện */}
            <section className="rp-section">
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

                    {galleryImages.length > 0 ? (
                        <Reveal>
                            <Gallery images={galleryFinal.slice(0, 5)} layout="collage" />
                        </Reveal>
                    ) : (
                        <div className="h-gallery__empty">
                            Hình ảnh hành trình sẽ được cập nhật khi đội ngũ hoàn tất chuyến đi tiếp theo.
                        </div>
                    )}
                </div>
            </section>

            {/* 6. Bài viết */}
            {posts.length > 0 && (
                <section className="rp-section rp-section--cream">
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
                                        {posts.slice(1, 3).map((post) => (
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

            {/* 7. CTA */}
            <section className="h-cta">
                {ctaImage && <SafeImage src={ctaImage} alt="" className="h-cta__image" />}

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
