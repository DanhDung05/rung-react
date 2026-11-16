import React, { useEffect, useRef, useState } from 'react';
import { Link } from '@inertiajs/react';
import {
    BookOpen,
    Compass,
    FileText,
    Images,
    Mail,
    MapPinned,
    Mountain,
    Users,
    Trash2,
    TrendingUp,
    Star,
    Phone,
} from 'lucide-react';
import AdminLayout from '../../Layouts/AdminLayout';
import Seo from '../../Components/Seo';
import Card from '../../Components/UI/Card';
import Badge from '../../Components/UI/Badge';
import EmptyState from '../../Components/UI/EmptyState';
import { routes } from '../../lib/routes';
import { number, datetime, truncate } from '../../lib/format';
import { DIFFICULTY } from '../../lib/constants';

const STAT_CARDS = [
    ['tours_total', 'Tổng số tour', Mountain, routes.admin.tours.index],
    ['posts_total', 'Bài viết', BookOpen, routes.admin.posts.index],
    ['destinations_total', 'Điểm đến', MapPinned, routes.admin.destinations.index],
    ['contacts_new', 'Liên hệ mới', Mail, routes.admin.contacts.index],
    ['users_total', 'Người dùng', Users, routes.admin.users.index],
    ['categories_total', 'Danh mục', Compass, routes.admin.categories.index],
    ['pages_total', 'Trang tĩnh', FileText, routes.admin.pages.index],
    ['sliders_total', 'Slider', Images, routes.admin.sliders.index],
    ['reviews_pending', 'Đánh giá chờ duyệt', Star, routes.admin.reviews.index],
    ['tour_inquiries_new', 'Lead tư vấn tour', Phone, '/admin/lien-he?type=tour_inquiry'],
];

function StatCard({ label, value, icon: Icon, href, hint, pulse = false }) {
    return (
        <Link href={href} className={`stat-card ${pulse ? 'is-pulse' : ''}`.trim()}>
            <span>
                <Icon size={19} />
            </span>
            <div>
                <strong>{number(value)}</strong>
                <small>{label}</small>
                {hint && <em className="stat-card__hint">{hint}</em>}
            </div>
        </Link>
    );
}

function MiniTable({ head, rows, empty }) {
    if (!rows?.length) return <EmptyState title={empty} />;

    return (
        <div className="ui-table-wrap">
            <table className="ui-table">
                <thead>
                    <tr>
                        {head.map((column) => (
                            <th key={column}>{column}</th>
                        ))}
                    </tr>
                </thead>
                <tbody>{rows}</tbody>
            </table>
        </div>
    );
}

