import React, { useMemo } from 'react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import { routes } from '../../lib/routes';
import { faqSchema } from '../../lib/schema';

export default function Faq({ faqs = [] }) {
    const groups = useMemo(() => {
        const map = new Map();

        faqs.forEach((faq) => {
            const key = faq.category || 'Câu hỏi thường gặp';
            if (!map.has(key)) map.set(key, []);
            map.get(key).push(faq);
        });

        return [...map.entries()];
    }, [faqs]);

    return (
        <AppLayout>
            <Seo
                title="Câu hỏi thường gặp — Rừng Adventure"
                description="Giải đáp về hành trình, an toàn, chuẩn bị và chính sách của Rừng Adventure."
                jsonLd={faqSchema(faqs)}
            />

            <PageHero
                eyebrow="Hỗ trợ"
                title="Câu hỏi thường gặp"
                lead="Những điều bạn cần biết trước khi bắt đầu hành trình."
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'FAQ' }]}
            />

            <section className="rp-section">
                <div className="rp-container rp-article">
                    {groups.length === 0 && <p className="rp-muted">Nội dung đang được cập nhật.</p>}

                    {groups.map(([category, items]) => (
                        <div className="rp-faq-group" key={category}>
                            <h2 className="rp-h3">{category}</h2>

                            {items.map((faq) => (
                                <details className="rp-faq" key={faq.id}>
                                    <summary>{faq.question}</summary>
                                    <div className="rp-prose" dangerouslySetInnerHTML={{ __html: faq.answer }} />
                                </details>
                            ))}
                        </div>
                    ))}
                </div>
            </section>
        </AppLayout>
    );
}
