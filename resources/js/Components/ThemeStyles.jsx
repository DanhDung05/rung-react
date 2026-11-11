import React from 'react';

const SIZE_SELECTORS = {
    h1: '.rp-app h1:not(.rp-slider__title), .rp-app .rp-display, .rp-app .rp-pagehead__title, .rp-app .h-welcome__title',
    h2: '.rp-app .rp-h2, .rp-app .h-about__title',
    h3: '.rp-app h3, .rp-app .rp-h3',
    'page-title': '.rp-app .rp-pagehead__title',
    'section-title': '.rp-app .rp-h2',
    body: '.rp-app p, .rp-app .rp-prose',
    lead: '.rp-app .rp-lead, .rp-app .h-welcome__lead, .rp-app .rp-pagehead__lead',
    eyebrow: '.rp-app .rp-eyebrow',
    breadcrumb: '.rp-app .rp-breadcrumb',
    footer: '.rp-footer',
};

export default function ThemeStyles({ theme, bgPrimary, bgAlternate }) {
    if (!theme && !bgPrimary && !bgAlternate) return null;

    const headings = theme?.headings || {};
    const palette = theme?.palette || {};
    const typography = theme?.typography || {};

    const lines = [
        `--color-primary:${theme?.primary || '#307a55'};`,
        `--color-secondary:${theme?.secondary || '#f97316'};`,
    ];

    if (bgPrimary) lines.push(`--rp-cream:${bgPrimary};`);
    if (bgAlternate) lines.push(`--rp-white:${bgAlternate};`);

    const paletteMap = {
        forest: '--rp-forest',
        cream: '--rp-cream',
        white: '--rp-white',
        earth: '--rp-earth',
        text: '--rp-ink',
        muted: '--rp-muted',
    };

    Object.entries(paletteMap).forEach(([key, variable]) => {
        if (palette[key]) lines.push(`${variable}:${palette[key]};`);
    });

    Object.entries(typography).forEach(([group, values]) => {
        if (!values) return;
        const prefix = `--typo-${group}`;
        if (values.font) lines.push(`${prefix}-font:${values.font};`);
        if (values.color) lines.push(`${prefix}-color:${values.color};`);
        if (values.weight) lines.push(`${prefix}-weight:${values.weight};`);
        if (values.line_height) lines.push(`${prefix}-line-height:${values.line_height};`);
    });

    Object.entries(headings).forEach(([tag, value]) => {
        if (value?.color) lines.push(`--color-${tag}:${value.color};`);
        if (value?.font) lines.push(`--font-${tag}:'${value.font}','Inter',sans-serif;`);
    });

    const propMap = { font: 'font-family', color: 'color', weight: 'font-weight', line_height: 'line-height', size: 'font-size' };

    const sizeRules = Object.entries(typography)
        .filter(([group]) => SIZE_SELECTORS[group])
        .flatMap(([group, values]) =>
            Object.entries(propMap)
                .filter(([prop]) => values?.[prop])
                .map(([prop, cssProp]) => `${SIZE_SELECTORS[group]}{${cssProp}:${values[prop]};}`)
        )
        .join('');

    return (
        <>
            <style>{`:root{${lines.join('')}}`}</style>
            {sizeRules && <style>{sizeRules}</style>}
        </>
    );
}
