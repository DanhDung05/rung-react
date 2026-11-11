import React, { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import { Search } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import DestinationCard from '../../Components/Public/DestinationCard';
import Pager from '../../Components/Public/Pager';
import Reveal from '../../Components/Public/Reveal';
import { routes } from '../../lib/routes';

const initialFilters = () => {
    if (typeof window === 'undefined') return { q: '', region: '', sort: 'default' };
    const params = new URLSearchParams(window.location.search);
    return {
        q: params.get('q') || '',
        region: params.get('region') || '',
        sort: params.get('sort') || 'default',
    };
};

export default function DestinationIndex({ destinations, regions = [] }) {
    const [filters, setFilters] = useState(initialFilters);

    const apply = (patch) => {
        const next = { ...filters, ...patch };
        setFilters(next);
        router.get(routes.destinations.index, Object.fromEntries(Object.entries(next).filter(([, value]) => value !== '')), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    };

    const list = destinations?.data || [];
    const total = useMemo(() => destinations?.total ?? list.length, [destinations, list.length]);

    return (
        <AppLayout>
            <Seo
                title="Điểm đến — Rừng Adventure"
                description="Khám phá các điểm đến thiên nhiên nổi bật của Việt Nam cùng Rừng Adventure."
            />

            <PageHero
                eyebrow="Điểm đến"
                title="Nơi rừng gọi tên bạn"
                lead="Từ núi rừng phía Bắc đến cao nguyên và biển đảo phía Nam — chọn vùng đất cho hành trình tiếp theo."
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Điểm đến' }]}
            />

            <section className="rp-section">
                <div className="rp-container">
                    <div className="rp-filterbar rp-filterbar--3">
                        <label className="rp-field">
                            <span>Tìm kiếm</span>
                            <div className="rp-field__input">
                                <Search size={16} />
                                <input
                                    type="search"
                                    value={filters.q}
                                    placeholder="Tên điểm đến, tỉnh thành..."
                                    onChange={(event) => setFilters({ ...filters, q: event.target.value })}
                                    onKeyDown={(event) => event.key === 'Enter' && apply({})}
                                />
                            </div>
                        </label>

                        <label className="rp-field">
                            <span>Khu vực</span>
                            <select value={filters.region} onChange={(event) => apply({ region: event.target.value })}>
                                <option value="">Tất cả khu vực</option>
                                {regions.map((region) => (
                                    <option key={region} value={region}>{region}</option>
                                ))}
                            </select>
                        </label>

                        <label className="rp-field">
                            <span>Sắp xếp</span>
                            <select value={filters.sort} onChange={(event) => apply({ sort: event.target.value })}>
                                <option value="default">Mặc định</option>
                                <option value="featured">Nổi bật</option>
                                <option value="name_asc">Tên A → Z</option>
                                <option value="name_desc">Tên Z → A</option>
                            </select>
                        </label>
                    </div>

                    <div className="rp-results-head">
                        <span>{total} điểm đến</span>
                        {(filters.q || filters.region) && (
                            <button type="button" className="rp-link-arrow" onClick={() => apply({ q: '', region: '' })}>
                                Xóa bộ lọc
                            </button>
                        )}
                    </div>

                    {list.length === 0 ? (
                        <div className="rp-empty">
                            <h3>Không tìm thấy điểm đến phù hợp</h3>
                            <p>Thử bỏ bớt bộ lọc hoặc tìm với từ khóa khác.</p>
                        </div>
                    ) : (
                        <Reveal>
                            <div className="rp-dest-grid">
                                {list.map((destination) => <DestinationCard key={destination.id} destination={destination} />)}
                            </div>
                        </Reveal>
                    )}

                    <Pager links={destinations?.links || []} />
                </div>
            </section>
        </AppLayout>
    );
}
