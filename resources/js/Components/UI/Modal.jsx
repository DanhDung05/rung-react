import React, { useEffect } from 'react';

/**
 * Modal cơ bản: đóng bằng ESC, click overlay, khóa scroll nền.
 */
export default function Modal({ open, onClose, title, children, footer, size = 'md' }) {
    useEffect(() => {
        if (!open) return undefined;

        const onKeyDown = (event) => {
            if (event.key === 'Escape') onClose?.();
        };

        document.addEventListener('keydown', onKeyDown);
        const previous = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        return () => {
            document.removeEventListener('keydown', onKeyDown);
            document.body.style.overflow = previous;
        };
    }, [open, onClose]);

    if (!open) return null;

    return (
        <div className="ui-modal-overlay" role="presentation" onMouseDown={() => onClose?.()}>
            <div
                className={`ui-modal ui-modal--${size}`}
                role="dialog"
                aria-modal="true"
                aria-label={title || 'Hộp thoại'}
                onMouseDown={(event) => event.stopPropagation()}
            >
                <header className="ui-modal__head">
                    <h2>{title}</h2>
                    <button type="button" className="ui-modal__close" onClick={onClose} aria-label="Đóng">
                        ×
                    </button>
                </header>
                <div className="ui-modal__body">{children}</div>
                {footer && <footer className="ui-modal__foot">{footer}</footer>}
            </div>
        </div>
    );
}
