import React from 'react';
import { useForm } from '@inertiajs/react';
import { Plus, Trash2, ArrowUp, ArrowDown } from 'lucide-react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Select from '../../../../Components/UI/Select';
import Textarea from '../../../../Components/UI/Textarea';
import ImageField from '../../../../Components/Form/ImageField';
import RichTextEditor from '../../../../Components/Form/RichTextEditor';
import SeoFields from '../../../../Components/Form/SeoFields';
import StatusField from '../../../../Components/Form/StatusField';
import SlugField from '../../../../Components/Form/SlugField';
import { routes } from '../../../../lib/routes';
import { mediaUrl } from '../../../../lib/format';
import { DIFFICULTY } from '../../../../lib/constants';

const emptyItinerary = () => ({
    day_number: '',
    title: '',
    description: '',
    distance_km: '',
    altitude: '',
    meals: '',
    accommodation: '',
});

export default function TourForm({ tour = null, categories = [], destinations = [] }) {
    const isEdit = !!tour;

    const { data, setData, post, put, processing, errors } = useForm({
        category_id: tour?.category_id ?? '',
        name: tour?.name ?? '',
        slug: tour?.slug ?? '',
        excerpt: tour?.excerpt ?? '',
        content: tour?.content ?? '',
        featured_image: null,
        gallery: [],
        duration_days: tour?.duration_days ?? '',
        duration_nights: tour?.duration_nights ?? '',
        distance_km: tour?.distance_km ?? '',
        altitude_min: tour?.altitude_min ?? '',
        altitude_max: tour?.altitude_max ?? '',
        difficulty: tour?.difficulty ?? '',
        price_reference: tour?.price_reference ?? '',
        start_point: tour?.start_point ?? '',
        end_point: tour?.end_point ?? '',
        best_time: tour?.best_time ?? '',
        notes: tour?.notes ?? '',
        meta_title: tour?.meta_title ?? '',
        meta_description: tour?.meta_description ?? '',
        is_featured: !!tour?.is_featured,
        status: tour?.status ?? 'draft',
        published_at: tour?.published_at ?? '',
        sort_order: tour?.sort_order ?? 0,
        destination_ids: (tour?.destinations || []).map((item) => item.id),
        itineraries: (tour?.itineraries || []).map((item) => ({
            day_number: item.day_number ?? '',
            title: item.title ?? '',
            description: item.description ?? '',
            distance_km: item.distance_km ?? '',
            altitude: item.altitude ?? '',
            meals: item.meals ?? '',
            accommodation: item.accommodation ?? '',
        })),
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();

        if (isEdit) {
            put(routes.admin.tours.update(tour.id), { forceFormData: true });
        } else {
            post(routes.admin.tours.store, { forceFormData: true });
        }
    };

    const updateItinerary = (index, key, value) => {
        const next = data.itineraries.map((item, i) => (i === index ? { ...item, [key]: value } : item));
        setData('itineraries', next);
    };

    const addItinerary = () => setData('itineraries', [...data.itineraries, emptyItinerary()]);

    const removeItinerary = (index) =>
        setData('itineraries', data.itineraries.filter((_, i) => i !== index));

    const moveItinerary = (index, direction) => {
        const target = index + direction;
        if (target < 0 || target >= data.itineraries.length) return;
        const next = [...data.itineraries];
        [next[index], next[target]] = [next[target], next[index]];
        setData('itineraries', next);
    };

    const gallery = (tour?.media || []).filter((item) => item.collection === 'gallery');

    return (
        <AdminLayout
            title={isEdit ? `Sửa tour: ${tour.name}` : 'Thêm tour mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Tour', href: routes.admin.tours.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.tours.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate>
                <div className="admin-form-layout">
                    <div>
                        <Card title="Thông tin cơ bản">
                            <div className="ui-form-grid">
                                <Select
                                    label="Danh mục"
                                    name="category_id"
                                    required
                                    value={data.category_id}
                                    options={categories.map((category) => ({ value: category.id, label: category.name }))}
                                    error={errors.category_id}
                                    placeholder="— Chọn danh mục —"
                                    onChange={(event) => set('category_id', event.target.value)}
                                />
                                <Input
                                    label="Tên tour"
                                    name="name"
                                    required
                                    value={data.name}
                                    error={errors.name}
                                    onChange={(event) => set('name', event.target.value)}
                                />
                            </div>

                            <SlugField
                                value={data.slug}
                                source={data.name}
                                error={errors.slug}
                                onChange={(value) => set('slug', value)}
                            />

                            <Textarea
                                label="Mô tả ngắn"
                                name="excerpt"
                                rows={3}
                                value={data.excerpt}
                                error={errors.excerpt}
                                onChange={(event) => set('excerpt', event.target.value)}
                            />

                            <RichTextEditor
                                label="Nội dung chi tiết"
                                value={data.content}
                                error={errors.content}
                                uploadUrl={routes.admin.uploadImage}
                                onChange={(value) => set('content', value)}
                            />
                        </Card>

                        <Card title="Thông số hành trình">
                            <div className="ui-form-grid ui-form-grid--3">
                                <Input label="Số ngày" name="duration_days" type="number" min="0" value={data.duration_days} error={errors.duration_days} onChange={(e) => set('duration_days', e.target.value)} />
                                <Input label="Số đêm" name="duration_nights" type="number" min="0" value={data.duration_nights} error={errors.duration_nights} onChange={(e) => set('duration_nights', e.target.value)} />
                                <Input label="Quãng đường (km)" name="distance_km" type="number" step="0.01" min="0" value={data.distance_km} error={errors.distance_km} onChange={(e) => set('distance_km', e.target.value)} />
                                <Input label="Độ cao thấp nhất (m)" name="altitude_min" type="number" min="0" value={data.altitude_min} error={errors.altitude_min} onChange={(e) => set('altitude_min', e.target.value)} />
                                <Input label="Độ cao cao nhất (m)" name="altitude_max" type="number" min="0" value={data.altitude_max} error={errors.altitude_max} onChange={(e) => set('altitude_max', e.target.value)} />
                                <Select
                                    label="Độ khó"
                                    name="difficulty"
                                    value={data.difficulty}
                                    error={errors.difficulty}
                                    placeholder="— Chọn —"
                                    options={Object.entries(DIFFICULTY).map(([value, label]) => ({ value, label }))}
                                    onChange={(e) => set('difficulty', e.target.value)}
                                />
                                <Input label="Giá tham khảo (VNĐ)" name="price_reference" type="number" min="0" value={data.price_reference} error={errors.price_reference} onChange={(e) => set('price_reference', e.target.value)} />
                                <Input label="Điểm khởi hành" name="start_point" value={data.start_point} error={errors.start_point} onChange={(e) => set('start_point', e.target.value)} />
                                <Input label="Điểm kết thúc" name="end_point" value={data.end_point} error={errors.end_point} onChange={(e) => set('end_point', e.target.value)} />
                                <Input label="Thời điểm đẹp nhất" name="best_time" value={data.best_time} error={errors.best_time} onChange={(e) => set('best_time', e.target.value)} />
                            </div>

                            <Textarea
                                label="Lưu ý / ghi chú"
                                name="notes"
                                rows={4}
                                value={data.notes}
                                error={errors.notes}
                                onChange={(event) => set('notes', event.target.value)}
                            />
                        </Card>

                        <Card
                            title="Lịch trình theo ngày"
                            description="Thêm, xóa và sắp xếp thứ tự các ngày."
                            actions={<Button type="button" size="sm" variant="outline" icon={<Plus size={14} />} onClick={addItinerary}>Thêm ngày</Button>}
                        >
                            {data.itineraries.length === 0 && (
                                <p className="ui-hint">Chưa có lịch trình. Bấm “Thêm ngày” để tạo.</p>
                            )}

                            {data.itineraries.map((item, index) => (
                                <div className="ui-repeater" key={index}>
                                    <div className="ui-repeater__head">
                                        <strong>Ngày {index + 1}</strong>
                                        <div className="ui-repeater__actions">
                                            <button type="button" className="ui-icon-btn" onClick={() => moveItinerary(index, -1)} aria-label="Lên"><ArrowUp size={14} /></button>
                                            <button type="button" className="ui-icon-btn" onClick={() => moveItinerary(index, 1)} aria-label="Xuống"><ArrowDown size={14} /></button>
                                            <button type="button" className="ui-icon-btn ui-icon-btn--danger" onClick={() => removeItinerary(index)} aria-label="Xóa"><Trash2 size={14} /></button>
                                        </div>
                                    </div>

                                    <div className="ui-form-grid ui-form-grid--3">
                                        <Input label="Ngày số" type="number" min="1" value={item.day_number} error={errors[`itineraries.${index}.day_number`]} onChange={(e) => updateItinerary(index, 'day_number', e.target.value)} />
                                        <Input label="Tiêu đề" value={item.title} error={errors[`itineraries.${index}.title`]} onChange={(e) => updateItinerary(index, 'title', e.target.value)} />
                                        <Input label="Quãng đường (km)" type="number" step="0.01" value={item.distance_km} error={errors[`itineraries.${index}.distance_km`]} onChange={(e) => updateItinerary(index, 'distance_km', e.target.value)} />
                                        <Input label="Độ cao (m)" type="number" value={item.altitude} error={errors[`itineraries.${index}.altitude`]} onChange={(e) => updateItinerary(index, 'altitude', e.target.value)} />
                                        <Input label="Bữa ăn" value={item.meals} error={errors[`itineraries.${index}.meals`]} onChange={(e) => updateItinerary(index, 'meals', e.target.value)} />
                                        <Input label="Lưu trú" value={item.accommodation} error={errors[`itineraries.${index}.accommodation`]} onChange={(e) => updateItinerary(index, 'accommodation', e.target.value)} />
                                    </div>

                                    <Textarea label="Mô tả" rows={3} value={item.description} error={errors[`itineraries.${index}.description`]} onChange={(e) => updateItinerary(index, 'description', e.target.value)} />
                                </div>
                            ))}
                        </Card>

                        <Card title="SEO">
                            <SeoFields data={data} errors={errors} onChange={set} />
                        </Card>
                    </div>

                    <aside>
                        <Card title="Xuất bản">
                            <StatusField data={data} errors={errors} onChange={set} />
                            <Button type="submit" loading={processing} className="ui-btn--block">
                                {isEdit ? 'Lưu thay đổi' : 'Tạo tour'}
                            </Button>
                        </Card>

                        <Card title="Ảnh đại diện">
                            <ImageField
                                label="Ảnh chính"
                                name="featured_image"
                                file={data.featured_image}
                                currentUrl={mediaUrl(tour?.featured_image)}
                                error={errors.featured_image}
                                onChange={(file) => set('featured_image', file)}
                            />
                        </Card>

                        <Card title="Thư viện ảnh">
                            {gallery.length > 0 && (
                                <div className="ui-gallery-grid">
                                    {gallery.map((item) => (
                                        <img key={item.id} src={mediaUrl(item.file_path)} alt={item.alt_text || ''} />
                                    ))}
                                </div>
                            )}
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                onChange={(event) => set('gallery', Array.from(event.target.files || []))}
                            />
                            <p className="ui-hint">Tối đa 20 ảnh, mỗi ảnh ≤ 10MB. Ảnh cũ giữ nguyên, ảnh mới được thêm vào.</p>
                            {errors.gallery && <span className="ui-error">{errors.gallery}</span>}
                        </Card>

                        <Card title="Điểm đến liên quan">
                            <div className="ui-field">
                                <label className="ui-label" htmlFor="destination-ids">Chọn điểm đến</label>
                                <select
                                    id="destination-ids"
                                    multiple
                                    className="ui-select"
                                    size={Math.min(8, Math.max(4, destinations.length))}
                                    value={data.destination_ids.map(String)}
                                    onChange={(event) =>
                                        set('destination_ids', Array.from(event.target.selectedOptions).map((option) => option.value))
                                    }
                                >
                                    {destinations.map((destination) => (
                                        <option key={destination.id} value={destination.id}>
                                            {destination.name}
                                        </option>
                                    ))}
                                </select>
                                <span className="ui-hint">Giữ Ctrl/Cmd để chọn nhiều.</span>
                            </div>
                        </Card>
                    </aside>
                </div>
            </form>
        </AdminLayout>
    );
}
