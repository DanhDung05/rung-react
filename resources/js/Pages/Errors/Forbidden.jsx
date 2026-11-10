import React from 'react';
import ErrorPage from '../../Components/Public/ErrorPage';

export default function Forbidden() {
    return (
        <ErrorPage
            code="403"
            title="Bạn không có quyền truy cập"
            message="Trang này yêu cầu quyền truy cập mà tài khoản hiện tại không có. Nếu bạn cho rằng đây là nhầm lẫn, vui lòng liên hệ quản trị viên."
        />
    );
}
