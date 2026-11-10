import React from 'react';

export default function Spinner({ size = 16, label = 'Đang tải' }) {
    return (
        <span
            className="ui-spinner"
            style={{ width: size, height: size }}
            role="status"
            aria-label={label}
        />
    );
}
