import React, { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Menu, Mountain, Search, ArrowRight, X, ChevronDown } from 'lucide-react';
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
    if (!url || url === '/') return path === '/';
    return path === url || path.startsWith(`${url}/`);
}

export default function Header({ settings = {}, navigation = {}, overlay = true }) {
    const [scrolled, setScrolled] = useState(false);
    const [open, setOpen] = useState(false);
    const [openSub, setOpenSub] = useState(null);
    const { url } = usePage();

    const items = navigation?.main?.length ? navigation.main : FALLBACK_NAV;
    const siteName = settings?.site_name || 'Rung Adventure';
    const solid = scrolled || !overlay || open;

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 40);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => {
        setOpen(false);
        setOpenSub(null);
    }, [url]);

    useEffect(() => {
        if (!open) return undefined;
        const previous = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        return () => {
            document.body.style.overflow = previous;
        };
    }, [open]);

    return (
        <>
            <header className={`rp-header ${solid ? 'is-solid' : ''} ${overlay ? 'is-overlay' : ''}`}>
                <div className="rp-container rp-header__inner">
                    <Link href={routes.home} className="rp-brand" aria-label={siteName}>
                        {settings?.logo_url ? (
                            <img src={settings.logo_url} alt={siteName} className="rp-brand__logo" />
                        ) : (
                            <span className="rp-brand__mark">
                                <Mountain size={22} />
                            </span>
                        )}
                        <span className="rp-brand__text" style={settings?.logo_url ? { display: 'none' } : undefined}>
                            RUNG<span className="rp-brand__gold">ADVENTURE</span>
                            <small>EXPLORE THE WILD</small>
                        </span>
                    </Link>

                    <nav className="rp-nav" aria-label="Menu chính">
                        {items.map((item) => (
                            <div className="rp-nav__item" key={item.id ?? item.url}>
                                <Link
                                    href={item.url || '#'}
                                    target={item.target || '_self'}
                                    className={`rp-nav__link ${isActive(url, item.url) ? 'is-active' : ''}`}
                                >
                                    {item.label}
                                </Link>

                                {item.children?.length > 0 && (
                                    <ul className="rp-nav__sub">
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

                    <div className="rp-header__actions">
                        <Link href={routes.search} className="rp-header__search" aria-label="Tìm kiếm">
                            <Search size={17} />
                        </Link>

                        <Link href={routes.tours.index} className="rp-btn rp-btn--gold rp-btn--sm">
                            Khám phá <ArrowRight size={15} />
                        </Link>

                        <button
                            type="button"
                            className="rp-menu-btn"
                            onClick={() => setOpen(true)}
                            aria-label="Mở menu"
                            aria-expanded={open}
                        >
                            <Menu size={19} />
                        </button>
                    </div>
                </div>
            </header>

            {open && (
                <div className="rp-mobile" role="dialog" aria-modal="true" aria-label="Menu di động">
                    <div className="rp-mobile__head">
                        <Link href={routes.home} className="rp-brand" onClick={() => setOpen(false)}>
                            {settings?.logo_url ? (
                                <img src={settings.logo_url} alt={siteName} className="rp-brand__logo" />
                            ) : (
                                <span className="rp-brand__mark">
                                    <Mountain size={20} />
                                </span>
                            )}
                            <span className="rp-brand__text" style={settings?.logo_url ? { display: 'none' } : undefined}>
                                RUNG<span className="rp-brand__gold">ADVENTURE</span>
                                <small>EXPLORE THE WILD</small>
                            </span>
                        </Link>

                        <button type="button" className="rp-mobile__close" onClick={() => setOpen(false)} aria-label="Đóng menu">
                            <X size={20} />
                        </button>
                    </div>

                    <nav className="rp-mobile__list" aria-label="Menu di động">
                        {items.map((item, index) => (
                            <div key={item.id ?? item.url}>
                                <Link href={item.url || '#'} className="rp-mobile__link" onClick={() => setOpen(false)}>
                                    {item.label}
                                </Link>

                                {item.children?.length > 0 && (
                                    <>
                                        <button
                                            type="button"
                                            className="rp-mobile__link"
                                            style={{ width: '100%', textAlign: 'left', background: 'none', border: 0, borderBottom: '1px solid rgba(255,255,255,.12)' }}
                                            onClick={() => setOpenSub(openSub === index ? null : index)}
                                            aria-expanded={openSub === index}
                                        >
                                            <span style={{ fontSize: '.82rem', letterSpacing: '.06em', opacity: 0.75 }}>
                                                Danh mục con <ChevronDown size={14} style={{ verticalAlign: '-2px' }} />
                                            </span>
                                        </button>

                                        {openSub === index && (
                                            <div className="rp-mobile__sub">
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

                    <div className="rp-mobile__foot">
                        <Link href={routes.contact} className="rp-btn rp-btn--gold" style={{ width: '100%' }} onClick={() => setOpen(false)}>
                            Liên hệ tư vấn <ArrowRight size={15} />
                        </Link>
                    </div>
                </div>
            )}
        </>
    );
}
