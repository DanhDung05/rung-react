import React from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeft, Calendar } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Badge from '../../../Components/UI/Badge';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

export default function ReleaseShow({ entry, currentVersion = '' }) {
    return (
        <AdminLayout
            title={`Bản ${entry.label}`}
            description={entry.title}
            breadcrumbs={[
                { label: 'Quản trị' },
                { label: 'Lịch sử cập nhật', href: routes.admin.releases.index },
                { label: entry.label },
            ]}
            actions={<Button href={routes.admin.releases.index} variant="outline" icon={<ArrowLeft size={15} />}>Quay lại</Button>}
        >
            <Card>
                <div className="release-detail__meta">
                    <Badge tone="muted">{entry.label}</Badge>
                    {entry.version === currentVersion && <Badge tone="success">Hiện tại</Badge>}
                    <span><Calendar size={14} /> {entry.date}</span>
                </div>

                <h2 className="release-detail__title">{entry.title}</h2>

                <div className="release-detail__content">
                    {entry.groups.map((group) => (
                        <section key={group.key}>
                            <h3>{group.label}</h3>
                            <ul>
                                {group.items.map((item, index) => <li key={index}>{item}</li>)}
                            </ul>
                        </section>
                    ))}
                </div>

                <p className="ui-hint">Nội dung lấy từ CHANGELOG.md; muốn thay đổi hãy sửa file và commit.</p>
            </Card>
        </AdminLayout>
    );
}
