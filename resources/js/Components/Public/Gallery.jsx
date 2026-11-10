import React, { useCallback, useEffect, useState } from 'react';

/**
 * Thư viện ảnh có lightbox (không cần thư viện ngoài).
 * images: [{ id, url, alt }]
 */
export default function Gallery({ images = [], columns = 3 }) {
    const [index, setIndex] = useState(null);
    const isOpen = index !== null;

    const close = useCallback(() => setIndex(null), []);
    const prev = useCallback(() => setIndex((current) => (current === null ? null : (current + images.length - 1) % images.length)), [images.length]);
    const next = useCallback(() => setIndex((current) => (current === null ? null : (current + 1) % images.length)), [images.length]);

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

    if (images.length === 0) return null;

    return (
        <>
            <div className="rp-gallery" style={{ gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))` }}>
                {images.map((image, i) => (
                    <button key={image.id ?? i} type="button" className="rp-gallery__item" onClick={() => setIndex(i)} aria-label={`Xem ảnh ${i + 1}`}>
                        <img src={image.url} alt={image.alt || ''} loading="lazy" decoding="async" />
                    </button>
                ))}
            </div>

            {isOpen && (
                <div className="rp-lightbox" role="dialog" aria-modal="true" aria-label="Xem ảnh" onClick={close}>
                    <button type="button" className="rp-lightbox__close" onClick={close} aria-label="Đóng">×</button>

                    {images.length > 1 && (
                        <button type="button" className="rp-lightbox__nav rp-lightbox__nav--prev" onClick={(e) => { e.stopPropagation(); prev(); }} aria-label="Ảnh trước">‹</button>
                    )}

                    <img
                        src={images[index].url}
                        alt={images[index].alt || ''}
                        onClick={(event) => event.stopPropagation()}
                    />

                    {images.length > 1 && (
                        <button type="button" className="rp-lightbox__nav rp-lightbox__nav--next" onClick={(e) => { e.stopPropagation(); next(); }} aria-label="Ảnh sau">›</button>
                    )}
                </div>
            )}
        </>
    );
}
