import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, Compass, Leaf, ShieldCheck, Users } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import SectionHeading from '../Components/Public/SectionHeading';
import Reveal from '../Components/Public/Reveal';
import HeroSlider from '../Components/Public/HeroSlider';
import TourCard from '../Components/Public/TourCard';
import DestinationCard from '../Components/Public/DestinationCard';
import PostCard from '../Components/Public/PostCard';
import Gallery from '../Components/Public/Gallery';
import { routes } from '../lib/routes';
import { mediaUrl } from '../lib/format';

const VALUES = [
    ['01', 'Trải nghiệm bản địa', 'Gặp gỡ con người, văn hoá và nhịp sống thật của mỗi vùng đất.', Compass],
    ['02', 'An toàn là ưu tiên', 'Lịch trình được chuẩn bị kỹ, đồng hành cùng hướng dẫn viên giàu kinh nghiệm.', ShieldCheck],
    ['03', 'Tôn trọng tự nhiên', 'Du lịch có trách nhiệm, giữ nguyên vẻ đẹp vốn có của rừng và núi.', Leaf],
    ['04', 'Kết nối cộng đồng', 'Mỗi chuyến đi là một cơ hội gắn kết người đi và người ở lại.', Users],
];

export default function Home({ sliders = [], featuredTours = [], featuredDestinations = [], latestPosts = [], settings = {} }) {
    const tours = Array.isArray(featuredTours) ? featuredTours : featuredTours?.data || [];
    const destinations = Array.isArray(featuredDestinations) ? featuredDestinations : featuredDestinations?.data || [];
    const posts = Array.isArray(latestPosts) ? latestPosts : latestPosts?.data || [];
    const about = settings?.about || {};

    const heroFallback =
        settings?.hero_images?.tour ||
        mediaUrl(tours[0]?.featured_image) ||
        mediaUrl(destinations[0]?.image) ||
        null;

    const galleryImages = [
        ...sliders.map((item) => ({ id: `s-${item.id}`, url: mediaUrl(item.image), alt: item.title || '' })),
        ...tours.map((item) => ({ id: `t-${item.id}`, url: mediaUrl(item.featured_image), alt: item.name })),
        ...destinations.map((item) => ({ id: `d-${item.id}`, url: mediaUrl(item.image), alt: item.name })),
        ...posts.map((item) => ({ id: `p-${item.id}`, url: mediaUrl(item.featured_image), alt: item.title })),
    ]
        .filter((item) => item.url)
        .slice(0, 7);

    const introBody =
        about.mission_content ||
        settings?.site_description ||
        'Chúng tôi tổ chức những hành trình trekking và khám phá thiên nhiên Việt Nam theo cách bền vững nhất: nhóm nhỏ, hướng dẫn viên địa phương và lịch trình được thiết kế riêng.';

    return (
        <AppLayout>
            <Seo description={settings?.site_description} />

            <HeroSlider
                sliders={sliders}
                fallbackImage={heroFallback}
                fallbackTitle={about.hero_title}
                fallbackLead={about.hero_subtitle || settings?.site_description}
            />

            {/* Giới thiệu thương hiệu — bố cục editorial bất đối xứng */}
            <section className="rp-section">
                <div className="rp-container rp-intro">
                    <Reveal>
                        <div className="rp-eyebrow">Về Rừng Adventure</div>
                        <h2 className="rp-intro__title">
                            {about.story_title ? (
                                about.story_title
                            ) : (
                                <>
                                    Không chỉ là chuyến đi.
                                    <br />
                                    <em>Là một cách sống.</em>
                                </>
                            )}
                        </h2>
                        <div className="rp-intro__rule" />
                        <Link href={routes.about} className="rp-link-arrow">
                            Câu chuyện của chúng tôi <ArrowRight size={15} />
                        </Link>
                    </Reveal>

                    <Reveal delay={120} className="rp-intro__body">
                        <p>{introBody}</p>
                        {about.vision_content && <p>{about.vision_content}</p>}
                        <p>
                            Mỗi hành trình bắt đầu từ một cung đường nhỏ, một bản làng trên núi, một buổi sáng sương phủ —
                            và kết thúc bằng những người bạn mới.
                        </p>
                    </Reveal>
                </div>
            </section>

            {/* Tour nổi bật */}
            {tours.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <SectionHeading
                            eyebrow="Hành trình nổi bật"
                            title={<>Chọn cung đường <em>của bạn</em></>}
                            link={{ href: routes.tours.index, label: 'Tất cả tour' }}
                        />

                        <div className="rp-feature-layout">
                            <Reveal>
                                <TourCard tour={tours[0]} tall />
                            </Reveal>

                            {tours.length > 1 && (
                                <div className="rp-feature-aside">
                                    {tours.slice(1, 5).map((tour, index) => (
                                        <Reveal key={tour.id} delay={80 + index * 70}>
                                            <TourCard tour={tour} />
                                        </Reveal>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </section>
            )}

            {/* Điểm đến nổi bật */}
            {destinations.length > 0 && (
                <section className="rp-section rp-section--forest rp-destinations">
                    <div className="rp-container">
                        <SectionHeading
                            eyebrow="Điểm đến"
                            title={<>Nơi rừng gọi <em>tên bạn</em></>}
                            link={{ href: routes.destinations.index, label: 'Khám phá điểm đến' }}
                        />

                        <div className="rp-grid">
                            {destinations.slice(0, 3).map((destination, index) => (
                                <Reveal key={destination.id} delay={index * 90}>
                                    <DestinationCard destination={destination} />
                                </Reveal>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Giá trị thương hiệu */}
            <section className="rp-section rp-section--forest" style={{ paddingTop: 0 }}>
                <div className="rp-container">
                    <div className="rp-spread" style={{ marginBottom: 18, alignItems: 'flex-start' }}>
                        <div>
                            <div className="rp-eyebrow">Vì sao chọn chúng tôi</div>
                            <h2 className="rp-h2">{about.values_title || 'Giá trị chúng tôi theo đuổi'}</h2>
                        </div>
                        {about.values_content && (
                            <p className="rp-lead" style={{ maxWidth: '42ch' }}>
                                {about.values_content}
                            </p>
                        )}
                    </div>

                    <div className="rp-values">
                        {VALUES.map(([num, title, desc, Icon], index) => (
                            <Reveal key={num} delay={index * 70}>
                                <div className="rp-value">
                                    <span className="rp-value__num">{num}</span>
                                    <div>
                                        <h3>{title}</h3>
                                        <p>{desc}</p>
                                    </div>
                                </div>
                            </Reveal>
                        ))}
                    </div>
                </div>
            </section>

            {/* Gallery */}
            {galleryImages.length > 0 && (
                <section className="rp-section">
                    <div className="rp-container">
                        <SectionHeading
                            eyebrow="Thư viện"
                            title={<>Những khoảnh khắc <em>đường rừng</em></>}
                            link={{ href: routes.posts.index, label: 'Đọc cẩm nang' }}
                        />
                        <Gallery images={galleryImages} layout="strip" />
                    </div>
                </section>
            )}

            {/* Bài viết mới */}
            {posts.length > 0 && (
                <section className="rp-section rp-section--cream">
                    <div className="rp-container">
                        <SectionHeading
                            eyebrow="Cẩm nang"
                            title={<>Ghi chép <em>từ rừng</em></>}
                            link={{ href: routes.posts.index, label: 'Tất cả bài viết' }}
                        />

                        <div className="rp-post-grid">
                            {posts.slice(0, 3).map((post, index) => (
                                <Reveal key={post.id} delay={index * 90}>
                                    <PostCard post={post} />
                                </Reveal>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* CTA */}
            <section className="rp-section">
                <div className="rp-container">
                    <Reveal>
                        <div className="rp-cta rp-cta--wide">
                            <div>
                                <div className="rp-eyebrow">Bắt đầu từ đây</div>
                                <h2 className="rp-h2">Sẵn sàng cho hành trình tiếp theo?</h2>
                                <p className="rp-lead">
                                    Cho chúng tôi biết bạn muốn đi đâu, bao lâu và cùng ai — đội ngũ Rừng Adventure sẽ thiết kế
                                    lịch trình phù hợp.
                                </p>
                            </div>
                            <div className="rp-cta__actions">
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
