import React from 'react';

/**
 * Nội dung HTML do admin soạn (TinyMCE) — hiển thị giống Blade cũ.
 * Nội dung này do quản trị viên/editor nhập, không phải input người dùng công khai.
 */
export default function Prose({ html = '', className = '' }) {
    if (!html) return null;

    return <div className={`rp-prose ${className}`.trim()} dangerouslySetInnerHTML={{ __html: html }} />;
}
