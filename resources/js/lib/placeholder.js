/**
 * Nguồn ảnh placeholder dùng chung.
 * - Không ghi vào DB, không hardcode trong dữ liệu nghiệp vụ.
 * - Ảnh thật luôn được ưu tiên: resolveImage(real, seed).
 * - Khi có ảnh thật, chỉ cần truyền URL vào resolveImage là placeholder tự bị bỏ.
 */

const PALETTES = [
    ['#123b2f', '#0a211b', '#2f6b55'],
    ['#1d3b46', '#0d2229', '#3f6f78'],
    ['#3c3a2a', '#1d1b12', '#7a6a44'],
    ['#213a2c', '#0f1f17', '#4d7358'],
    ['#2b3a45', '#141d24', '#5b7482'],
    ['#3a2f26', '#1b1611', '#7d5f45'],
];

function hash(value) {
    let result = 0;
    const text = String(value || 'rung-adventure');
    for (let i = 0; i < text.length; i += 1) {
        result = (result * 31 + text.charCodeAt(i)) % 100000;
    }
    return result;
}

export function placeholderImage(seed = 'rung-adventure') {
    const [top, bottom, ridge] = PALETTES[hash(seed) % PALETTES.length];

    const svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800" preserveAspectRatio="xMidYMid slice">
<defs>
<linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
<stop offset="0" stop-color="${top}"/><stop offset="1" stop-color="${bottom}"/>
</linearGradient>
</defs>
<rect width="1200" height="800" fill="url(#sky)"/>
<circle cx="960" cy="170" r="86" fill="#ffffff" opacity="0.10"/>
<path d="M0 560 L180 400 L320 500 L470 330 L640 520 L810 380 L980 540 L1200 400 L1200 800 L0 800 Z" fill="${ridge}" opacity="0.45"/>
<path d="M0 660 L160 540 L340 640 L520 500 L700 650 L900 520 L1080 640 L1200 570 L1200 800 L0 800 Z" fill="${ridge}" opacity="0.85"/>
<path d="M0 740 L240 690 L520 735 L760 700 L1000 745 L1200 710 L1200 800 L0 800 Z" fill="#08170f" opacity="0.55"/>
</svg>`;

    return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}`;
}

export function resolveImage(real, seed = 'rung-adventure') {
    return real || placeholderImage(seed);
}
