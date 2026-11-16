import { useEffect, useRef, useState } from 'react';
import { router, usePage } from '@inertiajs/react';

const POLL_INTERVAL = 60000;
const RELOAD_DEBOUNCE = 500;

const PAGE_PROPS = {
    'Admin/Contacts/Index': ['contacts', 'newCount', 'admin_badges'],
    'Admin/Reviews/Index': ['reviews', 'admin_badges'],
    'Admin/Dashboard': ['stats', 'latestContacts', 'admin_badges'],
};

function normalizeCounts(source) {
    return {
        contacts_new: Number(source?.contacts_new ?? source?.contacts ?? 0),
        reviews_pending: Number(source?.reviews_pending ?? source?.reviews ?? 0),
    };
}

/**
 * Realtime Admin tập trung: badge, toast, title, reload trang và polling dự phòng.
 */
export default function useAdminRealtime(initialBadges) {
    const { component } = usePage();
    const [badges, setBadges] = useState(() => normalizeCounts(initialBadges));
    const [toasts, setToasts] = useState([]);
    const componentRef = useRef(component);
    const baseTitle = useRef(typeof document === 'undefined' ? '' : document.title.replace(/^\(\d+\)\s*/, ''));
    const reloadTimer = useRef(null);

    componentRef.current = component;

    useEffect(() => {
        if (initialBadges) setBadges(normalizeCounts(initialBadges));
    }, [initialBadges?.contacts_new, initialBadges?.reviews_pending]);

    const pushToast = (toast) => {
        const key = `${toast.type}-${toast.id}-${Date.now()}`;
        setToasts((current) => [...current.slice(-2), { ...toast, key }]);
        window.setTimeout(() => setToasts((current) => current.filter((item) => item.key !== key)), 8000);
    };

    const scheduleReload = () => {
        const only = PAGE_PROPS[componentRef.current];
        if (!only) return;

        if (reloadTimer.current) window.clearTimeout(reloadTimer.current);
        reloadTimer.current = window.setTimeout(() => {
            router.reload({ only, preserveScroll: true, preserveState: true });
        }, RELOAD_DEBOUNCE);
    };

    useEffect(() => {
        const echo = typeof window === 'undefined' ? null : window.Echo;
        let pollTimer = null;
        let socketConnection = null;

        const applyCounts = (event) => {
            if (typeof event.contacts_new === 'number' || typeof event.reviews_pending === 'number') {
                setBadges({
                    contacts_new: Number(event.contacts_new ?? 0),
                    reviews_pending: Number(event.reviews_pending ?? 0),
                });
            }
        };

        const poll = () => {
            if (document.visibilityState !== 'visible') return;
            router.reload({ only: ['admin_badges'], preserveScroll: true, preserveState: true });
        };

        const stopPolling = () => {
            if (pollTimer) {
                window.clearInterval(pollTimer);
                pollTimer = null;
            }
        };

        const startPolling = () => {
            if (pollTimer) return;
            poll();
            pollTimer = window.setInterval(poll, POLL_INTERVAL);
        };

        const isConnected = () => socketConnection?.state === 'connected';

        const onVisible = () => {
            if (document.visibilityState !== 'visible') {
                stopPolling();
                return;
            }

            if (!isConnected()) {
                startPolling();
            }
        };

        const addSocketHeader = (event) => {
            const socketId = echo?.socketId?.();
            if (socketId) {
                event.detail.visit.headers = { ...(event.detail.visit.headers || {}), 'X-Socket-ID': socketId };
            }
        };

        let subscription = null;

        if (echo) {
            try {
                subscription = echo.private('admin-notifications');
                socketConnection = echo.connector?.pusher?.connection || null;

                subscription.listen('.new-contact', (event) => {
                    applyCounts(event);
                    pushToast({
                        type: event.type,
                        id: event.id,
                        title: event.title || 'Liên hệ mới',
                        time: event.time,
                        href: `/admin/lien-he/${event.id}`,
                    });
                    scheduleReload();
                });

                subscription.listen('.new-review', (event) => {
                    applyCounts(event);
                    pushToast({
                        type: 'review',
                        id: event.id,
                        title: event.title || 'Đánh giá mới',
                        time: event.time,
                        href: '/admin/danh-gia',
                    });
                    scheduleReload();
                });

                subscription.listen('.contact-status-changed', (event) => {
                    applyCounts(event);
                    if (componentRef.current === 'Admin/Contacts/Show') {
                        window.dispatchEvent(new CustomEvent('admin:contact-status', {
                            detail: { id: event.id, status: event.status },
                        }));
                    } else {
                        scheduleReload();
                    }
                });

                subscription.listen('.review-moderated', (event) => {
                    applyCounts(event);
                    scheduleReload();
                });

                if (socketConnection) {
                    socketConnection.bind('state_change', (states) => {
                        if (states.current === 'connected') {
                            stopPolling();
                            router.reload({
                                only: ['admin_badges', ...(PAGE_PROPS[componentRef.current] || [])],
                                preserveScroll: true,
                                preserveState: true,
                            });
                        } else {
                            startPolling();
                        }
                    });
                }

                if (!isConnected()) startPolling();
            } catch {
                startPolling();
            }
        } else {
            startPolling();
        }

        document.addEventListener('visibilitychange', onVisible);
        document.addEventListener('inertia:before', addSocketHeader);

        return () => {
            stopPolling();
            if (reloadTimer.current) window.clearTimeout(reloadTimer.current);
            document.removeEventListener('visibilitychange', onVisible);
            document.removeEventListener('inertia:before', addSocketHeader);

            if (subscription) {
                [' .new-contact', '.new-review', '.contact-status-changed', '.review-moderated'].forEach((name) => {
                    try {
                        subscription.stopListening(name.trim());
                    } catch {
                        // ignore
                    }
                });
            }

            if (socketConnection) {
                try {
                    socketConnection.unbind('state_change');
                } catch {
                    // ignore
                }
            }

            if (echo) {
                try {
                    echo.leave('admin-notifications');
                } catch {
                    // ignore
                }
            }
        };
    }, []);

    useEffect(() => {
        const total = badges.contacts_new + badges.reviews_pending;
        const base = baseTitle.current;
        document.title = total > 0 ? `(${total}) ${base}` : base;

        return () => {
            document.title = base;
        };
    }, [badges.contacts_new, badges.reviews_pending]);

    return { badges, toasts, dismissToast: (key) => setToasts((current) => current.filter((item) => item.key !== key)) };
}
