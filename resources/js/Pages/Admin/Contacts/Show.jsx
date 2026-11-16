import React, { useEffect, useState } from 'react';
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
    const [status, setStatus] = useState(contact.status);

    useEffect(() => {
        setStatus(contact.status);
    }, [contact.id, contact.status]);

    useEffect(() => {
        const onStatus = (event) => {
            if (Number(event.detail?.id) === Number(contact.id)) {
                setStatus(event.detail.status);
            }
        };

        window.addEventListener('admin:contact-status', onStatus);

        return () => window.removeEventListener('admin:contact-status', onStatus);
    }, [contact.id]);

    const update = (nextStatus) => {
        setStatus(nextStatus);
        router.patch(routes.admin.contactStatus(contact.id), { status: nextStatus }, { preserveScroll: true });
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
                            <Badge tone={CONTACT_STATUS[status]?.tone}>
                                {CONTACT_STATUS[status]?.label || status}
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
                        <div className="ui-detail-row"><span>Điện thoại</span><div>{contact.phone ? <a className="ui-link" href={`tel:${contact.phone}`}>{contact.phone}</a> : '—'}</div></div>
                        <div className="ui-detail-row"><span>Loại</span><div><Badge tone={contact.type === 'tour_inquiry' ? 'gold' : 'muted'}>{contact.type === 'tour_inquiry' ? 'Tư vấn tour' : 'Liên hệ chung'}</Badge></div></div>
                        <div className="ui-detail-row"><span>IP</span><div><code>{contact.ip_address || '—'}</code></div></div>
                        <div className="ui-detail-row"><span>Thiết bị</span><div className="ui-ua">{contact.user_agent || '—'}</div></div>
                    </Card>

                    {contact.type === 'tour_inquiry' && (
                        <Card title="Thông tin tư vấn tour">
                            <div className="ui-detail-row"><span>Tour</span><div>{contact.tour ? <Link className="ui-link" href={routes.admin.tours.show(contact.tour.id)}>{contact.tour.name}</Link> : '—'}</div></div>
                            <div className="ui-detail-row"><span>Số người</span><div>{contact.people_count || '—'}</div></div>
                            <div className="ui-detail-row"><span>Ngày dự kiến</span><div>{contact.preferred_date ? new Date(contact.preferred_date).toLocaleDateString('vi-VN') : '—'}</div></div>
                        </Card>
                    )}

                    <Card title="Cập nhật trạng thái">
                        <div className="ui-stack">
                            {NEXT_STATUSES.filter(([nextStatus]) => nextStatus !== status).map(([nextStatus, label, variant]) => (
                                <Button key={nextStatus} variant={variant} size="sm" onClick={() => update(nextStatus)} className="ui-btn--block">
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
