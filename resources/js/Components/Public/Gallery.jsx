import React, { useCallback, useEffect, useRef, useState } from 'react';

/**
 * Thư viện ảnh có lightbox (không cần thư viện ngoài).
 * Preload trước khi render để loại ảnh hỏng/MIME sai — không để khung trống.
 * images: [{ id, url, alt }]
 */
export default function Gallery({ images = [], columns = 3, layout = 'grid' }) {
    const [index, setIndex] = useState(null);
    const [ready, setReady] = useState(null);
    const listRef = useRef(images);
    const key = images.map((image) => image.url).join('|');

    listRef.current = images;

    useEffect(() => {
        let cancelled = false;
        const list = listRef.current;

        if (list.length === 0) {
            setReady([]);
            return undefined;
        }

        const results = [];
        let pending = list.length;

        const finish = () => {
            if (cancelled) return;
            pending -= 1;
            if (pending === 0) setReady(results.filter(Boolean));
        };

        list.forEach((image, i) => {
            const probe = new window.Image();
            let settled = false;

            const settle = (ok) => {
                if (settled) return;
                settled = true;
                if (ok) results[i] = image;
                finish();
            };

            probe.onload = () => settle(true);
            probe.onerror = () => settle(false);
            probe.src = image.url;

            // Ảnh không phản hồi (MIME sai) sẽ bị loại sau 6 giây.
            setTimeout(() => settle(false), 6000);
        });

        return () => {
            cancelled = true;
        };
    }, [key]);

    const visible = ready || [];
    const isOpen = index !== null;

    const close = useCallback(() => setIndex(null), []);
    const prev = useCallback(
        () => setIndex((current) => (current === null ? null : (current + visible.length - 1) % visible.length)),
        [visible.length]
    );
    const next = useCallback(
        () => setIndex((current) => (current === null ? null : (current + 1) % visible.length)),
        [visible.length]
    );

    useEffect(() => {
        if (!isOpen) return undefined;

        const onKey = (event) => {
            if (event.key === 'Escape') close();
            if (event.key === 'ArrowLeft') prev();
            if (event.key === 'ArrowRight') next();
        };

        document.addEventListener('keydown', onKey);
        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        return () => {
            document.removeEventListener('keydown', onKey);
            document.body.style.overflow = previousOverflow;
        };
    }, [isOpen, close, prev, next]);

    if (ready === null) {
        return <div className="h-gallery__empty">Đang tải hình ảnh…</div>;
    }

    if (visible.length === 0) return null;

    const strip = layout === 'strip';
    const collage = layout === 'collage';
    const duo = layout === 'duo';
    const collagePattern = ['is-large', 'is-tall', '', 'is-wide', '', ''];

    const itemClass = (i) => {
        if (duo) return `h-gallery__item ${i === 0 ? 'h-gallery__item--main' : 'h-gallery__item--side'}`;
        const extra = strip
            ? i % 6 === 0
                ? 'is-wide'
                : i % 6 === 3
                  ? 'is-tall'
                  : ''
            : collage
              ? collagePattern[i % 6]
              : '';
        return `rp-gallery__item ${extra}`.trim();
    };

    return (
        <>
            <div
                className={
                    strip
                        ? 'rp-gallery-strip'
                        : collage
                          ? 'rp-collage'
                          : duo
                            ? `h-gallery ${visible.length === 1 ? 'h-gallery--solo' : 'h-gallery--duo'}`
                            : 'rp-gallery'
                }
                style={strip || collage || duo ? undefined : { gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))` }}
            >
                {visible.map((image, i) => (
                    <button
                        key={image.id ?? i}
                        type="button"
                        className={itemClass(i)}
                        onClick={() => setIndex(i)}
                        aria-label={`Xem ảnh ${i + 1}`}
                        style={{ backgroundImage: `url("${image.url}")`, backgroundSize: 'cover', backgroundPosition: 'center' }}
                    >
                        <img src={image.url} alt={image.alt || ''} decoding="async" />
                    </button>
                ))}
            </div>

            {isOpen && (
                <div className="rp-lightbox" role="dialog" aria-modal="true" aria-label="Xem ảnh" onClick={close}>
                    <button type="button" className="rp-lightbox__close" onClick={close} aria-label="Đóng">
                        ×
                    </button>

                    {visible.length > 1 && (
                        <button type="button" className="rp-lightbox__nav rp-lightbox__nav--prev" onClick={(e) => { e.stopPropagation(); prev(); }} aria-label="Ảnh trước">
                            ‹
                        </button>
                    )}

                    <img src={visible[index].url} alt={visible[index].alt || ''} onClick={(event) => event.stopPropagation()} />

                    {visible.length > 1 && (
                        <button type="button" className="rp-lightbox__nav rp-lightbox__nav--next" onClick={(e) => { e.stopPropagation(); next(); }} aria-label="Ảnh sau">
                            ›
                        </button>
                    )}
                </div>
            )}
        </>
    );
}
