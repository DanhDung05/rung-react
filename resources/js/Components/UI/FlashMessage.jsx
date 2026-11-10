import React from 'react';
import { useFlash } from '../../hooks/useFlash';

const ICONS = {
    success: '✓',
    error: '!',
    info: 'i',
};

/**
 * Hiển thị flash message từ Laravel, tự ẩn sau 4 giây.
 * Đặt 1 lần trong mỗi layout.
 */
export default function FlashMessage() {
    const flash = useFlash();

    if (!flash) return null;

    return (
        <div className={`ui-flash ui-flash--${flash.type}`} role="status">
            <span className="ui-flash__icon" aria-hidden="true">
                {ICONS[flash.type] || 'i'}
            </span>
            <span className="ui-flash__text">{flash.text}</span>
            <button type="button" className="ui-flash__close" onClick={flash.clear} aria-label="Đóng thông báo">
                ×
            </button>
        </div>
    );
}
