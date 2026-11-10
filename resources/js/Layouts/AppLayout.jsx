import React from 'react';
import { usePage } from '@inertiajs/react';
import Header from './Partials/Header';
import Footer from './Partials/Footer';
import FlashMessage from '../Components/UI/FlashMessage';
import ThemeStyles from '../Components/ThemeStyles';

/**
 * Layout công khai: header + nội dung + footer + flash.
 * Mọi trang frontend phải dùng layout này (không tự dựng header/footer).
 */
export default function AppLayout({ children, className = '' }) {
    const { settings, navigation, theme } = usePage().props;

    return (
        <div className={`rp-app ui-app ${className}`.trim()}>
            <ThemeStyles theme={theme} />

            <a href="#main-content" className="rp-skip">
                Bỏ qua điều hướng
            </a>

            <Header settings={settings} navigation={navigation} />

            <main id="main-content" className="rp-app__main">
                {children}
            </main>

            <Footer settings={settings} navigation={navigation} />
            <FlashMessage />
        </div>
    );
}
