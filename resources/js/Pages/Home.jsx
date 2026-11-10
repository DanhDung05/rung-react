import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, Compass, Leaf, ShieldCheck, Users } from 'lucide-react';
import AppLayout from '../Layouts/AppLayout';
import Seo from '../Components/Seo';
import { routes } from '../lib/routes';
import { mediaUrl, money } from '../lib/format';

function FeatureCard({ item, type }) {
    const name = item.name || item.title || 'Hành trình khám phá';
    const href =
        type === 'tour'
            ? routes.tours.show(item.slug)
            : type === 'destination'
              ? routes.destinations.show(item.slug)
              : routes.posts.show(item.slug);

    const image = mediaUrl(item.thumbnail || item.featured_image || item.image || item.cover_image);
    const label = type === 'tour' ? 'TOUR KHÁM PHÁ' : type === 'destination' ? 'ĐIỂM ĐẾN' : 'CẨM NANG';

    return (
        <Link href={href} className="feature-card">
            <div
                className="feature-image"
                style={
                    image
                        ? {
                              backgroundImage: `linear-gradient(0deg, rgba(8,25,19,.65), transparent 65%), url(${image})`,
                          }
                        : undefined
                }
            >
                <span>{label}</span>
            </div>
            <div className="feature-info">
                <h3>{name}</h3>
                <p>
                    {item.short_description ||
                        item.excerpt ||
                        item.description ||
                        'Khám phá vẻ đẹp thiên nhiên và trải nghiệm hành trình đáng nhớ.'}
                </p>
                <div className="feature-meta">
                    {type === 'tour' && <span>{money(item.price_reference)}</span>}
                    <span>
                        Khám phá <ArrowRight size={15} />
                    </span>
                </div>
            </div>
        </Link>
    );
}

export default function Home({ sliders = [], featuredTours = [], featuredDestinations = [], latestPosts = [] }) {
    const tours = featuredTours?.data || featuredTours || [];
    const destinations = featuredDestinations?.data || featuredDestinations || [];
    const posts = latestPosts?.data || latestPosts || [];

    const heroImage = mediaUrl(sliders?.[0]?.image);
    const heroTitle = sliders?.[0]?.title || 'Chạm vào thiên nhiên';
    const heroSubtitle =
        sliders?.[0]?.subtitle ||
        'Rời xa những lối mòn. Mở ra hành trình khám phá nguyên bản, kết nối với thiên nhiên và chính mình.';

    return (
        <AppLayout>
            <Seo
                description="Khám phá những hành trình trekking, điểm đến nguyên bản và cẩm nang du lịch cùng Rung Adventure."
            />

            <section
                className="home-hero"
                style={heroImage ? { backgroundImage: `linear-gradient(90deg, rgba(8,28,22,.88), rgba(8,28,22,.38)), url(${heroImage})` } : undefined}
            >
                <div className="hero-shade" />
                <div className="container home-hero-content">
                    <div className="eyebrow">GO BEYOND THE ORDINARY</div>
                    <h1>
                        Chạm vào
                        <br />
                        <em>thiên nhiên</em>
                    </h1>
                    <p>{heroSubtitle}</p>
                    <div className="hero-buttons">
                        <Link className="btn btn-gold" href={routes.tours.index}>
                            Khám phá hành trình <ArrowRight size={17} />
                        </Link>
                        <Link className="text-link" href={routes.about}>
                            Câu chuyện của chúng tôi ↗
                        </Link>
                    </div>
                    <div className="hero-note">
                        <span>01 / 03</span>
                        <span>{heroTitle}</span>
                    </div>
                </div>
            </section>

            <section className="intro-strip">
                <div className="container intro-grid">
                    <div>
                        <div className="eyebrow dark">ABOUT RUNG ADVENTURE</div>
                        <h2>
                            Không chỉ là một chuyến đi.
                            <br />
                            <em>Là một cách sống.</em>
                        </h2>
                    </div>
                    <p>
                        Chúng tôi tạo nên những hành trình gần gũi với thiên nhiên, nơi mỗi bước chân đều mở ra một
                        câu chuyện và mỗi trải nghiệm đều trở thành ký ức đáng nhớ.
                    </p>
                </div>
            </section>

            {tours.length > 0 && (
                <section className="section-block">
                    <div className="container">
                        <div className="section-heading">
                            <div>
                                <div className="eyebrow dark">CHOOSE YOUR JOURNEY</div>
                                <h2>
                                    Hành trình <em>nổi bật</em>
                                </h2>
                            </div>
                            <Link href={routes.tours.index} className="outline-link">
                                Tất cả tour <ArrowRight size={16} />
                            </Link>
                        </div>
                        <div className="feature-grid">
                            {tours.slice(0, 6).map((tour) => (
                                <FeatureCard key={tour.id} item={tour} type="tour" />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {destinations.length > 0 && (
                <section className="destination-band">
                    <div className="container">
                        <div className="section-heading light">
                            <div>
                                <div className="eyebrow">FIND YOUR PLACE</div>
                                <h2>
                                    Điểm đến <em>ấn tượng</em>
                                </h2>
                            </div>
                            <Link href={routes.destinations.index} className="outline-link light-link">
                                Khám phá điểm đến <ArrowRight size={16} />
                            </Link>
                        </div>
                        <div className="feature-grid">
                            {destinations.slice(0, 6).map((destination) => (
                                <FeatureCard key={destination.id} item={destination} type="destination" />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {posts.length > 0 && (
                <section className="section-block">
                    <div className="container">
                        <div className="section-heading">
                            <div>
                                <div className="eyebrow dark">FIELD NOTES</div>
                                <h2>
                                    Câu chuyện <em>đường rừng</em>
                                </h2>
                            </div>
                            <Link href={routes.posts.index} className="outline-link">
                                Đọc tất cả <ArrowRight size={16} />
                            </Link>
                        </div>
                        <div className="feature-grid">
                            {posts.slice(0, 3).map((post) => (
                                <FeatureCard key={post.id} item={post} type="post" />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <section className="values-band">
                <div className="container values-grid">
                    <div>
                        <Compass />
                        <h3>Trải nghiệm bản địa</h3>
                        <p>Khám phá những góc nhìn chân thực và văn hóa địa phương.</p>
                    </div>
                    <div>
                        <ShieldCheck />
                        <h3>An toàn là ưu tiên</h3>
                        <p>Chuẩn bị kỹ lưỡng và đồng hành cùng đội ngũ giàu kinh nghiệm.</p>
                    </div>
                    <div>
                        <Leaf />
                        <h3>Tôn trọng tự nhiên</h3>
                        <p>Du lịch có trách nhiệm, gìn giữ vẻ đẹp của mỗi điểm đến.</p>
                    </div>
                    <div>
                        <Users />
                        <h3>Kết nối cộng đồng</h3>
                        <p>Cùng nhau tạo nên những kỷ niệm và tình bạn trên mọi cung đường.</p>
                    </div>
                </div>
            </section>

            <section className="contact-band">
                <div className="container contact-inner">
                    <div className="eyebrow">YOUR NEXT STORY STARTS HERE</div>
                    <h2>
                        Sẵn sàng cho
                        <br />
                        <em>hành trình tiếp theo?</em>
                    </h2>
                    <p>Để Rung Adventure đồng hành cùng bạn trên cung đường đáng nhớ.</p>
                    <Link href={routes.contact} className="btn btn-gold">
                        Kết nối với chúng tôi <ArrowRight size={16} />
                    </Link>
                </div>
            </section>
        </AppLayout>
    );
}
