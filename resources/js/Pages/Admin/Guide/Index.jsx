import React, { useMemo, useState } from 'react';
import { Search, ChevronRight } from 'lucide-react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';

export default function GuideIndex({ guide = {} }) {
    const [keyword, setKeyword] = useState('');

    const categories = useMemo(() => Object.entries(guide), []);

    const filtered = useMemo(() => {
        const term = keyword.trim().toLowerCase();

        return categories
            .map(([key, category]) => ({
                key,
                label: category.label,
                items: category.items.filter((item) => {
                    if (!term) return true;
                    return `${item.title} ${item.purpose} ${item.path} ${(item.steps || []).join(' ')}`
                        .toLowerCase()
                        .includes(term);
                }),
            }))
            .filter((category) => category.items.length > 0);
    }, [categories, keyword]);

    return (
        <AdminLayout
            title="Hướng dẫn sử dụng"
            description="Tra cứu nhanh cách dùng từng chức năng Admin và tác động của nó tới website."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Hướng dẫn sử dụng' }]}
        >
            <div className="guide-layout">
                <aside className="guide-nav">
                    <div className="guide-search">
                        <Search size={16} />
                        <input
                            type="search"
                            value={keyword}
                            onChange={(event) => setKeyword(event.target.value)}
                            placeholder="Tìm chức năng, đường dẫn..."
                        />
                    </div>

                    {filtered.map((category) => (
                        <div className="guide-nav__group" key={category.key}>
                            <div className="guide-nav__label">{category.label}</div>
                            {category.items.map((item) => (
                                <a key={item.key} href={`#${item.key}`} className="guide-nav__link">
                                    <ChevronRight size={13} /> {item.title}
                                </a>
                            ))}
                        </div>
                    ))}

                    {filtered.length === 0 && <p className="ui-hint">Không tìm thấy hướng dẫn phù hợp.</p>}
                </aside>

                <div className="guide-content">
                    {filtered.map((category) => (
                        <section key={category.key}>
                            <h2 className="guide-category">{category.label}</h2>

                            {category.items.map((item) => (
                                <Card key={item.key} title={item.title} description={item.purpose}>
                                    <div id={item.key} className="guide-item">
                                        <p><strong>Truy cập:</strong> <code>{item.path}</code></p>

                                        <strong>Các bước thao tác</strong>
                                        <ol>
                                            {item.steps.map((step, index) => <li key={index}>{step}</li>)}
                                        </ol>

                                        <p><strong>Thay đổi điều gì:</strong> {item.effect}</p>
                                        <p><strong>Ảnh hưởng website:</strong> {item.public}</p>
                                        <p><strong>Lưu ý:</strong> {item.notes}</p>
                                        <p><strong>Ví dụ:</strong> {item.example}</p>
                                    </div>
                                </Card>
                            ))}
                        </section>
                    ))}
                </div>
            </div>
        </AdminLayout>
    );
}
