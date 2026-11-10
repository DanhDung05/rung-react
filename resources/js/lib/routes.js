/**
 * Tập trung toàn bộ URL của ứng dụng.
 *
 * Lý do: tránh hardcode chuỗi URL rải rác trong 70+ page component.
 * Không dùng Ziggy để giữ nguyên số dependency hiện có.
 */

const resource = (base) => ({
    index: base,
    create: `${base}/create`,
    store: base,
    show: (id) => `${base}/${id}`,
    edit: (id) => `${base}/${id}/edit`,
    update: (id) => `${base}/${id}`,
    destroy: (id) => `${base}/${id}`,
    trash: `${base}/thung-rac`,
    restore: (id) => `${base}/${id}/khoi-phuc`,
    forceDestroy: (id) => `${base}/${id}/xoa-cung`,
});

export const routes = {
    // Frontend
    home: '/',
    about: '/gioi-thieu',
    faq: '/faq',
    contact: '/lien-he',
    search: '/tim-kiem',
    sitemap: '/sitemap.xml',

    tours: {
        index: '/tour',
        show: (slug) => `/tour/${slug}`,
        category: (slug) => `/tour/danh-muc/${slug}`,
        review: (id) => `/tour/${id}/review`,
    },

    destinations: {
        index: '/diem-den',
        show: (slug) => `/diem-den/${slug}`,
    },

    posts: {
        index: '/bai-viet',
        show: (slug) => `/bai-viet/${slug}`,
        category: (slug) => `/bai-viet/danh-muc/${slug}`,
        tag: (slug) => `/bai-viet/tag/${slug}`,
    },

    page: (slug) => `/trang-tinh/${slug}`,

    auth: {
        login: '/dang-nhap',
        logout: '/dang-xuat',
    },

    // Admin
    admin: {
        dashboard: '/admin/dashboard',
        stats: '/admin/dashboard/stats',
        uploadImage: '/admin/upload/image',

        tours: resource('/admin/tour'),
        destinations: resource('/admin/diem-den'),
        categories: resource('/admin/danh-muc'),
        posts: resource('/admin/bai-viet'),
        tags: resource('/admin/tag'),
        sliders: resource('/admin/slider'),
        pages: resource('/admin/trang-tinh'),
        faqs: resource('/admin/faq'),
        menus: resource('/admin/menu'),
        users: resource('/admin/tai-khoan'),
        media: resource('/admin/media'),
        reviews: resource('/admin/danh-gia'),
        contacts: resource('/admin/lien-he'),

        menuItems: (id) => `/admin/menu/${id}/items`,
        menuItemsSave: (id) => `/admin/menu/${id}/items`,
        userToggleActive: (id) => `/admin/tai-khoan/${id}/toggle-active`,
        reviewApprove: (id) => `/admin/danh-gia/${id}/duyet`,
        reviewReject: (id) => `/admin/danh-gia/${id}/tu-choi`,
        contactStatus: (id) => `/admin/lien-he/${id}/status`,

        settings: {
            index: '/admin/cau-hinh',
            edit: (group) => `/admin/cau-hinh/${group}`,
            update: (group) => `/admin/cau-hinh/${group}`,
        },
    },
};

/** Giữ query string hiện tại và thay đổi một vài tham số. */
export function withQuery(url, params) {
    const [path, search = ''] = String(url).split('?');
    const query = new URLSearchParams(search);

    Object.entries(params || {}).forEach(([key, value]) => {
        if (value === null || value === undefined || value === '') {
            query.delete(key);
        } else {
            query.set(key, value);
        }
    });

    const qs = query.toString();
    return qs ? `${path}?${qs}` : path;
}

export default routes;
