/**
 * Nhãn tiếng Việt cho các giá trị enum trong DB.
 * Nguồn: migrations + constants của Models.
 */

export const STATUS = {
    draft: { label: 'Bản nháp', tone: 'muted' },
    published: { label: 'Đã xuất bản', tone: 'success' },
};

export const DIFFICULTY = {
    easy: 'Dễ',
    moderate: 'Trung bình',
    hard: 'Khó',
    expert: 'Chuyên gia',
};

export const ROLE = {
    admin: { label: 'Quản trị viên', tone: 'danger' },
    editor: { label: 'Biên tập viên', tone: 'info' },
    user: { label: 'Người dùng', tone: 'muted' },
};

export const CONTACT_STATUS = {
    new: { label: 'Chưa đọc', tone: 'warning' },
    read: { label: 'Đã đọc', tone: 'info' },
    replied: { label: 'Đã trả lời', tone: 'success' },
    archived: { label: 'Đã lưu trữ', tone: 'muted' },
};

export const REVIEW_STATUS = {
    pending: { label: 'Chờ duyệt', tone: 'warning' },
    approved: { label: 'Đã duyệt', tone: 'success' },
    rejected: { label: 'Từ chối', tone: 'danger' },
};

export const TARGET = {
    _self: 'Cùng tab',
    _blank: 'Tab mới',
};

export const COLLECTION = {
    default: 'Mặc định',
    featured: 'Ảnh đại diện',
    gallery: 'Thư viện ảnh',
    banner: 'Banner',
    content: 'Nội dung',
};

export const CATEGORY_TYPE = {
    tour: 'Danh mục tour',
    post: 'Danh mục bài viết',
};

/** Lấy label an toàn: statusLabel(STATUS, 'draft') */
export function statusMeta(map, key) {
    return map[key] || { label: key || '—', tone: 'muted' };
}
