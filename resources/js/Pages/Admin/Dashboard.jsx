import React from 'react';
import {
    BookOpen,
    Compass,
    FileText,
    Images,
    Mail,
    MapPinned,
    Mountain,
    Users,
} from 'lucide-react';
import AdminLayout from '../../Layouts/AdminLayout';
import Seo from '../../Components/Seo';
import Card from '../../Components/UI/Card';
import Badge from '../../Components/UI/Badge';
import EmptyState from '../../Components/UI/EmptyState';
import { Link } from '@inertiajs/react';
import { routes } from '../../lib/routes';
import { number } from '../../lib/format';

const CARDS = [
    ['tours_total', 'Tổng số tour', Mountain],
    ['posts_total', 'Bài viết', BookOpen],
    ['destinations_total', 'Điểm đến', MapPinned],
    ['contacts_new', 'Liên hệ mới', Mail],
    ['users_total', 'Người dùng', Users],
    ['categories_total', 'Danh mục', Compass],
    ['pages_total', 'Trang tĩnh', FileText],
    ['sliders_total', 'Slider', Images],
];

export default function Dashboard({ stats = {}, topTours = [], topPosts = [] }) {
    return (
        <AdminLayout title="Tổng quan" description="Theo dõi tình hình hoạt động của website.">
            <Seo title="Tổng quan — Quản trị" noindex />

            <div className="stats-grid">
                {CARDS.map(([key, name, Icon]) => (
                    <div className="stat-card" key={key}>
                        <span>
                            <Icon size={19} />
                        </span>
                        <div>
                            <strong>{number(stats[key] ?? 0)}</strong>
                            <small>{name}</small>
                        </div>
                    </div>
                ))}
            </div>

            <div className="dashboard-lower">
                <Card
                    title="Tour được xem nhiều"
                    actions={<Link href={routes.admin.tours.index}>Quản lý tour →</Link>}
                >
                    {topTours.length === 0 ? (
                        <EmptyState title="Chưa có dữ liệu" />
                    ) : (
                        <div className="ui-table-wrap">
                            <table className="ui-table">
                                <thead>
                                    <tr>
                                        <th>Tên tour</th>
                                        <th>Lượt xem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {topTours.map((tour) => (
                                        <tr key={tour.id}>
                                            <td>{tour.name}</td>
                                            <td>
                                                <Badge tone="muted">{number(tour.view_count)}</Badge>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </Card>

                <Card
                    title="Bài viết được xem nhiều"
                    actions={<Link href={routes.admin.posts.index}>Quản lý bài viết →</Link>}
                >
                    {topPosts.length === 0 ? (
                        <EmptyState title="Chưa có dữ liệu" />
                    ) : (
                        <div className="ui-table-wrap">
                            <table className="ui-table">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Lượt xem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {topPosts.map((post) => (
                                        <tr key={post.id}>
                                            <td>{post.title}</td>
                                            <td>
                                                <Badge tone="muted">{number(post.view_count)}</Badge>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </Card>
            </div>
        </AdminLayout>
    );
}
