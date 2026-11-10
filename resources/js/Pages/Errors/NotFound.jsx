import React from 'react';
import ErrorPage from '../../Components/Public/ErrorPage';

export default function NotFound() {
    return (
        <ErrorPage
            code="404"
            title="Không tìm thấy trang"
            message="Đường dẫn bạn truy cập có thể đã bị thay đổi hoặc không còn tồn tại. Hãy quay lại trang chủ hoặc khám phá các hành trình của Rừng Adventure."
        />
    );
}
