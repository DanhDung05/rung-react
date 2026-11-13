import React from 'react';
import { Link } from '@inertiajs/react';
import { Phone, Mail, MapPin, Mountain, ArrowUpRight, Clock } from 'lucide-react';
import { routes } from '../../lib/routes';

const FALLBACK_NAV = [
    { label: 'Tour khám phá', url: routes.tours.index },
    { label: 'Điểm đến', url: routes.destinations.index },
    { label: 'Cẩm nang', url: routes.posts.index },
];

export default function Footer({ settings = {}, navigation = {} }) {
    const explore = navigation?.footer?.length ? navigation.footer : FALLBACK_NAV;
    const siteName = settings?.site_name || 'Rung Adventure';
    const year = new Date().getFullYear();

    const socials = [
        ['FB', settings?.social?.facebook],
        ['IG', settings?.social?.instagram],
        ['YT', settings?.social?.youtube],
        ['TT', settings?.social?.tiktok],
    ].filter(([, url]) => !!url);

    return (
        <footer className="rp-footer">
            <div className="rp-container">
                <div className="rp-footer__grid">
                    <div className="rp-footer__brand">
                        <Link href={routes.home} className="rp-brand">
                            {settings?.logo_url ? (
                                <img src={settings.logo_url} alt={siteName} className="rp-brand__logo" />
                            ) : (
                                <span className="rp-brand__mark">
                                    <Mountain size={21} />
                                </span>
                            )}
                            <span className="rp-brand__text" style={settings?.logo_url ? { display: 'none' } : undefined}>
                                RUNG<span className="rp-brand__gold">ADVENTURE</span>
                                <small>EXPLORE THE WILD</small>
                            </span>
                        </Link>

                        <p>
                            {settings?.site_description ||
                                'Hành trình khám phá thiên nhiên Việt Nam — trekking, cắm trại và những trải nghiệm nguyên bản.'}
                        </p>

                        {socials.length > 0 && (
                            <div className="rp-footer__socials">
                                {socials.map(([label, url]) => (
                                    <a key={label} href={url} target="_blank" rel="noopener noreferrer" aria-label={label}>
                                        {label}
                                    </a>
                                ))}
                            </div>
                        )}
                    </div>

                    <div>
                        <div className="rp-footer__title">Khám phá</div>
                        <div className="rp-footer__links">
                            {explore.map((item) => (
                                <Link key={item.id ?? item.url} href={item.url || '#'}>
                                    {item.label}
                                </Link>
                            ))}
                            <Link href={routes.about}>Giới thiệu</Link>
                            <Link href={routes.faq}>Câu hỏi thường gặp</Link>
                        </div>
                    </div>

                    <div>
                        <div className="rp-footer__title">Liên hệ</div>
                        <div className="rp-footer__contact">
                            {settings?.contact_address && (
                                <span>
                                    <MapPin size={15} style={{ marginTop: 2, flex: 'none' }} /> {settings.contact_address}
                                </span>
                            )}
                            {settings?.contact_phone && (
                                <a href={`tel:${settings.contact_phone}`}>
                                    <Phone size={15} style={{ marginTop: 2, flex: 'none' }} /> {settings.contact_phone}
                                </a>
                            )}
                            {settings?.contact_hours && (
                                <span>
                                    <Clock size={15} style={{ marginTop: 2, flex: 'none' }} /> {settings.contact_hours}
                                </span>
                            )}
                            {settings?.contact_email && (
                                <a href={`mailto:${settings.contact_email}`}>
                                    <Mail size={15} style={{ marginTop: 2, flex: 'none' }} /> {settings.contact_email}
                                </a>
                            )}
                        </div>
                    </div>

                    <div>
                        <div className="rp-footer__title">Bắt đầu hành trình</div>
                        <p style={{ fontSize: '.85rem', lineHeight: 1.7, color: 'rgba(233,228,214,.66)', margin: '0 0 18px' }}>
                            Cần tư vấn lịch trình riêng? Đội ngũ Rừng Adventure sẵn sàng đồng hành.
                        </p>
                        <Link href={routes.contact} className="rp-btn rp-btn--gold rp-btn--sm">
                            Gửi yêu cầu <ArrowUpRight size={14} />
                        </Link>
                    </div>
                </div>

                <div className="rp-footer__bottom">
                    <span>© {year} {siteName}. All rights reserved.</span>
                    <span style={{ display: 'flex', gap: 18 }}>
                        <Link href={routes.sitemap}>Sitemap</Link>
                        <Link href={routes.search}>Tìm kiếm</Link>
                        <Link href={routes.contact}>Liên hệ</Link>
                    </span>
                </div>
            </div>
        </footer>
    );
}
