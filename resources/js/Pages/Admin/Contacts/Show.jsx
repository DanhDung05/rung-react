import React from 'react';
import { Link, router } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';
import { CONTACT_STATUS } from '../../../lib/constants';

const NEXT_STATUSES = [
    ['read', 'Đã đọc', 'outline'],
    ['replied', 'Đã trả lời', 'primary'],
    ['archived', 'Lưu trữ', 'ghost'],
    ['new', 'Đánh dấu chưa đọc', 'ghost'],
];

export default function ContactsShow({ contact }) {
    const update = (status) => {
        router.patch(routes.admin.contactStatus(contact.id), { status }, { preserveScroll: true });
    };

    return (
        <AdminLayout
            title={`Liên hệ từ ${contact.name}`}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Liên hệ', href: routes.admin.contacts.index }, { label: 'Chi tiết' }]}
            actions={<Button href={routes.admin.contacts.index} variant="outline" icon={<ArrowLeft size={15} />}>Hộp thư</Button>}
        >
            <div className="admin-form-layout">
                <div>
                    <Card
                        title={contact.subject || '(Không tiêu đề)'}
                        description={datetime(contact.created_at)}
                        actions={
                            <Badge tone={CONTACT_STATUS[contact.status]?.tone}>
                                {CONTACT_STATUS[contact.status]?.label || contact.status}
                            </Badge>
                        }
                    >
                        <div className="ui-message">{contact.message}</div>

                        <div className="ui-qa">
                            <a className="ui-btn ui-btn--dark ui-btn--sm" href={`mailto:${contact.email}?subject=${encodeURIComponent(`Re: ${contact.subject || 'Liên hệ Rung Adventure'}`)}`}>
                            Trả lời qua email
                            </a>
                            {contact.phone && (
                                <a className="ui-btn ui-btn--outline ui-btn--sm" href={`tel:${contact.phone}`}>
                                    Gọi {contact.phone}
                                </a>
                            )}
                        </div>
                    </Card>
                </div>

                <aside>
                    <Card title="Thông tin người gửi">
                        <div className="ui-detail-row"><span>Họ tên</span><div>{contact.name}</div></div>
                        <div className="ui-detail-row"><span>Email</span><div><a className="ui-link" href={`mailto:${contact.email}`}>{contact.email}</a></div></div>
                        <div className="ui-detail-row"><span>Điện thoại</span><div>{contact.phone || '—'}</div></div>
                        <div className="ui-detail-row"><span>IP</span><div><code>{contact.ip_address || '—'}</code></div></div>
                        <div className="ui-detail-row"><span>Thiết bị</span><div className="ui-ua">{contact.user_agent || '—'}</div></div>
                    </Card>

                    <Card title="Cập nhật trạng thái">
                        <div className="ui-stack">
                            {NEXT_STATUSES.filter(([status]) => status !== contact.status).map(([status, label, variant]) => (
                                <Button key={status} variant={variant} size="sm" onClick={() => update(status)} className="ui-btn--block">
                                    {label}
                                </Button>
                            ))}
                        </div>
                    </Card>
                </aside>
            </div>
        </AdminLayout>
    );
}
