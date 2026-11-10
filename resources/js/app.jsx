import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

/**
 * Lazy-load từng page → không bundle toàn bộ 70+ trang vào 1 chunk.
 * Khi thêm page mới chỉ cần tạo file trong Pages/.
 */
const pages = import.meta.glob('./Pages/**/*.jsx');

createInertiaApp({
    resolve: async (name) => {
        const key = `./Pages/${name}.jsx`;
        const importer = pages[key];

        if (!importer) {
            if (import.meta.env.DEV) {
                // Cảnh báo rõ trong môi trường dev để không âm thầm rơi vào GenericPage.
                console.warn(
                    `[Inertia] Chưa có page component "${name}" (${key}). Đang dùng GenericPage tạm thời.`
                );
            }

            const fallback = await import('./Pages/GenericPage');

            return fallback.default;
        }

        const module = await importer();

        return module.default;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
    progress: { color: '#c58b45' },
});
