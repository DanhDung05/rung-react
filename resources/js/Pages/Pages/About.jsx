import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';
import { mediaUrl } from '../../lib/format';
import { placeholderImage } from '../../lib/placeholder';

export default function About({ page }) {
    const image = mediaUrl(page?.featured_image) || placeholderImage('gioi-thieu');

    return (
        <AppLayout>
            <Seo
                title={page?.meta_title || 'Giới thiệu — Rừng Adventure'}
                description={page?.meta_description || 'Câu chuyện, sứ mệnh và con người của Rừng Adventure.'}
                image={image}
            />

            <PageHero
                eyebrow="Về chúng tôi"
                title={page?.title || 'Câu chuyện Rừng Adventure'}
                lead="Hành trình bắt đầu từ những cung đường nhỏ và những người muốn đi chậm."
                image={image}
                size="tall"
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Giới thiệu' }]}
            />

            <section className="rp-section">
                <div className="rp-container rp-article">
                    {page?.content ? (
                        <div className="rp-prose" dangerouslySetInnerHTML={{ __html: page.content }} />
                    ) : (
                        <div className="rp-prose">
                            <p>Nội dung trang giới thiệu đang được cập nhật.</p>
                        </div>
                    )}

                    <div className="rp-article__foot">
                        <Link href={routes.tours.index} className="rp-btn rp-btn--gold">
                            Xem các hành trình <ArrowRight size={15} />
                        </Link>
                    </div>
                </div>
            </section>

            <section className="rp-section rp-section--cream">
                <div className="rp-container rp-spread">
                    <div>
                        <div className="rp-eyebrow">Đồng hành cùng chúng tôi</div>
                        <h2 className="rp-h2">Bạn đã sẵn sàng cho chuyến đi tiếp theo?</h2>
                    </div>
                    <Link href={routes.contact} className="rp-btn rp-btn--gold">
                        Liên hệ tư vấn <ArrowRight size={15} />
                    </Link>
                </div>
            </section>
        </AppLayout>
    );
}
