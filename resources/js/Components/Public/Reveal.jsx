import React from 'react';
import { useReveal } from '../../hooks/useReveal';

/**
 * Bọc nội dung để fade-up khi cuộn tới.
 * delay: mili giây (dùng cho stagger).
 */
export default function Reveal({ children, delay = 0, className = '', as: Tag = 'div' }) {
    const { ref, isVisible } = useReveal();

    return (
        <Tag
            ref={ref}
            className={`rp-reveal ${isVisible ? 'is-visible' : ''} ${className}`.trim()}
            style={delay ? { transitionDelay: `${delay}ms` } : undefined}
        >
            {children}
        </Tag>
    );
}
