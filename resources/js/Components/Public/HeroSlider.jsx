import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { Link } from '@inertiajs/react';
import { ArrowRight, ArrowLeft, ArrowUpRight } from 'lucide-react';
import { mediaUrl } from '../../lib/format';
import { routes } from '../../lib/routes';

const AUTOPLAY_MS = 7000;

/**
 * Hero slider dùng dữ liệu thật từ prop `sliders`.
 * Nếu không có slider, hiển thị hero tĩnh với ảnh fallback + copy thương hiệu.
 */
export default function HeroSlider({ sliders = [], fallbackImage = null, fallbackTitle = null, fallbackLead = null, overlay = true }) {
    const slides = useMemo(() => {
        const list = (sliders || []).filter(Boolean).map((slider) => ({
            id: slider.id,
            image: mediaUrl(slider.image),
            title: slider.title,
            subtitle: slider.subtitle,
            href: slider.link_url || routes.tours.index,
            target: slider.link_target || '_self',
        }));

        if (list.length > 0) return list;

        return [
            {
                id: 'fallback',
                image: fallbackImage,
                title: fallbackTitle || 'Chạm vào thiên nhiên',
                subtitle: fallbackLead || 'Những hành trình nguyên bản, những ký ức còn mãi.',
                href: routes.tours.index,
                target: '_self',
            },
        ];
    }, [sliders, fallbackImage, fallbackTitle, fallbackLead]);

    const [index, setIndex] = useState(0);
    const [paused, setPaused] = useState(false);
    const reduced = useRef(false);

    useEffect(() => {
        reduced.current = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches || false;
    }, []);

    const go = useCallback(
        (direction) => setIndex((current) => (current + direction + slides.length) % slides.length),
        [slides.length]
    );

    useEffect(() => {
        if (slides.length <= 1 || paused || reduced.current) return undefined;
        const timer = setInterval(() => setIndex((current) => (current + 1) % slides.length), AUTOPLAY_MS);
        return () => clearInterval(timer);
    }, [slides.length, paused]);

    const active = slides[index] || slides[0];

    return (
        <section
            className="rp-slider"
            aria-label="Hành trình nổi bật"
            onMouseEnter={() => setPaused(true)}
            onMouseLeave={() => setPaused(false)}
            onFocus={() => setPaused(true)}
            onBlur={() => setPaused(false)}
        >
            {slides.map((slide, i) => (
                <div key={slide.id} className={`rp-slider__slide ${i === index ? 'is-active' : ''}`} aria-hidden={i !== index}>
                    {slide.image && <img src={slide.image} alt="" fetchPriority={i === 0 ? 'high' : 'low'} decoding="async" />}
                    {overlay ? <div className="rp-slider__shade" /> : <div className="rp-slider__topshade" />}
                </div>
            ))}

            <div className="rp-container rp-slider__content">
                <div className="rp-slider__eyebrow">Rừng Adventure · Việt Nam</div>

                <h1 className="rp-slider__title">
                    {active.title || 'Chạm vào thiên nhiên'}
                </h1>

                {active.subtitle && <p className="rp-slider__lead">{active.subtitle}</p>}

                <div className="rp-slider__actions">
                    <Link href={active.href} target={active.target} className="rp-btn rp-btn--gold">
                        Khám phá hành trình <ArrowRight size={16} />
                    </Link>
                    <Link href={routes.about} className="rp-btn rp-btn--ghost-light">
                        Câu chuyện của chúng tôi <ArrowUpRight size={16} />
                    </Link>
                </div>
            </div>

            {slides.length > 1 && (
                <div className="rp-container rp-slider__ui">
                    <div className="rp-slider__dots" role="tablist" aria-label="Chọn slide">
                        {slides.map((slide, i) => (
                            <button
                                key={slide.id}
                                type="button"
                                role="tab"
                                aria-selected={i === index}
                                aria-label={`Slide ${i + 1}`}
                                className={`rp-slider__dot ${i === index ? 'is-active' : ''}`}
                                onClick={() => setIndex(i)}
                            />
                        ))}
                    </div>

                    <div className="rp-slider__arrows">
                        <button type="button" className="rp-slider__arrow" onClick={() => go(-1)} aria-label="Slide trước">
                            <ArrowLeft size={17} />
                        </button>
                        <button type="button" className="rp-slider__arrow" onClick={() => go(1)} aria-label="Slide sau">
                            <ArrowRight size={17} />
                        </button>
                    </div>
                </div>
            )}
        </section>
    );
}
