import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    LayoutDashboard,
    Mountain,
    MapPinned,
    Compass,
    BookOpen,
    FileText,
    CircleHelp,
    Images,
    Menu as MenuIcon,
    MessageSquare,
    Star,
    Users,
    Settings,
    ExternalLink,
} from 'lucide-react';
import { routes } from '../../lib/routes';

/**
 * Cấu hình menu quản trị.
 * `adminOnly: true` → chỉ role admin thấy (editor bị ẩn).
 */
const NAV = [
    {
        title: null,
        items: [{ label: 'Tổng quan', href: routes.admin.dashboard, icon: LayoutDashboard, exact: true }],
    },
    {
        title: 'Nội dung',
        items: [
            { label: 'Tour', href: routes.admin.tours.index, icon: Mountain },
            { label: 'Điểm đến', href: routes.admin.destinations.index, icon: MapPinned },
            { label: 'Danh mục', href: routes.admin.categories.index, icon: Compass },
            { label: 'Bài viết', href: routes.admin.posts.index, icon: BookOpen },
            { label: 'Trang tĩnh', href: routes.admin.pages.index, icon: FileText },
            { label: 'FAQ', href: routes.admin.faqs.index, icon: CircleHelp },
            { label: 'Slider', href: routes.admin.sliders.index, icon: Images },
        ],
    },
    {
        title: 'Giao diện',
        items: [
            { label: 'Menu', href: routes.admin.menus.index, icon: MenuIcon },
            { label: 'Thư viện ảnh', href: routes.admin.media.index, icon: Images },
        ],
    },
    {
        title: 'Tương tác',
        items: [
            { label: 'Liên hệ', href: routes.admin.contacts.index, icon: MessageSquare },
            { label: 'Đánh giá', href: routes.admin.reviews.index, icon: Star },
        ],
    },
    {
        title: 'Hệ thống',
        items: [
            { label: 'Người dùng', href: routes.admin.users.index, icon: Users, adminOnly: true },
            { label: 'Cài đặt', href: routes.admin.settings.index, icon: Settings, adminOnly: true },
        ],
    },
];

function isActive(currentUrl, href, exact = false) {
    const path = String(currentUrl || '').split('?')[0];
    if (exact) return path === href;
    return path === href || path.startsWith(`${href}/`);
}

export default function AdminSidebar({ open = false, onNavigate }) {
    const { url, props } = usePage();
    const isAdmin = props.auth?.user?.is_admin === true;

    return (
        <aside className={`admin-sidebar ${open ? 'is-open' : ''}`}>
            <Link href={routes.home} className="brand admin-brand">
                <span className="brand-mark">
                    <Mountain size={20} />
                </span>
                <span>
                    RUNG<span className="brand-gold">ADVENTURE</span>
                    <small>ADMIN CONSOLE</small>
                </span>
            </Link>

            <nav className="admin-nav" aria-label="Menu quản trị">
                {NAV.map((group, index) => {
                    const items = group.items.filter((item) => !item.adminOnly || isAdmin);
                    if (items.length === 0) return null;

                    return (
                        <div key={group.title || `group-${index}`} className="admin-nav__group">
                            {group.title && <div className="sidebar-caption">{group.title}</div>}
                            {items.map((item) => {
                                const Icon = item.icon;
                                return (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        onClick={onNavigate}
                                        className={`side-link ${isActive(url, item.href, item.exact) ? 'is-active' : ''}`}
                                    >
                                        <Icon size={18} />
                                        {item.label}
                                    </Link>
                                );
                            })}
                        </div>
                    );
                })}
            </nav>

            <div className="admin-nav__footer">
                <Link href={routes.home} className="side-link" onClick={onNavigate}>
                    <ExternalLink size={18} />
                    Xem website
                </Link>
            </div>
        </aside>
    );
}
