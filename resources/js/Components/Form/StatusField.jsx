import React from 'react';
import Input from '../UI/Input';
import Select from '../UI/Select';
import Checkbox from '../UI/Checkbox';

/** ISO / "YYYY-MM-DD HH:mm:ss" → "YYYY-MM-DDTHH:mm" cho input datetime-local */
export function toLocalInput(value) {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '';

    const pad = (n) => String(n).padStart(2, '0');
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

/**
 * Cụm trường xuất bản: trạng thái, ngày đăng, thứ tự, nổi bật.
 */
export default function StatusField({ data, errors = {}, onChange, withFeatured = true, withSortOrder = true }) {
    return (
        <div className="ui-form-grid">
            <Select
                label="Trạng thái"
                name="status"
                value={data.status || 'draft'}
                options={[
                    { value: 'draft', label: 'Bản nháp' },
                    { value: 'published', label: 'Đã xuất bản' },
                ]}
                error={errors.status}
                placeholder={null}
                onChange={(event) => onChange('status', event.target.value)}
            />

            <Input
                label="Ngày xuất bản"
                name="published_at"
                type="datetime-local"
                value={toLocalInput(data.published_at)}
                error={errors.published_at}
                hint="Để trống sẽ tự lấy thời điểm lưu khi xuất bản."
                onChange={(event) => onChange('published_at', event.target.value)}
            />

            {withSortOrder && (
                <Input
                    label="Thứ tự hiển thị"
                    name="sort_order"
                    type="number"
                    min="0"
                    value={data.sort_order ?? 0}
                    error={errors.sort_order}
                    onChange={(event) => onChange('sort_order', event.target.value)}
                />
            )}

            {withFeatured && (
                <div className="ui-form-grid__checkbox">
                    <Checkbox
                        label="Nổi bật (hiển thị ở trang chủ)"
                        name="is_featured"
                        checked={!!data.is_featured}
                        error={errors.is_featured}
                        onChange={(event) => onChange('is_featured', event.target.checked)}
                    />
                </div>
            )}
        </div>
    );
}
