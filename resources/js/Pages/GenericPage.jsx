import React, { useMemo } from 'react';
import { usePage } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';
import AdminLayout from '../Layouts/AdminLayout';
import AuthLayout from '../Layouts/AuthLayout';
import Seo from '../Components/Seo';
import Card from '../Components/UI/Card';
import EmptyState from '../Components/UI/EmptyState';

/**
 * FALLBACK TẠM THỜI cho các trang Inertia chưa có component riêng.
 *
 * Đây KHÔNG phải giao diện thật. Nó chỉ hiển thị dữ liệu thô để không trắng trang.
 * Sẽ bị loại bỏ khi hoàn thành Giai đoạn 2 (đợt 2E).
 */

const TITLE_MAP = {
    Home: 'Khám phá thiên nhiên cùng Rung Adventure',
    'Admin/Dashboard': 'Tổng quan quản trị',
    'Auth/Login': 'Đăng nhập hệ thống',
};

const HIDDEN_KEYS = ['auth', 'flash', 'errors', 'settings', 'theme', 'navigation', 'app', 'ziggy', 'title', 'pageKey'];

const HIDDEN_COLUMNS = ['pivot', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token', 'content'];

const label = (value = '') =>
    String(value)
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());

const pretty = (value) => {
    if (value === null || value === undefined || value === '') return '—';
    if (typeof value === 'boolean') return value ? 'Có' : 'Không';
    if (typeof value === 'object') return Array.isArray(value) ? `${value.length} mục` : value.name || value.title || value.label || '';
    return String(value);
};

function DataTable({ name, items }) {
    const rows = Array.isArray(items) ? items : items?.data || [];
    if (!rows.length) return null;

    const columns = Object.keys(rows[0] || {})
        .filter((key) => !HIDDEN_COLUMNS.includes(key))
        .slice(0, 6);

    return (
        <Card title={label(name)} description={`${rows.length} bản ghi`}>
            <div className="ui-table-wrap">
                <table className="ui-table">
                    <thead>
                        <tr>
                            {columns.map((column) => (
                                <th key={column}>{label(column)}</th>
                            ))}
                        </tr>
                    </thead>
                    <tbody>
                        {rows.map((row, index) => (
                            <tr key={row.id ?? index}>
                                {columns.map((column) => (
                                    <td key={column}>{pretty(row[column])}</td>
                                ))}
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </Card>
    );
}

export default function GenericPage() {
    const { component, props } = usePage();

    const isAdmin = component.startsWith('Admin/');
    const isAuth = component.startsWith('Auth/');
    const title = TITLE_MAP[component] || props.title || label(component.split('/').pop());

    const tables = useMemo(
        () =>
            Object.entries(props).filter(
                ([key, value]) => !HIDDEN_KEYS.includes(key) && value && typeof value === 'object'
            ),
        [props]
    );

    const notice = (
        <div className="ui-notice">
            <strong>Trang đang dùng giao diện tạm.</strong> Component <code>{component}</code> chưa được xây dựng.
            Dữ liệu bên dưới là dữ liệu thật từ backend.
        </div>
    );

    if (isAdmin) {
        return (
            <AdminLayout title={title} description="Quản lý và cập nhật nội dung website.">
                {notice}
                {tables.length === 0 ? (
                    <EmptyState title="Chưa có dữ liệu hiển thị" />
                ) : (
                    <div className="generic-grid">
                        {tables.map(([key, value]) => (
                            <DataTable key={key} name={key} items={value} />
                        ))}
                    </div>
                )}
            </AdminLayout>
        );
    }

    if (isAuth) {
        return (
            <AuthLayout title={title}>
                <p>Vui lòng sử dụng biểu mẫu đăng nhập của hệ thống.</p>
            </AuthLayout>
        );
    }

    return (
        <AppLayout>
            <Seo title={title} />
            <section className="page-hero">
                <div className="container">
                    <div className="eyebrow">
                        RUNG ADVENTURE <span /> EXPLORE MORE
                    </div>
                    <h1>{title}</h1>
                    <p>Những hành trình đáng nhớ bắt đầu từ đây.</p>
                </div>
            </section>

            <main className="container generic-content">
                {notice}
                {tables.length === 0 ? (
                    <EmptyState title="Chưa có dữ liệu hiển thị" />
                ) : (
                    tables.map(([key, value]) => <DataTable key={key} name={key} items={value} />)
                )}
            </main>
        </AppLayout>
    );
}
