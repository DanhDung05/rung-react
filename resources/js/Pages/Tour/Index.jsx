import React, { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import { Search, SlidersHorizontal, ArrowRight } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import Pager from '../../Components/Public/Pager';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';

const initialFilters = () => {
    if (typeof window === 'undefined') return {};
    const params = new URLSearchParams(window.location.search);
    return {
        q: params.get('q') || '',
        category: params.get('category') || '',
        difficulty: params.get('difficulty') || '',
        sort: params.get('sort') || 'newest',
    };
};

export default function TourIndex({ tours, categories = [], difficulties = {} }) {
    const [filters, setFilters] = useState(initialFilters);

    const apply = (patch) => {
        const next = { ...filters, ...patch };
        setFilters(next);
        router.get(routes.tours.index, Object.fromEntries(Object.entries(next).filter(([, value]) => value !== '')), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    };

    const total = useMemo(() => tours?.total ?? tours?.data?.length ?? 0, [tours]);
    const list = tours?.data || [];

    return (
        <AppLayout>
            <Seo
                title="Hành trình — Rừng Adventure"
                description="Khám phá các hành trình trekking, cắm trại và khám phá thiên nhiên Việt Nam."
            />

            <PageHero
                eyebrow="Hành trình"
                title="Chọn cung đường của bạn"
                lead="Từ trekking nhiều ngày đến những chuyến đi trong ngày — lọc theo nhóm, độ khó và ngân sách."
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Hành trình' }]}
            />

            <section className="rp-section">
                <div className="rp-container">
                    <div className="rp-filterbar">
                        <label className="rp-field">
                            <span>Tìm kiếm</span>
                            <div className="rp-field__input">
                                <Search size={16} />
                                <input
                                    type="search"
                                    value={filters.q}
                                    placeholder="Tên hành trình..."
                                    onChange={(event) => setFilters({ ...filters, q: event.target.value })}
                                    onKeyDown={(event) => event.key === 'Enter' && apply({})}
                                />
                            </div>
                        </label>

                        <label className="rp-field">
                            <span>Nhóm hành trình</span>
                            <select value={filters.category} onChange={(event) => apply({ category: event.target.value })}>
                                <option value="">Tất cả</option>
                                {categories.map((category) => (
                                    <option key={category.id} value={category.slug}>{category.name}</option>
                                ))}
                            </select>
                        </label>

                        <label className="rp-field">
                            <span>Độ khó</span>
                            <select value={filters.difficulty} onChange={(event) => apply({ difficulty: event.target.value })}>
                                <option value="">Tất cả</option>
                                {Object.entries(difficulties).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </label>

                        <label className="rp-field">
                            <span>Sắp xếp</span>
                            <select value={filters.sort} onChange={(event) => apply({ sort: event.target.value })}>
                                <option value="newest">Mới nhất</option>
                                <option value="price_asc">Giá thấp → cao</option>
                                <option value="price_desc">Giá cao → thấp</option>
                                <option value="popular">Xem nhiều</option>
                            </select>
                        </label>
                    </div>

                    <div className="rp-results-head">
                        <span><SlidersHorizontal size={15} /> {total} hành trình</span>
                        {(filters.q || filters.category || filters.difficulty) && (
                            <button type="button" className="rp-link-arrow" onClick={() => apply({ q: '', category: '', difficulty: '' })}>
                                Xóa bộ lọc
                            </button>
                        )}
                    </div>

                    {list.length === 0 ? (
                        <div className="rp-empty">
                            <h3>Không tìm thấy hành trình phù hợp</h3>
                            <p>Thử bỏ bớt bộ lọc hoặc tìm với từ khóa khác.</p>
                        </div>
                    ) : (
                        <Reveal>
                            <div className="h-tour-grid">
                                {list.map((tour) => <TourCard key={tour.id} tour={tour} />)}
                            </div>
                        </Reveal>
                    )}

                    <Pager links={tours?.links || []} />
                </div>
            </section>

            <section className="rp-section rp-section--cream">
                <div className="rp-container rp-cta--wide" style={{ background: 'transparent', padding: 0 }}>
                    <div>
                        <div className="rp-eyebrow">Chưa chắc chắn?</div>
                        <h2 className="rp-h2">Để chúng tôi thiết kế hành trình riêng cho bạn</h2>
                    </div>
                    <div className="rp-cta__actions">
                        <a href={routes.contact} className="rp-btn rp-btn--gold">
                            Liên hệ tư vấn <ArrowRight size={15} />
                        </a>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
