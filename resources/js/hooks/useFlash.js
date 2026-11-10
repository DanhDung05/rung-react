import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';

/**
 * Trả về flash message hiện tại và tự ẩn sau `duration` ms.
 *
 * Dùng:
 *   const flash = useFlash();
 *   {flash && <FlashMessage {...flash} onClose={flash.clear} />}
 */
export function useFlash(duration = 4000) {
    const { flash } = usePage().props;
    const [message, setMessage] = useState(null);

    useEffect(() => {
        const next = flash?.success
            ? { type: 'success', text: flash.success }
            : flash?.error
              ? { type: 'error', text: flash.error }
              : flash?.info
                ? { type: 'info', text: flash.info }
                : null;

        setMessage(next);
    }, [flash?.success, flash?.error, flash?.info]);

    useEffect(() => {
        if (!message) return undefined;
        const timer = setTimeout(() => setMessage(null), duration);
        return () => clearTimeout(timer);
    }, [message, duration]);

    return message ? { ...message, clear: () => setMessage(null) } : null;
}

export default useFlash;
