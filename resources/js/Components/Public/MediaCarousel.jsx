import React, { useCallback, useEffect, useRef, useState } from 'react';
import { ChevronLeft, ChevronRight, Play, Pause, X } from 'lucide-react';

const AUTOPLAY_SPEED = 0.28; // ảnh/giây, giống nhịp chậm của bản gốc
const FOLLOW = 8;

function mod(value, total) {
    return ((value % total) + total) % total;
}

/**
 * Carousel 3D kiểu "The Window" — bản du lịch, ảnh khung ngang 3:2.
 * Ảnh xếp thành bức tường cong, kéo/chạm để trượt, có timeline và modal.
 */
export default function MediaCarousel({ images = [] }) {
    const list = (images || []).filter((item) => item?.url);
    const stageRef = useRef(null);
    const cardRefs = useRef([]);
    const positionRef = useRef(0);
    const targetRef = useRef(0);
    const playingRef = useRef(true);
    const dragRef = useRef(null);
    const hoverRef = useRef(false);
    const rafRef = useRef(null);
    const lastRef = useRef(0);

    const [index, setIndex] = useState(0);
    const [playing, setPlaying] = useState(true);
    const [preview, setPreview] = useState(null);

    const go = useCallback((direction) => {
        const total = list.length || 1;
        const current = Math.round(targetRef.current);
        targetRef.current = current + direction;
        positionRef.current += 0;
        setIndex(mod(current + direction, total));
    }, [list.length]);

    const draw = useCallback(() => {
        const stage = stageRef.current;
        if (!stage) return;

        const width = stage.clientWidth;
        const mobile = width < 700;
        const cardWidth = mobile ? width * 0.78 : Math.min(460, width * 0.34);
        const radius = mobile ? width * 0.92 : Math.min(880, width * 0.7);
        const recess = mobile ? 100 : 150;
        const total = list.length || 1;
        const active = mod(Math.round(positionRef.current), total);

        list.forEach((_, i) => {
            const card = cardRefs.current[i];
            if (!card) return;

            const distance = mod(i - positionRef.current + total / 2, total) - total / 2;
            const absolute = Math.abs(distance);
            const theta = distance * (mobile ? 0.26 : 0.34);
            const x = radius * Math.sin(theta);
            const z = radius * (1 - Math.cos(theta)) - recess;
            const scale = (1 - Math.min(absolute * 0.08, 0.32)) * (mobile ? 0.92 : 0.86);
            const angle = (-theta * 180) / Math.PI;
            const visible = absolute < 3.2;

            card.style.width = `${cardWidth}px`;
            card.style.transform = `translate(-50%, -50%) translateX(${x}px) translateZ(${z}px) rotateY(${angle}deg) scale(${scale})`;
            card.style.zIndex = String(100 - Math.round(absolute * 10));
            card.style.opacity = visible ? '1' : '0';
            card.style.pointerEvents = visible ? 'auto' : 'none';
        });

        if (active !== index) setIndex(active);
    }, [list, index]);

    useEffect(() => {
        if (list.length === 0) return undefined;

        const animate = (time) => {
            if (!lastRef.current) lastRef.current = time;
            const delta = Math.min(0.05, (time - lastRef.current) / 1000);
            lastRef.current = time;

            if (!dragRef.current && !hoverRef.current && playingRef.current && preview === null) {
                targetRef.current += delta * AUTOPLAY_SPEED;
            }

            positionRef.current += (targetRef.current - positionRef.current) * Math.min(1, delta * FOLLOW);
            draw();
            rafRef.current = requestAnimationFrame(animate);
        };

        rafRef.current = requestAnimationFrame(animate);

        return () => {
            if (rafRef.current) cancelAnimationFrame(rafRef.current);
            rafRef.current = null;
            lastRef.current = 0;
        };
    }, [draw, list.length, preview]);

    useEffect(() => {
        if (preview === null) return undefined;
        const onKey = (event) => {
            if (event.key === 'Escape') setPreview(null);
            if (event.key === 'ArrowLeft') setPreview((current) => mod(current - 1, list.length));
            if (event.key === 'ArrowRight') setPreview((current) => mod(current + 1, list.length));
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [preview, list.length]);

    const startDrag = (event) => {
        dragRef.current = { startX: event.clientX, startPosition: positionRef.current, moved: false };
        event.currentTarget.setPointerCapture?.(event.pointerId);
    };

    const moveDrag = (event) => {
        if (!dragRef.current) return;
        const stage = stageRef.current;
        const width = stage?.clientWidth || 1200;
        const step = Math.max(160, width * 0.34);
        const delta = event.clientX - dragRef.current.startX;

        if (Math.abs(delta) > 6) dragRef.current.moved = true;
        targetRef.current = dragRef.current.startPosition - delta / step;
    };

    const endDrag = () => {
        if (!dragRef.current) return;
        dragRef.current = null;
        targetRef.current = Math.round(targetRef.current);
    };

    if (list.length === 0) return null;

    const active = list[index] || list[0];

    return (
        <div className="ow-gallery">

            <div
                className="ow-stage"
                ref={stageRef}
                onPointerDown={startDrag}
                onPointerMove={moveDrag}
                onPointerUp={endDrag}
                onPointerCancel={endDrag}
                onMouseEnter={() => { hoverRef.current = true; targetRef.current = Math.round(targetRef.current); }}
                onMouseLeave={() => { hoverRef.current = false; }}
                role="region"
                aria-roledescription="carousel"
                aria-label="Thư viện ảnh"
            >
                <div className="ow-horizon" />

                {list.map((item, i) => (
                    <button
                        type="button"
                        key={item.id || i}
                        className={`ow-card ${i === index ? 'is-active' : ''}`}
                        ref={(node) => { cardRefs.current[i] = node; }}
                        onClick={() => {
                            if (dragRef.current?.moved) return;
                            if (i === index) setPreview(index);
                            else {
                                targetRef.current = i;
                                setIndex(i);
                            }
                        }}
                        aria-label={item.alt || `Ảnh ${i + 1}`}
                    >
                        <img src={item.url} alt={item.alt || ''} loading={Math.abs(i - index) < 3 ? 'eager' : 'lazy'} draggable="false" />
                    </button>
                ))}
            </div>

            <div className="ow-bottom">
                <div className="ow-project">
                    <span className="ow-number">
                        {String(index + 1).padStart(2, '0')} / {String(list.length).padStart(2, '0')}
                    </span>
                    <div>
                        <strong>{active.alt || 'Rừng Adventure'}</strong>
                        <small>{active.caption || 'Thư viện ảnh'}</small>
                    </div>
                </div>

                <div className="ow-timeline" aria-label="Chọn ảnh">
                    {list.map((item, i) => (
                        <button
                            key={item.id || i}
                            type="button"
                            className={`ow-tick ${i === index ? 'is-active' : ''}`}
                            onClick={() => { targetRef.current = i; setIndex(i); }}
                            aria-label={item.alt || `Ảnh ${i + 1}`}
                        />
                    ))}
                </div>

                <div className="ow-controls">
                    <button type="button" className="ow-round" onClick={() => go(-1)} aria-label="Ảnh trước"><ChevronLeft size={18} /></button>
                    <button type="button" className="ow-round" onClick={() => go(1)} aria-label="Ảnh sau"><ChevronRight size={18} /></button>
                    <button
                        type="button"
                        className="ow-round ow-play"
                        onClick={() => { setPlaying((value) => !value); playingRef.current = !playingRef.current; targetRef.current = Math.round(targetRef.current); }}
                        aria-label={playing ? 'Tạm dừng' : 'Tự chạy'}
                    >
                        {playing ? <Pause size={16} /> : <Play size={16} />}
                    </button>
                </div>
            </div>

            <p className="ow-hint">Kéo để xem · Bấm ảnh giữa để phóng to</p>

            {preview !== null && (
                <div className="ow-modal" role="dialog" aria-modal="true" aria-label="Xem ảnh">
                    <button type="button" className="ow-modal__close" onClick={() => setPreview(null)} aria-label="Đóng"><X size={22} /></button>
                    <button type="button" className="ow-modal__nav ow-modal__nav--prev" onClick={() => setPreview(mod(preview - 1, list.length))} aria-label="Ảnh trước"><ChevronLeft size={26} /></button>
                    <img className="ow-modal__image" src={list[preview].url} alt={list[preview].alt || ''} />
                    <button type="button" className="ow-modal__nav ow-modal__nav--next" onClick={() => setPreview(mod(preview + 1, list.length))} aria-label="Ảnh sau"><ChevronRight size={26} /></button>
                    <div className="ow-modal__caption">
                        <strong>{list[preview].alt}</strong>
                        <small>{list[preview].caption}</small>
                    </div>
                </div>
            )}
        </div>
    );
}
