import React, { useMemo, useState } from 'react';
import { router, usePage } from '@inertiajs/react';
import { Search, SlidersHorizontal, ArrowRight } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import Pager from '../../Components/Public/Pager';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';
import { ui } from '../../lib/ui';

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

const PAGE = 'tour.index';

export default function TourIndex({ tours, categories = [], difficulties = {} }) {
    const { ui: uiData } = usePage().props;
    const t = (section, field, fallback = '') => ui(uiData, PAGE, section, field, fallback);

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
            <Seo title="Hành trình — Rừng Adventure" description="Khám phá các hành trình trekking, cắm trại và khám phá thiên nhiên Việt Nam." />

            <PageHero
                eyebrow={t('hero', 'eyebrow', 'Hành trình')}
                title={t('hero', 'title', 'Chọn cung đường của bạn')}
                lead={t('hero', 'lead', '')}
                heroKey="tour"
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: t('hero', 'eyebrow', 'Hành trình') }]}
            />

            <section className="rp-section">
                <div className="rp-container">
                    <div className="rp-filterbar">
                        <label className="rp-field">
                            <span>{t('filters', 'search_label', 'Tìm kiếm')}</span>
                            <div className="rp-field__input">
                                <Search size={16} />
                                <input
                                    type="search"
                                    value={filters.q}
                                    placeholder={t('filters', 'search_placeholder', 'Tên hành trình...')}
                                    onChange={(event) => setFilters({ ...filters, q: event.target.value })}
                                    onKeyDown={(event) => event.key === 'Enter' && apply({})}
                                />
                            </div>
                        </label>

                        <label className="rp-field">
                            <span>{t('filters', 'category_label', 'Nhóm hành trình')}</span>
                            <select value={filters.category} onChange={(event) => apply({ category: event.target.value })}>
                                <option value="">{t('filters', 'category_all', 'Tất cả')}</option>
                                {categories.map((category) => (
                                    <option key={category.id} value={category.slug}>{category.name}</option>
                                ))}
                            </select>
                        </label>

                        <label className="rp-field">
                            <span>{t('filters', 'difficulty_label', 'Độ khó')}</span>
                            <select value={filters.difficulty} onChange={(event) => apply({ difficulty: event.target.value })}>
                                <option value="">{t('filters', 'difficulty_all', 'Tất cả')}</option>
                                {Object.entries(difficulties).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                        </label>

                        <label className="rp-field">
                            <span>{t('filters', 'sort_label', 'Sắp xếp')}</span>
                            <select value={filters.sort} onChange={(event) => apply({ sort: event.target.value })}>
                                <option value="newest">{t('filters', 'sort_newest', 'Mới nhất')}</option>
                                <option value="price_asc">{t('filters', 'sort_price_asc', 'Giá thấp → cao')}</option>
                                <option value="price_desc">{t('filters', 'sort_price_desc', 'Giá cao → thấp')}</option>
                                <option value="popular">{t('filters', 'sort_popular', 'Xem nhiều')}</option>
                            </select>
                        </label>
                    </div>

                    <div className="rp-results-head">
                        <span><SlidersHorizontal size={15} /> {total} {t('results', 'count_suffix', 'hành trình')}</span>
                        {(filters.q || filters.category || filters.difficulty) && (
                            <button type="button" className="rp-link-arrow" onClick={() => apply({ q: '', category: '', difficulty: '' })}>
                                {t('filters', 'reset', 'Xóa bộ lọc')}
                            </button>
                        )}
                    </div>

                    {list.length === 0 ? (
                        <div className="rp-empty">
                            <h3>{t('results', 'empty_title', 'Không tìm thấy hành trình phù hợp')}</h3>
                            <p>{t('results', 'empty_text', 'Thử bỏ bớt bộ lọc hoặc tìm với từ khóa khác.')}</p>
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
                        <div className="rp-eyebrow">{t('cta', 'eyebrow', 'Chưa chắc chắn?')}</div>
                        <h2 className="rp-h2">{t('cta', 'title', '')}</h2>
                    </div>
                    <div className="rp-cta__actions">
                        <a href={routes.contact} className="rp-btn rp-btn--gold">
                            {t('cta', 'button', 'Liên hệ tư vấn')} <ArrowRight size={15} />
                        </a>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
