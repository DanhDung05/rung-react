import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Mountain } from 'lucide-react';
import { routes } from '../lib/routes';
import FlashMessage from '../Components/UI/FlashMessage';
import ThemeStyles from '../Components/ThemeStyles';

/**
 * Layout trang đăng nhập.
 */
export default function AuthLayout({ children, title = 'Đăng nhập', description = null }) {
    const { settings, theme } = usePage().props;
    const siteName = settings?.site_name || 'Rung Adventure';

    return (
        <div className="auth-screen">
            <ThemeStyles theme={theme} />
            <FlashMessage />

            <div className="auth-card">
                <Link href={routes.home} className="brand">
                    {settings?.logo_url ? (
                        <img src={settings.logo_url} alt={siteName} className="brand-logo" />
                    ) : (
                        <span className="brand-mark">
                            <Mountain size={20} />
                        </span>
                    )}
                    <span>
                        RUNG<span className="brand-gold">ADVENTURE</span>
                        <small>ADMINISTRATION</small>
                    </span>
                </Link>

                <h1>{title}</h1>
                {description && <p>{description}</p>}

                {children}

                <div className="auth-bottom">© {new Date().getFullYear()} {siteName}</div>
            </div>
        </div>
    );
}
