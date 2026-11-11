import React, { useState } from 'react';
import { router, usePage } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import Input from '../../../Components/UI/Input';
import Textarea from '../../../Components/UI/Textarea';
import Select from '../../../Components/UI/Select';
import Checkbox from '../../../Components/UI/Checkbox';
import ImageField from '../../../Components/Form/ImageField';
import { routes } from '../../../lib/routes';
import { mediaUrl } from '../../../lib/format';

/** Render 1 trường theo `type` trong config/settings.php */
function Field({ name, field, value, error, file, onChange, onFile }) {
    const label = field.label || name;
    const hint = field.hint || field.description || null;

    switch (field.type) {
        case 'text':
            return <Textarea label={label} name={name} rows={4} value={value ?? ''} hint={hint} error={error} onChange={(e) => onChange(e.target.value)} />;

        case 'boolean':
            return (
                <div className="ui-field">
                    <Checkbox label={label} name={name} checked={!!value} hint={hint} error={error} onChange={(e) => onChange(e.target.checked)} />
                </div>
            );

        case 'number':
            return <Input label={label} name={name} type="number" value={value ?? ''} hint={hint} error={error} onChange={(e) => onChange(e.target.value)} />;

        case 'color':
            return (
                <div className="ui-field">
                    <label className="ui-label" htmlFor={`field-${name}`}>{label}</label>
                    <div className="ui-color">
                        <input id={`field-${name}`} type="color" value={/^#[0-9a-f]{6}$/i.test(value || '') ? value : '#307a55'} onChange={(e) => onChange(e.target.value)} />
                        <input className="ui-input" value={value ?? ''} placeholder="#307a55" onChange={(e) => onChange(e.target.value)} />
                    </div>
                    {hint && <span className="ui-hint">{hint}</span>}
                    {error && <span className="ui-error">{error}</span>}
                </div>
            );

        case 'select': {
            const options = Object.entries(field.options || {}).map(([optionValue, optionLabel]) => ({ value: optionValue, label: optionLabel }));
            return <Select label={label} name={name} value={value ?? ''} options={options} placeholder="— Chọn —" hint={hint} error={error} onChange={(e) => onChange(e.target.value)} />;
        }

        case 'image':
            return <ImageField label={label} name={name} file={file} currentUrl={mediaUrl(value)} hint={hint} error={error} onChange={onFile} />;

        case 'json':
            return <Textarea label={label} name={name} rows={5} value={value ?? ''} hint={hint || 'Nhập JSON hợp lệ.'} error={error} onChange={(e) => onChange(e.target.value)} />;

        default:
            return <Input label={label} name={name} value={value ?? ''} hint={hint} error={error} onChange={(e) => onChange(e.target.value)} />;
    }
}

export default function SettingsEdit({ group, groupConfig = {}, current = {} }) {
    const { props } = usePage();
    const errors = props.errors || {};

    const [values, setValues] = useState(() => ({ ...current }));
    const [files, setFiles] = useState({});
    const [processing, setProcessing] = useState(false);

    const fields = groupConfig.fields || {};

    const resetDefaults = () => {
        if (!window.confirm('Khôi phục các trường về giá trị mặc định? Thay đổi chỉ áp dụng sau khi bấm Lưu.')) {
            return;
        }

        const defaults = {};

        Object.entries(fields).forEach(([key, field]) => {
            defaults[key] = field.type === 'boolean' ? !!field.default : field.default ?? '';
        });

        setValues(defaults);
        setFiles({});
    };

    const isThemeGroup = ['palette', 'typography'].includes(group);

    const previewVars = (() => {
        const vars = {};
        const map = {
            palette_forest: '--rp-forest',
            palette_cream: '--rp-cream',
            palette_white: '--rp-white',
            palette_earth: '--rp-earth',
            palette_text: '--rp-ink',
            palette_muted: '--rp-muted',
        };

        Object.entries(map).forEach(([key, variable]) => {
            if (values[key]) vars[variable] = values[key];
        });

        ['h1', 'h2', 'h3', 'page_title', 'section_title', 'body', 'lead', 'eyebrow', 'breadcrumb', 'footer'].forEach((key) => {
            const suffix = key.replace(/_/g, '-');
            if (values[`typo_${key}_font`]) vars[`--typo-${suffix}-font`] = values[`typo_${key}_font`];
            if (values[`typo_${key}_color`]) vars[`--typo-${suffix}-color`] = values[`typo_${key}_color`];
            if (values[`typo_${key}_weight`]) vars[`--typo-${suffix}-weight`] = values[`typo_${key}_weight`];
            if (values[`typo_${key}_line_height`]) vars[`--typo-${suffix}-line-height`] = values[`typo_${key}_line_height`];
            if (values[`typo_${key}_size`]) vars[`--typo-${suffix}-size`] = values[`typo_${key}_size`];
        });

        return vars;
    })();

    const submit = (event) => {
        event.preventDefault();

        const payload = {};

        Object.entries(fields).forEach(([key, field]) => {
            if (field.type === 'image') {
                if (files[key]) payload[key] = files[key];
                return;
            }

            if (field.type === 'boolean') {
                payload[key] = values[key] ? 1 : null;
                return;
            }

            payload[key] = values[key] ?? null;
        });

        setProcessing(true);
        router.post(routes.admin.settings.update(group), { ...payload, _method: 'put' }, {
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const pv = (key) => {
        const style = {};
        const color = values[`typo_${key}_color`];
        const font = values[`typo_${key}_font`];
        const weight = values[`typo_${key}_weight`];
        const size = values[`typo_${key}_size`];
        const lineHeight = values[`typo_${key}_line_height`];

        if (color) style.color = color;
        if (font) style.fontFamily = font;
        if (weight) style.fontWeight = weight;
        if (size) style.fontSize = size;
        if (lineHeight) style.lineHeight = lineHeight;

        return style;
    };

    return (
        <AdminLayout
            title={groupConfig.label || `Cấu hình: ${group}`}
            description={groupConfig.description}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Cài đặt', href: routes.admin.settings.index }, { label: groupConfig.label || group }]}
            actions={<Button href={routes.admin.settings.index} variant="outline">← Cài đặt</Button>}
        >
            {isThemeGroup && (
                <Card title="Xem trước trực tiếp" description="Preview cập nhật ngay khi đổi màu/font. Chưa bấm Lưu thì website công khai không thay đổi.">
                    <div className="rp-app theme-preview" style={previewVars}>
                        <div className="rp-eyebrow" style={pv('eyebrow')}>Nhãn nhỏ · Eyebrow</div>
                        <h1 className="rp-pagehead__title" style={pv('h1')}>Tiêu đề trang (H1)</h1>
                        <h2 className="rp-h2" style={pv('h2')}>Tiêu đề section (H2)</h2>
                        <h3 className="rp-h3" style={pv('h3')}>Tiêu đề phụ (H3)</h3>
                        <p className="rp-lead" style={pv('lead')}>Mô tả ngắn — lead text.</p>
                        <p style={pv('body')}>Nội dung văn bản mẫu cho phần body.</p>
                        <nav className="rp-breadcrumb" style={pv('breadcrumb')}>Trang chủ / Mẫu</nav>

                        <div className="theme-swatches">
                            {['palette_forest', 'palette_cream', 'palette_white', 'palette_earth', 'palette_text', 'palette_muted'].map((key) => (
                                <span key={key} title={key}>
                                    <i style={{ background: values[key] || '#ddd' }} />
                                    {values[key] || '—'}
                                </span>
                            ))}
                        </div>

                        <div className="theme-footer-sample" style={pv('footer')}>Footer mẫu — chữ liên hệ, bản quyền.</div>
                    </div>
                    <p className="ui-hint">Giá trị đang chỉnh hiển thị ở trên. Bấm “Hủy thay đổi” để quay về bản đã lưu.</p>
                </Card>
            )}

            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Cấu hình" description={`${Object.keys(fields).length} trường · nhóm “${group}”`}>
                    {Object.entries(fields).map(([key, field]) => (
                        <Field
                            key={key}
                            name={key}
                            field={field}
                            value={values[key]}
                            error={errors[key]}
                            file={files[key]}
                            onChange={(value) => setValues((previous) => ({ ...previous, [key]: value }))}
                            onFile={(file) => setFiles((previous) => ({ ...previous, [key]: file }))}
                        />
                    ))}

                    <div className="ui-form-actions">
                        <Button href={routes.admin.settings.index} variant="outline">Thoát</Button>
                        {isThemeGroup && (
                            <Button type="button" variant="outline" onClick={() => { setValues({ ...current }); setFiles({}); }}>
                                Hủy thay đổi
                            </Button>
                        )}
                        <Button type="button" variant="outline" onClick={resetDefaults}>
                            Khôi phục mặc định
                        </Button>
                        <Button type="submit" loading={processing}>Lưu cấu hình</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
