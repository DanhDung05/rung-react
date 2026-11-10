import React from 'react';

/**
 * Hiển thị 5 sao + điểm trung bình.
 */
export default function RatingStars({ value = 0, count = null, showValue = true }) {
    const rounded = Math.round(Number(value) || 0);

    return (
        <span className="rp-row" style={{ gap: 8 }}>
            <span className="rp-stars" aria-label={`${Number(value) || 0} trên 5 sao`}>
                {Array.from({ length: 5 }).map((_, index) => (
                    <span key={index} className={index < rounded ? 'is-on' : ''}>
                        ★
                    </span>
                ))}
            </span>
            {showValue && (
                <span className="rp-muted">
                    {Number(value || 0).toFixed(1)}
                    {count !== null ? ` · ${count} đánh giá` : ''}
                </span>
            )}
        </span>
    );
}
