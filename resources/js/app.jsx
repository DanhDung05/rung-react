import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import GenericPage from './Pages/GenericPage';

const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });

createInertiaApp({
    resolve: (name) => pages[`./Pages/${name}.jsx`]?.default || GenericPage,
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
    progress: { color: '#c58b45' },
});
