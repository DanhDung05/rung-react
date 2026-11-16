import React, { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import AdminSidebar from './Partials/AdminSidebar';
import AdminTopbar from './Partials/AdminTopbar';
import FlashMessage from '../Components/UI/FlashMessage';
import ThemeStyles from '../Components/ThemeStyles';
import useAdminRealtime from '../hooks/useAdminRealtime';

/**
 * Layout khu vực quản trị: sidebar + topbar + nội dung.
 * Hỗ trợ giao diện Sáng/Tối, thu gọn sidebar và thông báo realtime.
 */
export default function AdminLayout({ children, title = null, description = null, breadcrumbs = [], actions = null }) {
    const [open, setOpen] = useState(false);
    const { theme, admin_badges: initialBadges = null } = usePage().props;
    const { badges, toasts } = useAdminRealtime(initialBadges);

    const [mode, setMode] = useState(() => (typeof window !== 'undefined' ? window.localStorage.getItem('admin_theme') || 'light' : 'light'));
    const [collapsed, setCollapsed] = useState(() => (typeof window !== 'undefined' ? window.localStorage.getItem('admin_sidebar') === 'collapsed' : false));

    useEffect(() => {
        if (typeof window !== 'undefined') window.localStorage.setItem('admin_theme', mode);
    }, [mode]);

    useEffect(() => {
        if (typeof window !== 'undefined') window.localStorage.setItem('admin_sidebar', collapsed ? 'collapsed' : 'expanded');
    }, [collapsed]);

    return (
        <div className={`admin-shell ${collapsed ? 'is-collapsed' : ''}`.trim()} data-theme={mode}>
            <ThemeStyles theme={theme} />

            <AdminSidebar open={open} collapsed={collapsed} onNavigate={() => setOpen(false)} />

            <div className="admin-main">
                <AdminTopbar
                    onToggleSidebar={() => setOpen((value) => !value)}
                    mode={mode}
                    onToggleMode={() => setMode((value) => (value === 'dark' ? 'light' : 'dark'))}
                    collapsed={collapsed}
                    onToggleCollapsed={() => setCollapsed((value) => !value)}
                    badges={badges}
                />

                <main className="admin-content">
                    {(title || breadcrumbs.length > 0) && (
                        <div className="admin-page-heading">
                            <div>
                                {breadcrumbs.length > 0 && (
                                    <nav className="ui-breadcrumb" aria-label="Breadcrumb">
                                        {breadcrumbs.map((crumb, index) => (
                                            <span key={`${crumb.label}-${index}`}>
                                                {index > 0 && <span className="ui-breadcrumb__sep">/</span>}
                                                {crumb.label}
                                            </span>
                                        ))}
                                    </nav>
                                )}
                                <div className="eyebrow dark">RUNG ADVENTURE / QUẢN TRỊ</div>
                                {title && <h1>{title}</h1>}
                                {description && <p>{description}</p>}
                            </div>
                            {actions && <div className="admin-page-heading__actions">{actions}</div>}
                        </div>
                    )}

                    <FlashMessage />

                    {children}
                </main>
            </div>

            {open && (
                <button type="button" className="sidebar-overlay" onClick={() => setOpen(false)} aria-label="Đóng menu" />
            )}

            {toasts.length > 0 && (
                <div className="admin-toasts" role="status" aria-live="polite">
                    {toasts.map((toast) => (
                        <Link href={toast.href} key={toast.key} className="admin-toast">
                            <strong>{toast.title}</strong>
                            <span>{toast.time || 'Vừa xong'}</span>
                        </Link>
                    ))}
                </div>
            )}
        </div>
    );
}
