import React, { useMemo, useState } from 'react';
import { router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

const ROLE_LABEL = {
    A: 'Nhãn',
    B: 'Tiêu đề',
    C: 'Chữ nhấn mạnh',
    D: 'Nút',
};

function Marker({ role, style }) {
    return (
        <span className="hs-marker" style={style} aria-hidden="true">
            {role}
        </span>
    );
}

function Field({ name, field, value, onChange }) {
    if (field.type === 'boolean') {
        return (
            <label className="hs-check">
                <input type="checkbox" checked={!!value} onChange={(event) => onChange(event.target.checked)} />
                <span>{field.label}</span>
            </label>
        );
    }

    if (field.type === 'color') {
        return (
            <label className="hs-field">
                <span>{field.label}</span>
                <div className="hs-color">
                    <input type="color" value={/^#[0-9a-f]{6}$/i.test(value || '') ? value : '#102c24'} onChange={(event) => onChange(event.target.value)} />
                    <input className="ui-input" value={value || ''} placeholder="#102c24" onChange={(event) => onChange(event.target.value)} />
                </div>
                {field.hint && <small>{field.hint}</small>}
            </label>
        );
    }

    if (field.type === 'select') {
        return (
            <label className="hs-field">
                <span>{field.label}</span>
                <select className="ui-select" value={value || ''} onChange={(event) => onChange(event.target.value)}>
                    {Object.entries(field.options || {}).map(([optionValue, optionLabel]) => (
                        <option key={optionValue} value={optionValue}>{optionLabel}</option>
                    ))}
                </select>
                {field.hint && <small>{field.hint}</small>}
            </label>
        );
    }

    return (
        <label className="hs-field">
            <span>{field.label}</span>
            <input className="ui-input" value={value ?? ''} onChange={(event) => onChange(event.target.value)} />
            {field.hint && <small>{field.hint}</small>}
        </label>
    );
}

export default function HomeSectionsIndex({ section, config, values }) {
    const [form, setForm] = useState(() => ({ ...values }));
    const [processing, setProcessing] = useState(false);

    const set = (key, value) => setForm((previous) => ({ ...previous, [key]: value }));

    const groups = useMemo(() => {
        const map = new Map();

        Object.entries(config.fields || {}).forEach(([key, field]) => {
            const role = field.role || 'section';
            if (!map.has(role)) map.set(role, []);
            map.get(role).push([key, field]);
        });

        return [...map.entries()];
    }, [config]);

    const save = () => {
        setProcessing(true);
        router.put(routes.admin.homeSections.update(section), form, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const reset = () => {
        setProcessing(true);
        router.post(routes.admin.homeSections.reset(section), {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const cream = form.background !== 'white';

    return (
        <AdminLayout
            title="Nội dung trang chủ"
            description="Chỉnh trực tiếp từng section: nhìn hình minh họa, sửa nội dung và xem kết quả trước khi lưu."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Nội dung trang chủ' }]}
        >
            <div className="hs-layout">
                <Card title={config.label} description={config.description}>
                    <div className="hs-preview" style={{ background: cream ? '#F7F5EF' : '#FFFFFF' }}>
                        <div className="hs-preview__inner">
                            {form.label_on !== false && (
                                <div className="hs-preview__label" style={{ color: form.label_color }}>
                                    <Marker role="A" style={{ left: -18, top: -10 }} />
                                    {form.label_text || 'Nhãn section'}
                                </div>
                            )}

                            <div className="hs-preview__title" style={{ color: form.title_color }}>
                                <Marker role="B" style={{ right: 6, top: -14 }} />
                                {form.title || 'Tiêu đề chính'}
                                {form.accent && (
                                    <em style={{ color: form.accent_color || form.title_color }}>
                                        <Marker role="C" style={{ right: -14, top: -8 }} /> {form.accent}
                                    </em>
                                )}
                            </div>

                            {form.lead && (
                                <p className="hs-preview__lead" style={{ color: form.lead_color }}>{form.lead}</p>
                            )}

                            <div className="hs-preview__gallery">
                                <div /><div /><div /><div />
                            </div>

                            {form.button_on !== false && (
                                <div style={{ position: 'relative', display: 'inline-block', marginTop: 16 }}>
                                    <Marker role="D" style={{ left: -18, top: -10 }} />
                                    <span className="hs-preview__btn" style={{ color: form.button_color, background: form.button_bg, borderColor: form.button_color }}>
                                        {form.button_text || 'Nút'}
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>

                    <p className="hs-legend">
                        A — Nhãn · B — Tiêu đề · C — Chữ nhấn mạnh · D — Nút. Các trường bên phải tương ứng đúng thứ tự này.
                    </p>
                </Card>

                <Card title="Chỉnh nội dung" description="Thay đổi bên dưới, phần xem trước cập nhật ngay.">
                    <div className="hs-form">
                        {groups.map(([role, fields]) => (
                            <fieldset className="hs-group" key={role}>
                                <legend>{ROLE_LABEL[role] || 'Section'}</legend>
                                {fields.map(([key, field]) => (
                                    <Field key={key} name={key} field={field} value={form[key]} onChange={(value) => set(key, value)} />
                                ))}
                            </fieldset>
                        ))}
                    </div>

                    <div className="ui-form-actions">
                        <Button type="button" variant="outline" onClick={reset} disabled={processing}>
                            Khôi phục mặc định
                        </Button>
                        <Button type="button" onClick={save} loading={processing}>
                            Lưu section
                        </Button>
                    </div>
                </Card>
            </div>
        </AdminLayout>
    );
}
