import React, { useState } from 'react';
import ConfirmDialog from '../Components/UI/ConfirmDialog';

/**
 * Hook xác nhận: `const { confirm, dialog } = useConfirm()`.
 * Render {dialog} trong page; gọi `await confirm({...})` trả về boolean.
 */
export function useConfirm() {
    const [state, setState] = useState(null);

    const confirm = (options) =>
        new Promise((resolve) => {
            setState({ ...options, resolve });
        });

    const close = (result) => {
        state?.resolve(result);
        setState(null);
    };

    const dialog = (
        <ConfirmDialog
            open={!!state}
            title={state?.title || 'Xác nhận thao tác'}
            message={state?.message}
            confirmLabel={state?.confirmLabel || 'Đồng ý'}
            tone={state?.tone || 'danger'}
            onConfirm={() => close(true)}
            onCancel={() => close(false)}
        />
    );

    return { confirm, dialog };
}

export default useConfirm;
