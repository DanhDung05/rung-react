import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Menu, X, Mountain, ArrowRight } from 'lucide-react';
import { routes } from '../../lib/routes';

const FALLBACK_NAV = [
    { label: 'Tour khám phá', url: routes.tours.index },
    { label: 'Điểm đến', url: routes.destinations.index },
    { label: 'Cẩm nang', url: routes.posts.index },
    { label: 'Giới thiệu', url: routes.about },
    { label: 'Liên hệ', url: routes.contact },
];

function isActive(currentUrl, url) {
    const path = String(currentUrl || '').split('?')[0];
    if (url === '/') return path === '/';
    return path === url || path.startsWith(`${url}/`);
}

export default function Header({ settings = {}, navigation = {} }) {
    const [open, setOpen] = useState(false);
    const [openSub, setOpenSub] = useState(null);
    const { url } = usePage();

    const items = navigation?.main?.length ? navigation.main : FALLBACK_NAV;
    const siteName = settings?.site_name || 'Rung Adventure';

    return (
        <header className="site-header">
            <div className="container header-inner">
                <Link href={routes.home} className="brand" aria-label={siteName}>
                    {settings?.logo_url ? (
                        <img src={settings.logo_url} alt={siteName} className="brand-logo" />
                    ) : (
                        <span className="brand-mark">
                            <Mountain size={23} />
                        </span>
                    )}
                    <span>
                        RUNG<span className="brand-gold">ADVENTURE</span>
                        <small>EXPLORE THE WILD</small>
                    </span>
                </Link>

                <nav className="desktop-nav" aria-label="Menu chính">
                    {items.map((item) => (
                        <div key={item.id ?? item.url} className={`nav-item ${item.children?.length ? 'has-children' : ''}`}>
                            <Link
                                href={item.url || '#'}
                                target={item.target || '_self'}
                                className={isActive(url, item.url) ? 'is-active' : ''}
                            >
                                {item.label}
                            </Link>

                            {item.children?.length > 0 && (
                                <ul className="nav-sub">
                                    {item.children.map((child) => (
                                        <li key={child.id ?? child.url}>
                                            <Link href={child.url || '#'} target={child.target || '_self'}>
                                                {child.label}
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    ))}
                </nav>

                <div className="header-actions">
                    <Link className="btn btn-gold header-cta" href={routes.tours.index}>
                        Khám phá ngay <ArrowRight size={16} />
                    </Link>
                    <button
                        type="button"
                        className="mobile-menu"
                        onClick={() => setOpen((value) => !value)}
                        aria-label={open ? 'Đóng menu' : 'Mở menu'}
                        aria-expanded={open}
                    >
                        {open ? <X /> : <Menu />}
                    </button>
                </div>

                {open && (
                    <nav className="mobile-nav" aria-label="Menu di động">
                        {items.map((item) => (
                            <div key={item.id ?? item.url}>
                                <Link href={item.url || '#'} onClick={() => setOpen(false)}>
                                    {item.label}
                                </Link>

                                {item.children?.length > 0 && (
                                    <>
                                        <button
                                            type="button"
                                            className="mobile-sub-toggle"
                                            onClick={() => setOpenSub(openSub === item.id ? null : item.id)}
                                        >
                                            Danh mục con
                                        </button>
                                        {openSub === item.id && (
                                            <div className="mobile-sub">
                                                {item.children.map((child) => (
                                                    <Link
                                                        key={child.id ?? child.url}
                                                        href={child.url || '#'}
                                                        onClick={() => setOpen(false)}
                                                    >
                                                        {child.label}
                                                    </Link>
                                                ))}
                                            </div>
                                        )}
                                    </>
                                )}
                            </div>
                        ))}
                    </nav>
                )}
            </div>
        </header>
    );
}
