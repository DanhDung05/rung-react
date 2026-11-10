import { useEffect, useRef, useState } from 'react';

/**
 * Phát hiện phần tử vào viewport để chạy animation nhẹ.
 * Tôn trọng prefers-reduced-motion và trình duyệt không hỗ trợ.
 */
export function useReveal({ threshold = 0.12, once = true } = {}) {
    const ref = useRef(null);
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const element = ref.current;

        if (!element) return undefined;

        const reduced = window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches;

        if (reduced || typeof IntersectionObserver === 'undefined') {
            setIsVisible(true);
            return undefined;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        setIsVisible(true);
                        if (once) observer.unobserve(entry.target);
                    } else if (!once) {
                        setIsVisible(false);
                    }
                });
            },
            { threshold, rootMargin: '0px 0px -40px 0px' }
        );

        observer.observe(element);

        return () => observer.disconnect();
    }, [threshold, once]);

    return { ref, isVisible };
}

export default useReveal;
