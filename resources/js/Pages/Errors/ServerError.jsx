import React from 'react';
import ErrorPage from '../../Components/Public/ErrorPage';

export default function ServerError() {
    return (
        <ErrorPage
            code="500"
            title="Có lỗi xảy ra"
            message="Hệ thống đang gặp sự cố tạm thời. Vui lòng thử lại sau ít phút hoặc liên hệ với chúng tôi nếu tình trạng kéo dài."
        />
    );
}
