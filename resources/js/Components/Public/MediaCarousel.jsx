import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { ChevronLeft, ChevronRight, Play, Pause, X } from 'lucide-react';

/**
 * Carousel 3D kiểu "The Window": coverflow, kéo/swipe, tự chạy,
 * timeline thumbnail và modal xem ảnh lớn.
 */
export default function MediaCarousel({ images = [], interval = 5000 }) {
    const list = useMemo(() => (images || []).filter((item) => item && item.url), [images]);
    const [index, setIndex] = useState(0);
    const [playing, setPlaying] = useState(true);
    const [preview, setPreview] = useState(null);
    const drag = useRef({ startX: 0, active: false });

    const go = useCallback(
        (direction) => {
            setIndex((current) => (current + direction + list.length) % list.length);
        },
        [list.length]
    );

    useEffect(() => {
        if (!playing || list.length < 2) return undefined;
        const timer = window.setInterval(() => go(1), interval);
        return () => window.clearInterval(timer);
    }, [playing, interval, go, list.length]);

    useEffect(() => {
        if (preview === null) return undefined;
        const onKey = (event) => {
            if (event.key === 'Escape') setPreview(null);
            if (event.key === 'ArrowLeft') setPreview((current) => (current - 1 + list.length) % list.length);
            if (event.key === 'ArrowRight') setPreview((current) => (current + 1) % list.length);
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [preview, list.length]);

    if (list.length === 0) return null;

    const active = list[index] || list[0];

    const onPointerDown = (event) => {
        drag.current = { startX: event.clientX, active: true };
    };

    const onPointerUp = (event) => {
        if (!drag.current.active) return;
        const delta = event.clientX - drag.current.startX;
        drag.current.active = false;
        if (Math.abs(delta) > 45) go(delta > 0 ? -1 : 1);
    };

    return (
        <div
            className="tw-carousel"
            onMouseEnter={() => setPlaying(false)}
            onMouseLeave={() => setPlaying(true)}
            aria-roledescription="carousel"
            aria-label="Thư viện ảnh"
        >
            <div
                className="tw-stage"
                onPointerDown={onPointerDown}
                onPointerUp={onPointerUp}
                onPointerCancel={() => (drag.current.active = false)}
            >
                {list.map((item, i) => {
                    const raw = (i - index + list.length) % list.length;
                    const offset = raw > list.length / 2 ? raw - list.length : raw;
                    const distance = Math.abs(offset);
                    if (distance > 3) return null;

                    return (
                        <button
                            type="button"
                            key={item.id || i}
                            className={`tw-card ${i === index ? 'is-active' : ''}`}
                            style={{
                                transform: `translate(-50%, -50%) translateX(${offset * 46}%) rotateY(${offset * -24}deg) scale(${1 - Math.min(distance * 0.13, 0.4)})`,
                                zIndex: 20 - distance,
                                opacity: 1,
                            }}
                            onClick={() => (i === index ? setPreview(index) : setIndex(i))}
                            aria-label={item.alt || `Ảnh ${i + 1}`}
                        >
                            <img src={item.url} alt={item.alt || ''} loading={distance < 2 ? 'eager' : 'lazy'} draggable="false" />
                        </button>
                    );
                })}

                <button type="button" className="tw-arrow tw-arrow--prev" onClick={() => go(-1)} aria-label="Ảnh trước">
                    <ChevronLeft size={22} />
                </button>
                <button type="button" className="tw-arrow tw-arrow--next" onClick={() => go(1)} aria-label="Ảnh sau">
                    <ChevronRight size={22} />
                </button>
            </div>

            <div className="tw-meta">
                <div className="tw-meta__text">
                    <span className="tw-meta__number">
                        {String(index + 1).padStart(2, '0')} / {String(list.length).padStart(2, '0')}
                    </span>
                    <strong>{active.alt || 'Rừng Adventure'}</strong>
                    {active.caption && <small>{active.caption}</small>}
                </div>

                <div className="tw-meta__controls">
                    <button type="button" className="tw-round" onClick={() => go(-1)} aria-label="Ảnh trước">
                        <ChevronLeft size={18} />
                    </button>
                    <button type="button" className="tw-round" onClick={() => go(1)} aria-label="Ảnh sau">
                        <ChevronRight size={18} />
                    </button>
                    <button type="button" className="tw-round" onClick={() => setPlaying((value) => !value)} aria-label={playing ? 'Tạm dừng' : 'Tự chạy'}>
                        {playing ? <Pause size={16} /> : <Play size={16} />}
                    </button>
                </div>
            </div>

            <div className="tw-timeline" aria-label="Chọn ảnh">
                {list.map((item, i) => (
                    <button
                        type="button"
                        key={item.id || i}
                        className={`tw-thumb ${i === index ? 'is-active' : ''}`}
                        onClick={() => setIndex(i)}
                        aria-label={item.alt || `Ảnh ${i + 1}`}
                    >
                        <img src={item.url} alt="" loading="lazy" draggable="false" />
                    </button>
                ))}
            </div>

            <p className="tw-hint">Kéo hoặc dùng mũi tên · Bấm ảnh để xem lớn</p>

            {preview !== null && (
                <div className="tw-modal" role="dialog" aria-modal="true" aria-label="Xem ảnh">
                    <button type="button" className="tw-modal__close" onClick={() => setPreview(null)} aria-label="Đóng">
                        <X size={22} />
                    </button>
                    <button type="button" className="tw-modal__nav tw-modal__nav--prev" onClick={() => setPreview((preview - 1 + list.length) % list.length)} aria-label="Ảnh trước">
                        <ChevronLeft size={26} />
                    </button>
                    <img className="tw-modal__image" src={list[preview].url} alt={list[preview].alt || ''} />
                    <button type="button" className="tw-modal__nav tw-modal__nav--next" onClick={() => setPreview((preview + 1) % list.length)} aria-label="Ảnh sau">
                        <ChevronRight size={26} />
                    </button>
                    <div className="tw-modal__caption">
                        <strong>{list[preview].alt}</strong>
                        {list[preview].caption && <small>{list[preview].caption}</small>}
                    </div>
                </div>
            )}
        </div>
    );
}
