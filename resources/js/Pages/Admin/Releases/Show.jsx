import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeft, Pencil, Calendar, User, GitBranch } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Badge from '../../../Components/UI/Badge';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

const CATEGORY_TONES = { feature: 'success', improvement: 'info', fix: 'warning', security: 'danger' };

export default function ReleaseShow({ release, currentVersion = '' }) {
    const lines = String(release.content || '').split('\n');

    return (
        <AdminLayout
            title={`Bản ${release.version}`}
            description={release.title}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Lịch sử cập nhật', href: routes.admin.releases.index }, { label: release.version }]}
            actions={<>
                <Button href={routes.admin.releases.index} variant="outline" icon={<ArrowLeft size={15} />}>Quay lại</Button>
                <Button href={routes.admin.releases.edit(release.id)} icon={<Pencil size={15} />}>Sửa</Button>
            </>}
        >
            <Card>
                <div className="release-detail__meta">
                    <Badge tone={release.status === 'released' ? 'success' : 'warning'}>{release.status_label}</Badge>
                    <Badge tone={CATEGORY_TONES[release.category] || 'muted'}>{release.category_label}</Badge>
                    <span><Calendar size={14} /> {release.released_at ? new Date(release.released_at).toLocaleDateString('vi-VN') : 'Chưa phát hành'}</span>
                    <span><User size={14} /> {release.author?.name || 'Hệ thống'}</span>
                    {release.previous_version && (
                        <span><GitBranch size={14} /> {release.previous_version} → {release.version}</span>
                    )}
                </div>

                <h2 className="release-detail__title">{release.title}</h2>

                <div className="release-detail__content">
                    {lines.map((line, index) => (
                        line.trim().startsWith('-') ? (
                            <li key={index}>{line.replace(/^\s*-\s*/, '')}</li>
                        ) : line.trim() === '' ? null : (
                            <p key={index}>{line}</p>
                        )
                    ))}
                </div>

                {release.version === currentVersion && (
                    <p className="ui-hint">Đây là phiên bản hiện tại của website.</p>
                )}
            </Card>
        </AdminLayout>
    );
}
