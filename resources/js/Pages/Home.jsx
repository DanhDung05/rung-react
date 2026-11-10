import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, ArrowUpRight, Phone, Mail } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import Reveal from '../Components/Public/Reveal';
import HeroSlider from '../Components/Public/HeroSlider';
import Gallery from '../Components/Public/Gallery';
import { routes } from '../lib/routes';
import { mediaUrl, money, date } from '../lib/format';
import { DIFFICULTY } from '../lib/constants';

const VALUES = [
    ['01', 'Trải nghiệm bản địa', 'Gặp gỡ con người, văn hoá và nhịp sống thật của mỗi vùng đất.'],
    ['02', 'An toàn là ưu tiên', 'Lịch trình chuẩn bị kỹ, đồng hành cùng hướng dẫn viên giàu kinh nghiệm.'],
    ['03', 'Tôn trọng tự nhiên', 'Du lịch có trách nhiệm, giữ nguyên vẻ đẹp vốn có của rừng và núi.'],
    ['04', 'Kết nối cộng đồng', 'Mỗi chuyến đi là cơ hội gắn kết người đi và người ở lại.'],
];

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

    const heroFallback =
        settings?.hero_images?.tour || mediaUrl(tours[0]?.featured_image) || mediaUrl(destinations[0]?.image) || null;

    const aboutImage =
        settings?.hero_images?.about || mediaUrl(destinations[0]?.image) || mediaUrl(tours[0]?.featured_image) || null;

    const ctaImage = settings?.hero_images?.contact || aboutImage;

    const galleryImages = [
        ...sliders.map((item) => ({ id: `s-${item.id}`, url: mediaUrl(item.image), alt: item.title || '' })),
        ...tours.map((item) => ({ id: `t-${item.id}`, url: mediaUrl(item.featured_image), alt: item.name })),
        ...destinations.map((item) => ({ id: `d-${item.id}`, url: mediaUrl(item.image), alt: item.name })),
        ...posts.map((item) => ({ id: `p-${item.id}`, url: mediaUrl(item.featured_image), alt: item.title })),
    ]
        .filter((item) => item.url)
        .slice(0, 7);

    return (
        <AppLayout>
            <Seo description={settings?.site_description} />

            <HeroSlider
                sliders={sliders}
                fallbackImage={heroFallback}
                fallbackTitle={about.hero_title}
                fallbackLead={about.hero_subtitle || settings?.site_description}
            />

            {/* 1. Giới thiệu thương hiệu — media + editorial copy */}
            <section className="rp-section">
                <div className="rp-container">
                    <Reveal>
                        <div className="rp-about__grid">
                            {aboutImage && (
                                <figure className="rp-about__media">
                                    <img src={aboutImage} alt={settings?.site_name || 'Rừng Adventure'} loading="lazy" decoding="async" />
                                    <figcaption className="rp-about__caption">
                                        {about.story_title || 'Những cung đường nhỏ, những ký ức dài.'}
                                    </figcaption>
                                </figure>
                            )}

                            <div className="rp-about__body" style={aboutImage ? undefined : { gridColumn: '1 / -1' }}>
                                <div className="rp-eyebrow">Về Rừng Adventure</div>

                                <h2 className="rp-about__title">
                                    {about.story_title || 'Không chỉ là chuyến đi.'}
                                    <em>Là một cách sống.</em>
                                </h2>

                                <p>
                                    {about.mission_content ||
                                        settings?.site_description ||
                                        'Chúng tôi tổ chức những hành trình trekking và khám phá thiên nhiên Việt Nam theo cách bền vững nhất.'}
                                </p>

                                {about.vision_content && <p>{about.vision_content}</p>}

                                <blockquote className="rp-about__quote">
                                    “Đi để trở về biết ơn — với núi rừng, với con người và với chính mình.”
                                </blockquote>

                                <div className="rp-about__sign">
                                    <span>Nhóm Rừng Adventure</span>
                                </div>

                                <div style={{ marginTop: 26 }}>
                                    <Link href={routes.about} className="rp-link-arrow">
                                        Đọc câu chuyện đầy đủ <ArrowRight size={15} />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>

            {/* 2. Tour nổi bật — feature lớn + danh sách editorial */}
            {tours.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 36 }}>
                            <div>
                                <div className="rp-eyebrow">Hành trình nổi bật</div>
                                <h2 className="rp-h2">
                                    Chọn cung đường <em>của bạn</em>
                                </h2>
                            </div>
                            <Link href={routes.tours.index} className="rp-link-arrow">
                                Tất cả tour <ArrowRight size={15} />
                            </Link>
                        </div>

                        <Reveal>
                            <div className="rp-tour-editorial">
                                <Link href={routes.tours.show(tours[0].slug)} className="rp-tour-feature">
                                    {mediaUrl(tours[0].featured_image) && (
                                        <img src={mediaUrl(tours[0].featured_image)} alt={tours[0].name} loading="lazy" decoding="async" />
                                    )}

                                    <div className="rp-tour-feature__body">
                                        <span className="rp-tour-feature__tag">
                                            {tours[0].category?.name || (tours[0].difficulty ? DIFFICULTY[tours[0].difficulty] : 'Tour nổi bật')}
                                        </span>
                                        <h3 className="rp-tour-feature__title">{tours[0].name}</h3>
                                        {tours[0].excerpt && <p className="rp-tour-feature__desc">{tours[0].excerpt}</p>}

                                        <div className="rp-tour-feature__foot">
                                            <span>
                                                {[durationText(tours[0]), tours[0].difficulty ? DIFFICULTY[tours[0].difficulty] : null]
                                                    .filter(Boolean)
                                                    .join(' · ')}
                                            </span>
                                            <span className="rp-tour-feature__price">{money(tours[0].price_reference)}</span>
                                        </div>
                                    </div>
                                </Link>

                                {tours.length > 1 && (
                                    <div className="rp-tour-list">
                                        {tours.slice(1, 5).map((tour, index) => (
                                            <Link key={tour.id} href={routes.tours.show(tour.slug)} className="rp-tour-row">
                                                <span className="rp-tour-row__index">{String(index + 2).padStart(2, '0')}</span>

                                                {mediaUrl(tour.featured_image) ? (
                                                    <img className="rp-tour-row__thumb" src={mediaUrl(tour.featured_image)} alt="" loading="lazy" />
                                                ) : (
                                                    <span className="rp-tour-row__thumb" />
                                                )}

                                                <div>
                                                    <span className="rp-tour-row__title">{tour.name}</span>
                                                    <span className="rp-tour-row__meta">
                                                        {[durationText(tour), tour.category?.name].filter(Boolean).join(' · ')}
                                                    </span>
                                                </div>

                                                <span className="rp-tour-row__price">{money(tour.price_reference)}</span>
                                            </Link>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 3. Điểm đến — mosaic bất đối xứng */}
            {destinations.length > 0 && (
                <section className="rp-section rp-section--forest">
                    <div className="rp-container">
                        <div className="rp-spread" style={{ marginBottom: 36 }}>
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
                            <div className="rp-dest-editorial">
                                <Link href={routes.destinations.show(destinations[0].slug)} className="rp-dest-main">
                                    {mediaUrl(destinations[0].image) && (
                                        <img src={mediaUrl(destinations[0].image)} alt={destinations[0].name} loading="lazy" decoding="async" />
                                    )}
                                    <div className="rp-dest-main__body">
                                        {destinations[0].region && <span className="rp-dest-main__region">{destinations[0].region}</span>}
                                        <h3 className="rp-dest-main__title">{destinations[0].name}</h3>
                                        <div className="rp-dest-main__meta">
                                            {destinations[0].province && <span>{destinations[0].province}</span>}
                                            {destinations[0].tours_count !== undefined && <span>{destinations[0].tours_count} tour</span>}
                                        </div>
                                    </div>
                                </Link>

                                {destinations.length > 1 && (
                                    <div className="rp-dest-side">
                                        {destinations.slice(1, 3).map((destination) => (
                                            <Link key={destination.id} href={routes.destinations.show(destination.slug)} className="rp-dest-card">
                                                {mediaUrl(destination.image) && (
                                                    <img src={mediaUrl(destination.image)} alt={destination.name} loading="lazy" decoding="async" />
                                                )}
                                                <div className="rp-dest-card__body">
                                                    <h3>{destination.name}</h3>
                                                    <span>
                                                        {[destination.province, destination.region].filter(Boolean).join(' · ')}
                                                    </span>
                                                </div>
                                            </Link>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 4. Giá trị thương hiệu — manifesto */}
            <section className="rp-section rp-section--forest" style={{ paddingTop: 0 }}>
                <div className="rp-container">
                    <Reveal>
                        <div className="rp-manifesto">
                            <div>
                                <div className="rp-eyebrow">Vì sao chọn chúng tôi</div>
                                <h2 className="rp-manifesto__title">
                                    {about.values_title || 'Giá trị chúng tôi'}
                                    <em> theo đuổi</em>
                                </h2>
                                <p className="rp-manifesto__lead">
                                    {about.values_content ||
                                        'Bốn nguyên tắc định hình mọi hành trình: bản địa, an toàn, bền vững và kết nối.'}
                                </p>
                            </div>

                            <div className="rp-values-row">
                                {VALUES.map(([num, title, desc]) => (
                                    <div className="rp-value-col" key={num}>
                                        <span className="rp-value-col__num">{num}</span>
                                        <h3>{title}</h3>
                                        <p>{desc}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </Reveal>
                </div>
            </section>

            {/* 5. Thư viện ảnh — collage */}
            {galleryImages.length > 0 && (
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

                        <Reveal>
                            <Gallery images={galleryImages} layout="collage" />
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 6. Bài viết mới — journal */}
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
                            <div className="rp-journal">
                                <Link href={routes.posts.show(posts[0].slug)} className="rp-journal__feature">
                                    {mediaUrl(posts[0].featured_image) && (
                                        <div className="rp-journal__media">
                                            <img src={mediaUrl(posts[0].featured_image)} alt={posts[0].title} loading="lazy" decoding="async" />
                                        </div>
                                    )}

                                    <div className="rp-journal__meta">
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
                                    <div className="rp-journal__list">
                                        {posts.slice(1, 3).map((post) => (
                                            <Link key={post.id} href={routes.posts.show(post.slug)} className="rp-journal__item">
                                                <div>
                                                    <small>
                                                        {[post.category?.name, post.published_at ? date(post.published_at) : null]
                                                            .filter(Boolean)
                                                            .join(' · ')}
                                                    </small>
                                                    <h4>{post.title}</h4>
                                                    {post.excerpt && <span>{post.excerpt}</span>}
                                                </div>
                                                <ArrowUpRight size={17} className="rp-journal__arrow" />
                                            </Link>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </Reveal>
                    </div>
                </section>
            )}

            {/* 7. CTA — full bleed */}
            <section className="rp-cta-band">
                {ctaImage && <img className="rp-cta-band__image" src={ctaImage} alt="" loading="lazy" decoding="async" />}

                <div className="rp-container">
                    <Reveal>
                        <div className="rp-cta-band__inner">
                            <div>
                                <div className="rp-eyebrow">Bắt đầu từ đây</div>
                                <h2 className="rp-cta-band__title">
                                    Sẵn sàng cho
                                    <br />
                                    <em>hành trình tiếp theo?</em>
                                </h2>
                                <p className="rp-cta-band__lead">
                                    Cho chúng tôi biết bạn muốn đi đâu, bao lâu và cùng ai — đội ngũ Rừng Adventure sẽ thiết kế
                                    lịch trình phù hợp với bạn.
                                </p>
                            </div>

                            <div className="rp-cta-band__side">
                                <div className="rp-cta-band__contact">
                                    {settings?.contact_phone && (
                                        <a href={`tel:${settings.contact_phone}`}>
                                            <Phone size={14} style={{ verticalAlign: '-2px', marginRight: 6 }} />
                                            {settings.contact_phone}
                                        </a>
                                    )}
                                    {settings?.contact_email && (
                                        <a href={`mailto:${settings.contact_email}`}>
                                            <Mail size={14} style={{ verticalAlign: '-2px', marginRight: 6 }} />
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
