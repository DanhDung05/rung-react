import React from 'react';
import { Link } from '@inertiajs/react';
import { Settings, ChevronRight } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

export default function SettingsIndex({ groups = {} }) {
    const entries = Object.entries(groups);

    return (
        <AdminLayout
            title="Cài đặt website"
            description="Chỉ quản trị viên mới có quyền thay đổi cấu hình."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Cài đặt' }]}
        >
            <div className="ui-settings-grid">
                {entries.map(([key, group]) => (
                    <Link key={key} href={routes.admin.settings.edit(key)} className="ui-settings-card">
                        <span className="ui-settings-card__icon">
                            <Settings size={18} />
                        </span>
                        <div>
                            <strong>{group.label || key}</strong>
                            <span>{group.description || '—'}</span>
                            <code>{Object.keys(group.fields || {}).length} trường</code>
                        </div>
                        <ChevronRight size={16} />
                    </Link>
                ))}
            </div>

            {entries.length === 0 && (
                <Card>
                    <p className="ui-hint">Chưa có nhóm cấu hình nào trong <code>config/settings.php</code>.</p>
                </Card>
            )}

            <p className="admin-note">
                <Settings size={14} /> Thay đổi ở đây áp dụng ngay lên giao diện React (menu, màu sắc, thông tin liên hệ).
            </p>

            <div className="ui-form-actions">
                <Button href={routes.admin.dashboard} variant="outline">← Về tổng quan</Button>
            </div>
        </AdminLayout>
    );
}