export default function Dashboard({
    stats = {},
    trash = {},
    topTours = [],
    topPosts = [],
    tourByDifficulty = {},
    chartContacts = [],
    maxContact = 1,
    latestContacts = [],
    latestTours = [],
    latestPosts = [],
    menus = [],
}) {
    const difficultyRows = Object.entries(tourByDifficulty || {});
    const [pulseKeys, setPulseKeys] = useState([]);
    const previous = useRef({});

    useEffect(() => {
        const keys = ['contacts_new', 'reviews_pending', 'tour_inquiries_new'];
        const changed = keys.filter((key) => previous.current[key] !== undefined && previous.current[key] !== stats[key]);
        previous.current = keys.reduce((carry, key) => ({ ...carry, [key]: stats[key] }), {});

        if (changed.length === 0) return undefined;

        setPulseKeys(changed);
        const timer = window.setTimeout(() => setPulseKeys([]), 1600);

        return () => window.clearTimeout(timer);
    }, [stats]);

    return (
        <AdminLayout title="Tổng quan" description="Theo dõi tình hình hoạt động của website.">
            <Seo title="Tổng quan — Quản trị" noindex />

            <div className="stats-grid">
                {STAT_CARDS.map(([key, label, Icon, href]) => (
                    <StatCard key={key} label={label} value={stats[key] ?? 0} icon={Icon} href={href} pulse={pulseKeys.includes(key)} />
                ))}
            </div>

            <div className="admin-grid-2">
                <Card title="Liên hệ 7 ngày gần nhất" description={`Cao nhất: ${number(maxContact)} liên hệ/ngày`}>
                    {chartContacts.length === 0 ? (
                        <EmptyState title="Chưa có dữ liệu" />
                    ) : (
                        <div className="ui-chart">
                            {chartContacts.map((day) => {
                                const height = Math.max(4, Math.round((Number(day.count) / Number(maxContact || 1)) * 100));
                                return (
                                    <div className="ui-chart__col" key={day.date}>
                                        <span className="ui-chart__value">{day.count}</span>
                                        <div className="ui-chart__track">
                                            <div className="ui-chart__bar" style={{ height: `${height}%` }} />
                                        </div>
                                        <span className="ui-chart__label">{day.date}</span>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </Card>

                <Card title="Phân bố độ khó tour">
                    {difficultyRows.length === 0 ? (
                        <EmptyState title="Chưa có tour" />
                    ) : (
                        <ul className="ui-dist">
                            {difficultyRows.map(([key, total]) => {
                                const sum = difficultyRows.reduce((acc, [, value]) => acc + Number(value), 0) || 1;
                                const percent = Math.round((Number(total) / sum) * 100);
                                return (
                                    <li key={key}>
                                        <div className="ui-dist__head">
                                            <span>{DIFFICULTY[key] || key || 'Chưa xác định'}</span>
                                            <strong>
                                                {number(total)} <small>({percent}%)</small>
                                            </strong>
                                        </div>
                                        <div className="ui-dist__track">
                                            <div className="ui-dist__bar" style={{ width: `${percent}%` }} />
                                        </div>
                                    </li>
                                );
                            })}
                        </ul>
                    )}
                </Card>
            </div>

            <div className="admin-grid-3">
                <Link href={routes.admin.tours.trash} className="ui-alert-card">
                    <Trash2 size={18} />
                    <div>
                        <strong>{number(trash.tours ?? 0)}</strong>
                        <span>Tour trong thùng rác</span>
                    </div>
                </Link>
                <Link href={routes.admin.posts.trash} className="ui-alert-card">
                    <Trash2 size={18} />
                    <div>
                        <strong>{number(trash.posts ?? 0)}</strong>
                        <span>Bài viết trong thùng rác</span>
                    </div>
                </Link>
                <Link href={routes.admin.contacts.index} className="ui-alert-card">
                    <Mail size={18} />
                    <div>
                        <strong>{number(stats.contacts_new ?? 0)}</strong>
                        <span>Liên hệ chưa đọc</span>
                    </div>
                </Link>
            </div>

            <div className="admin-grid-2">
                <Card
                    title="Tour được xem nhiều"
                    actions={<Link href={routes.admin.tours.index}>Quản lý tour →</Link>}
                >
                    <MiniTable
                        head={['Tên tour', 'Lượt xem']}
                        empty="Chưa có tour"
                        rows={topTours.map((tour) => (
                            <tr key={tour.id}>
                                <td>
                                    <Link href={routes.admin.tours.show(tour.id)} className="ui-link">
                                        {truncate(tour.name, 60)}
                                    </Link>
                                </td>
                                <td>
                                    <Badge tone="muted">{number(tour.view_count)}</Badge>
                                </td>
                            </tr>
                        ))}
                    />
                </Card>

                <Card
                    title="Bài viết được xem nhiều"
                    actions={<Link href={routes.admin.posts.index}>Quản lý bài viết →</Link>}
                >
                    <MiniTable
                        head={['Tiêu đề', 'Lượt xem']}
                        empty="Chưa có bài viết"
                        rows={topPosts.map((post) => (
                            <tr key={post.id}>
                                <td>
                                    <Link href={routes.admin.posts.show(post.id)} className="ui-link">
                                        {truncate(post.title, 60)}
                                    </Link>
                                </td>
                                <td>
                                    <Badge tone="muted">{number(post.view_count)}</Badge>
                                </td>
                            </tr>
                        ))}
                    />
                </Card>
            </div>

            <div className="admin-grid-3">
                <Card title="Liên hệ mới nhất" actions={<Link href={routes.admin.contacts.index}>Tất cả →</Link>}>
                    {latestContacts.length === 0 ? (
                        <EmptyState title="Chưa có liên hệ" />
                    ) : (
                        <ul className="ui-feed">
                            {latestContacts.map((contact) => (
                                <li key={contact.id}>
                                    <Link href={routes.admin.contacts.show(contact.id)}>
                                        <strong>{contact.name}</strong>
                                        <span>{truncate(contact.subject || contact.message, 50)}</span>
                                        <small>{datetime(contact.created_at)}</small>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </Card>

                <Card title="Tour mới nhất" actions={<Link href={routes.admin.tours.index}>Tất cả →</Link>}>
                    {latestTours.length === 0 ? (
                        <EmptyState title="Chưa có tour" />
                    ) : (
                        <ul className="ui-feed">
                            {latestTours.map((tour) => (
                                <li key={tour.id}>
                                    <Link href={routes.admin.tours.show(tour.id)}>
                                        <strong>{truncate(tour.name, 50)}</strong>
                                        <span>{tour.category?.name || 'Chưa phân loại'}</span>
                                        <small>{datetime(tour.created_at)}</small>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </Card>

                <Card title="Menu & điều hướng" actions={<Link href={routes.admin.menus.index}>Quản lý →</Link>}>
                    {menus.length === 0 ? (
                        <EmptyState title="Chưa có menu" />
                    ) : (
                        <ul className="ui-feed">
                            {menus.map((menu) => (
                                <li key={menu.id}>
                                    <Link href={routes.admin.menus.items(menu.id)}>
                                        <strong>{menu.name}</strong>
                                        <span>
                                            Vị trí: <code>{menu.location}</code>
                                        </span>
                                        <small>{number(menu.all_items_count)} item</small>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </Card>
            </div>

            <Card title="Bài viết mới nhất" actions={<Link href={routes.admin.posts.index}>Tất cả →</Link>}>
                <MiniTable
                    head={['Tiêu đề', 'Danh mục', 'Ngày tạo']}
                    empty="Chưa có bài viết"
                    rows={latestPosts.map((post) => (
                        <tr key={post.id}>
                            <td>
                                <Link href={routes.admin.posts.show(post.id)} className="ui-link">
                                    {truncate(post.title, 70)}
                                </Link>
                            </td>
                            <td>{post.category?.name || '—'}</td>
                            <td>{datetime(post.created_at)}</td>
                        </tr>
                    ))}
                />
            </Card>

            <p className="admin-note">
                <TrendingUp size={14} /> Số liệu lấy trực tiếp từ database, không hardcode.
            </p>
        </AdminLayout>
    );
}
