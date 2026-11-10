import React from 'react';
import { Link, router } from '@inertiajs/react';
import { Eye, Mail } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Badge from '../../../Components/UI/Badge';
import Pagination from '../../../Components/UI/Pagination';
import DataTable from '../../../Components/Data/DataTable';
import FilterBar from '../../../Components/Data/FilterBar';
import { routes } from '../../../lib/routes';
import { datetime } from '../../../lib/format';
import { CONTACT_STATUS } from '../../../lib/constants';

export default function ContactsIndex({ contacts, newCount = 0 }) {
    const rows = contacts?.data || [];

    return (
        <AdminLayout
            title="Hộp thư liên hệ"
            description={newCount > 0 ? `Có ${newCount} liên hệ chưa đọc.` : 'Tất cả liên hệ đã được xử lý.'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Liên hệ' }]}
            actions={newCount > 0 ? <Badge tone="warning">{newCount} chưa đọc</Badge> : null}
        >
            <Card>
                <FilterBar
                    url={routes.admin.contacts.index}
                    values={Object.fromEntries(new URLSearchParams(window.location.search))}
                    searchPlaceholder="Tìm theo tên, email, tiêu đề…"
                    filters={[
                        {
                            name: 'status',
                            label: 'Trạng thái',
                            options: Object.entries(CONTACT_STATUS).map(([value, meta]) => ({ value, label: meta.label })),
                        },
                    ]}
                />

                <DataTable
                    empty="Chưa có liên hệ nào"
                    rows={rows}
                    columns={[
                        {
                            key: 'name',
                            label: 'Người gửi',
                            render: (contact) => (
                                <div>
                                    <Link href={routes.admin.contacts.show(contact.id)} className="ui-link">
                                        {contact.name}
                                    </Link>
                                    <div className="ui-sub">{contact.email}</div>
                                </div>
                            ),
                        },
                        { key: 'phone', label: 'Điện thoại', render: (contact) => contact.phone || '—' },
                        { key: 'subject', label: 'Tiêu đề', render: (contact) => contact.subject || '—' },
                        {
                            key: 'status',
                            label: 'Trạng thái',
                            render: (contact) => (
                                <Badge tone={CONTACT_STATUS[contact.status]?.tone}>
                                    {CONTACT_STATUS[contact.status]?.label || contact.status}
                                </Badge>
                            ),
                        },
                        { key: 'created_at', label: 'Thời gian', render: (contact) => datetime(contact.created_at) },
                        {
                            key: 'actions',
                            label: 'Thao tác',
                            width: 90,
                            render: (contact) => (
                                <Link href={routes.admin.contacts.show(contact.id)} className="ui-icon-btn" title="Xem chi tiết">
                                    <Eye size={15} />
                                </Link>
                            ),
                        },
                    ]}
                />
                <Pagination links={contacts?.links} from={contacts?.from} to={contacts?.to} total={contacts?.total} />
            </Card>

            <p className="admin-note">
                <Mail size={14} /> Xem chi tiết sẽ tự động đánh dấu liên hệ là “Đã đọc”.
            </p>
        </AdminLayout>
    );
}
