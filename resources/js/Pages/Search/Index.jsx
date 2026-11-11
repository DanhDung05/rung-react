import React, { useState } from 'react';
import { router } from '@inertiajs/react';
import { Search as SearchIcon } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import TourCard from '../../Components/Public/TourCard';
import DestinationCard from '../../Components/Public/DestinationCard';
import PostCard from '../../Components/Public/PostCard';
import { routes } from '../../lib/routes';

const TYPES = [
    ['all', 'Tất cả'],
    ['tour', 'Hành trình'],
    ['destination', 'Điểm đến'],
    ['post', 'Bài viết'],
];

export default function SearchIndex({ keyword = '', type = 'all', results = {}, total = 0 }) {
    const [term, setTerm] = useState(keyword);
    const [currentType, setCurrentType] = useState(type);

    const submit = (event) => {
        event.preventDefault();
        router.get(routes.search, { q: term, type: currentType }, { preserveState: true, preserveScroll: true });
    };

    const tours = results.tours || [];
    const destinations = results.destinations || [];
    const posts = results.posts || [];

    return (
        <AppLayout>
            <Seo
                title={keyword ? `Tìm "${keyword}" — Rừng Adventure` : 'Tìm kiếm — Rừng Adventure'}
                description="Tìm kiếm hành trình, điểm đến và bài viết trên Rừng Adventure."
                noindex
            />

            <PageHero
                eyebrow="Tìm kiếm"
                title={keyword ? `Kết quả cho “${keyword}”` : 'Tìm kiếm'}
                lead={keyword ? `${total} kết quả phù hợp.` : 'Nhập từ khóa để tìm hành trình, điểm đến hoặc bài viết.'}
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Tìm kiếm' }]}
            />

            <section className="rp-section">
                <div className="rp-container">
                    <form className="rp-search-form" onSubmit={submit}>
                        <div className="rp-field__input">
                            <SearchIcon size={16} />
                            <input
                                type="search"
                                value={term}
                                onChange={(event) => setTerm(event.target.value)}
                                placeholder="Tìm hành trình, điểm đến, bài viết..."
                            />
                        </div>

                        <select value={currentType} onChange={(event) => setCurrentType(event.target.value)}>
                            {TYPES.map(([value, label]) => (
                                <option key={value} value={value}>{label}</option>
                            ))}
                        </select>

                        <button type="submit" className="rp-btn rp-btn--gold">Tìm kiếm</button>
                    </form>

                    {tours.length > 0 && (
                        <div className="rp-search-group">
                            <h2 className="rp-h3">Hành trình ({tours.length})</h2>
                            <div className="h-tour-grid">
                                {tours.map((tour) => <TourCard key={tour.id} tour={tour} />)}
                            </div>
                        </div>
                    )}

                    {destinations.length > 0 && (
                        <div className="rp-search-group">
                            <h2 className="rp-h3">Điểm đến ({destinations.length})</h2>
                            <div className="rp-dest-grid">
                                {destinations.map((destination) => <DestinationCard key={destination.id} destination={destination} />)}
                            </div>
                        </div>
                    )}

                    {posts.length > 0 && (
                        <div className="rp-search-group">
                            <h2 className="rp-h3">Bài viết ({posts.length})</h2>
                            <div className="rp-blog-grid rp-blog-grid--3">
                                {posts.map((post) => <PostCard key={post.id} post={post} />)}
                            </div>
                        </div>
                    )}

                    {keyword && total === 0 && (
                        <div className="rp-empty">
                            <h3>Không tìm thấy kết quả</h3>
                            <p>Thử từ khóa khác hoặc chọn loại nội dung khác.</p>
                        </div>
                    )}
                </div>
            </section>
        </AppLayout>
    );
}
