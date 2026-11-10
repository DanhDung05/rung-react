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
        <div className={`ui-app ${className}`.trim()}>
            <ThemeStyles theme={theme} />
            <Header settings={settings} navigation={navigation} />
            <main id="main-content" className="ui-app__main">
                {children}
            </main>
            <Footer settings={settings} navigation={navigation} />
            <FlashMessage />
        </div>
    );
}
