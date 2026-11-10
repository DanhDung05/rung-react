import React, { useState } from 'react';
import { usePage } from '@inertiajs/react';
import AdminSidebar from './Partials/AdminSidebar';
import AdminTopbar from './Partials/AdminTopbar';
import FlashMessage from '../Components/UI/FlashMessage';
import ThemeStyles from '../Components/ThemeStyles';

/**
 * Layout khu vực quản trị: sidebar + topbar + nội dung.
 */
export default function AdminLayout({ children, title = null, description = null, breadcrumbs = [], actions = null }) {
    const [open, setOpen] = useState(false);
    const { theme } = usePage().props;

    return (
        <div className="admin-shell">
            <ThemeStyles theme={theme} />

            <AdminSidebar open={open} onNavigate={() => setOpen(false)} />

            <div className="admin-main">
                <AdminTopbar onToggleSidebar={() => setOpen((value) => !value)} />

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
                <button
                    type="button"
                    className="sidebar-overlay"
                    onClick={() => setOpen(false)}
                    aria-label="Đóng menu"
                />
            )}
        </div>
    );
}
