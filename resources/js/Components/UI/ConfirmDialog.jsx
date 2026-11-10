import React from 'react';
import Modal from './Modal';
import Button from './Button';

export default function ConfirmDialog({
    open,
    title = 'Xác nhận thao tác',
    message,
    confirmLabel = 'Đồng ý',
    cancelLabel = 'Hủy',
    tone = 'danger',
    loading = false,
    onConfirm,
    onCancel,
}) {
    return (
        <Modal
            open={open}
            onClose={onCancel}
            title={title}
            size="sm"
            footer={
                <>
                    <Button variant="outline" onClick={onCancel} disabled={loading}>
                        {cancelLabel}
                    </Button>
                    <Button variant={tone} onClick={onConfirm} loading={loading}>
                        {confirmLabel}
                    </Button>
                </>
            }
        >
            <p className="ui-confirm__message">{message}</p>
        </Modal>
    );
}
