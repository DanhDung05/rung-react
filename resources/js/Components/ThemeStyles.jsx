import React from 'react';

/**
 * Inject màu/font do admin cấu hình trong Settings vào CSS variables.
 * Nhờ đó theme trong admin có tác dụng thật lên giao diện React.
 */
export default function ThemeStyles({ theme }) {
    if (!theme) return null;

    const headings = theme.headings || {};
    const lines = [
        `--color-primary:${theme.primary || '#307a55'};`,
        `--color-secondary:${theme.secondary || '#f97316'};`,
    ];

    Object.entries(headings).forEach(([tag, value]) => {
        if (value?.color) lines.push(`--color-${tag}:${value.color};`);
        if (value?.font) lines.push(`--font-${tag}:'${value.font}','Inter',sans-serif;`);
    });

    return <style>{`:root{${lines.join('')}}`}</style>;
}
