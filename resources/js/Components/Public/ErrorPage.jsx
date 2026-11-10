import React from 'react';
import { Link } from '@inertiajs/react';
import { Compass, Home } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../Seo';
import { routes } from '../../lib/routes';

/**
 * Khung trang lỗi public.
 */
export default function ErrorPage({ code = '404', title = 'Không tìm thấy trang', message = '', image = null }) {
    return (
        <AppLayout>
            <Seo title={`${code} — ${title}`} noindex />

            <section className="rp-section rp-section--cream" style={{ minHeight: '58vh', display: 'flex', alignItems: 'center' }}>
                <div className="rp-container" style={{ textAlign: 'center', maxWidth: 720 }}>
                    <div
                        aria-hidden="true"
                        style={{
                            fontSize: 'clamp(5rem, 16vw, 9rem)',
                            fontWeight: 800,
                            lineHeight: 1,
                            letterSpacing: '-0.04em',
                            color: 'var(--rp-sand)',
                            marginBottom: 8,
                        }}
                    >
                        {code}
                    </div>

                    <h1 className="rp-h2" style={{ marginBottom: 14 }}>
                        {title}
                    </h1>

                    <p className="rp-lead" style={{ marginInline: 'auto', marginBottom: 28 }}>
                        {message}
                    </p>

                    <div className="rp-row" style={{ justifyContent: 'center' }}>
                        <Link href={routes.home} className="rp-btn rp-btn--primary">
                            <Home size={16} /> Về trang chủ
                        </Link>
                        <Link href={routes.tours.index} className="rp-btn rp-btn--outline">
                            <Compass size={16} /> Khám phá tour
                        </Link>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
