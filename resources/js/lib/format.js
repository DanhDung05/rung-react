/**
 * Định dạng dữ liệu hiển thị — dùng chung toàn bộ React.
 */

/** 1500000 → "1.500.000 ₫" */
export function money(value) {
    if (value === null || value === undefined || value === '') return 'Liên hệ';
    const number = Number(value);
    if (Number.isNaN(number)) return 'Liên hệ';
    return new Intl.NumberFormat('vi-VN').format(number) + ' ₫';
}

/** 1234 → "1.234" */
export function number(value) {
    const n = Number(value ?? 0);
    return Number.isNaN(n) ? '0' : new Intl.NumberFormat('vi-VN').format(n);
}

/** ISO date → "10/11/2026" */
export function date(value) {
    if (!value) return '—';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

/** ISO date → "10/11/2026 14:30" */
export function datetime(value) {
    if (!value) return '—';
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return '—';
    return d.toLocaleString('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

/** Cắt chuỗi, giữ nguyên từ */
export function truncate(text, max = 120) {
    if (!text) return '';
    const clean = String(text).trim();
    if (clean.length <= max) return clean;
    return clean.slice(0, max).trimEnd() + '…';
}

/** Bỏ thẻ HTML để lấy text thuần */
export function stripHtml(html) {
    if (!html) return '';
    return String(html).replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

/** Chuỗi tiếng Việt → slug */
export function slugify(value) {
    return String(value || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/Đ/g, 'D')
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

/** Rút gọn số lớn: 1200 → 1,2K */
export function compactNumber(value) {
    const n = Number(value ?? 0);
    if (n >= 1000000) return (n / 1000000).toFixed(1).replace('.', ',') + 'M';
    if (n >= 1000) return (n / 1000).toFixed(1).replace('.', ',') + 'K';
    return String(n);
}

/**
 * Đường dẫn file trên disk public → URL hiển thị được.
 * DB lưu dạng "media/tours/2026/10/abc.jpg"; public/storage là symlink tới storage/app/public.
 */
export function mediaUrl(path) {
    if (!path) return null;
    if (/^https?:\/\//i.test(path)) return path;
    return path.startsWith('/') ? path : `/storage/${path}`;
}
