import React from 'react';
import { Link } from '@inertiajs/react';
import { Phone, Mail, MapPin, Mountain } from 'lucide-react';
import { routes } from '../../lib/routes';

const FALLBACK_NAV = [
    { label: 'Tour khám phá', url: routes.tours.index },
    { label: 'Điểm đến', url: routes.destinations.index },
    { label: 'Cẩm nang', url: routes.posts.index },
];

export default function Footer({ settings = {}, navigation = {} }) {
    const items = navigation?.footer?.length ? navigation.footer : FALLBACK_NAV;
    const siteName = settings?.site_name || 'Rung Adventure';
    const year = new Date().getFullYear();

    const socials = [
        ['Facebook', settings?.social?.facebook],
        ['Instagram', settings?.social?.instagram],
        ['YouTube', settings?.social?.youtube],
        ['TikTok', settings?.social?.tiktok],
    ].filter(([, url]) => !!url);

    return (
        <footer className="site-footer">
            <div className="container footer-grid">
                <div>
                    <div className="brand footer-brand">
                        {settings?.logo_url ? (
                            <img src={settings.logo_url} alt={siteName} className="brand-logo" />
                        ) : (
                            <span className="brand-mark">
                                <Mountain />
                            </span>
                        )}
                        <span>
                            RUNG<span className="brand-gold">ADVENTURE</span>
                            <small>EXPLORE THE WILD</small>
                        </span>
                    </div>
                    <p>{settings?.site_description || 'Hành trình khám phá thiên nhiên, chạm đến những trải nghiệm nguyên bản.'}</p>
                </div>

                <div>
                    <b>Khám phá</b>
                    {items.map((item) => (
                        <Link key={item.id ?? item.url} href={item.url || '#'}>
                            {item.label}
                        </Link>
                    ))}
                </div>

                <div>
                    <b>Kết nối</b>
                    {settings?.contact_phone && (
                        <span>
                            <Phone size={15} /> {settings.contact_phone}
                        </span>
                    )}
                    {settings?.contact_email && (
                        <a href={`mailto:${settings.contact_email}`}>
                            <Mail size={15} /> {settings.contact_email}
                        </a>
                    )}
                    {settings?.contact_address && (
                        <span>
                            <MapPin size={15} /> {settings.contact_address}
                        </span>
                    )}
                    {socials.length > 0 && (
                        <span className="ui-socials">
                            {socials.map(([label, url]) => (
                                <a key={label} href={url} target="_blank" rel="noopener noreferrer">
                                    {label}
                                </a>
                            ))}
                        </span>
                    )}
                </div>
            </div>

            <div className="copyright">
                © {year} {siteName}. All rights reserved.
            </div>
        </footer>
    );
}
