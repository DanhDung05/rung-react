import React from 'react';
import ErrorPage from '../../Components/Public/ErrorPage';

export default function Expired() {
    return (
        <ErrorPage
            code="419"
            title="Phiên làm việc đã hết hạn"
            message="Trang đã chờ quá lâu nên phiên bảo mật không còn hiệu lực. Vui lòng tải lại trang và thử lại."
        />
    );
}
