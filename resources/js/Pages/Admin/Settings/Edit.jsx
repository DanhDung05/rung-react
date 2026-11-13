import React, { useEffect, useRef, useState } from 'react';
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
            palette_gold: '--rp-gold',
            palette_surface: '--rp-surface',
            palette_border: '--rp-line',
            palette_on_dark: '--rp-on-dark',
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

    const [previewPage, setPreviewPage] = useState('home');
    const [previewDevice, setPreviewDevice] = useState(1440);
    const [previewMode, setPreviewMode] = useState('editing');
    const [previewKey, setPreviewKey] = useState(0);
    const [previewTick, setPreviewTick] = useState(0);
    const previewRef = useRef(null);

    const buildPreviewCss = (source) => {
        const paletteMap = {
            palette_forest: '--rp-forest',
            palette_cream: '--rp-cream',
            palette_white: '--rp-white',
            palette_earth: '--rp-earth',
            palette_text: '--rp-ink',
            palette_muted: '--rp-muted',
            palette_gold: '--rp-gold',
            palette_surface: '--rp-surface',
            palette_border: '--rp-line',
            palette_on_dark: '--rp-on-dark',
        };
        const lines = [];
        Object.entries(paletteMap).forEach(([key, variable]) => {
            if (source[key]) lines.push(`${variable}:${source[key]};`);
        });

        const selectors = {
            h1: '.rp-app h1:not(.rp-slider__title), .rp-app .rp-display, .rp-app .rp-pagehead__title, .rp-app .h-welcome__title',
            h2: '.rp-app .rp-h2, .rp-app .h-about__title',
            h3: '.rp-app h3, .rp-app .rp-h3',
            page_title: '.rp-app .rp-pagehead__title',
            section_title: '.rp-app .rp-h2',
            body: '.rp-app p, .rp-app .rp-prose',
            lead: '.rp-app .rp-lead, .rp-app .h-welcome__lead, .rp-app .rp-pagehead__lead',
            eyebrow: '.rp-app .rp-eyebrow',
            breadcrumb: '.rp-app .rp-breadcrumb',
            footer: '.rp-footer',
        };
        const props = { font: 'font-family', color: 'color', weight: 'font-weight', line_height: 'line-height', size: 'font-size' };
        const rules = [];

        Object.entries(selectors).forEach(([group, selector]) => {
            Object.entries(props).forEach(([prop, cssProp]) => {
                const value = source[`typo_${group}_${prop}`];
                if (value) rules.push(`${selector}{${cssProp}:${value};}`);
            });
        });

        return `:root{${lines.join('')}}${rules.join('')}`;
    };

    useEffect(() => {
        const doc = previewRef.current?.contentDocument;
        if (!doc || !isThemeGroup) return;

        let style = doc.getElementById('theme-preview-style');
        if (!style) {
            style = doc.createElement('style');
            style.id = 'theme-preview-style';
            doc.head.appendChild(style);
        }

        style.textContent = buildPreviewCss(previewMode === 'saved' ? current : values);
    }, [values, current, previewMode, previewPage, previewKey, previewTick, isThemeGroup]);
    return (
        <AdminLayout
            title={groupConfig.label || `Cấu hình: ${group}`}
            description={groupConfig.description}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Cài đặt', href: routes.admin.settings.index }, { label: groupConfig.label || group }]}
            actions={<Button href={routes.admin.settings.index} variant="outline">← Cài đặt</Button>}
        >
            {groupConfig.notice && (
                <Card title="Thông báo" description={groupConfig.description}>
                    <p style={{ marginTop: 0 }}>{groupConfig.notice}</p>
                    {groupConfig.link && (
                        <Button href={groupConfig.link.url} variant="outline">{groupConfig.link.label}</Button>
                    )}
                </Card>
            )}
            {group === 'palette' && (
                <Card title="Màu này ảnh hưởng phần nào?" description="Mỗi ô là thành phần thật trên website, cập nhật theo màu đang chỉnh.">
                    <div className="palette-impact">
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Forest — Footer / CTA / tiêu đề</span>
                            <div style={{ background: values.palette_forest || '#102c24', color: values.palette_on_dark || '#e9e4d6', padding: '12px 14px', borderRadius: 6, fontWeight: 700 }}>Rừng Adventure</div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Warm Cream — nền section</span>
                            <div style={{ background: values.palette_cream || '#f7f3e9', border: '1px solid #e4e0d5', padding: 14, borderRadius: 6 }}>
                                <div className="rp-eyebrow" style={{ color: values.palette_earth || '#a77942' }}>NHÃN NHỎ</div>
                            </div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">White — nền trang</span>
                            <div style={{ background: values.palette_white || '#fff', border: '1px solid #e4e0d5', padding: 14, borderRadius: 6 }}>Nội dung trên nền trắng</div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Earth — nhãn & chữ nhấn</span>
                            <div style={{ background: '#fff', border: '1px solid #e4e0d5', padding: 14, borderRadius: 6 }}>
                                <div className="rp-eyebrow" style={{ color: values.palette_earth || '#a77942' }}>THƯ VIỆN</div>
                                <div style={{ color: values.palette_forest || '#102c24', fontWeight: 800 }}>Khoảnh khắc <em style={{ color: values.palette_earth || '#a77942' }}>đường rừng</em></div>
                            </div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Text / Muted — chữ chính & phụ</span>
                            <div style={{ background: '#fff', border: '1px solid #e4e0d5', padding: 14, borderRadius: 6 }}>
                                <div style={{ color: values.palette_text || '#1b2a22' }}>Chữ nội dung chính</div>
                                <div style={{ color: values.palette_muted || '#6b7a70', fontSize: '.8rem' }}>Meta, ngày tháng, ghi chú</div>
                            </div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Gold — nút CTA</span>
                            <div style={{ background: '#fff', border: '1px solid #e4e0d5', padding: 14, borderRadius: 6 }}>
                                <span style={{ background: values.palette_gold || '#c58b45', color: '#fff', padding: '9px 16px', borderRadius: 999, fontWeight: 800, fontSize: '.75rem' }}>Khám phá hành trình</span>
                            </div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">Surface / Border — card & viền</span>
                            <div style={{ background: values.palette_surface || '#fff', border: `1px solid ${values.palette_border || '#e4e0d5'}`, padding: 14, borderRadius: 6 }}>Card nội dung</div>
                        </div>
                        <div className="palette-impact__item">
                            <span className="palette-impact__label">On-dark — chữ trên nền tối</span>
                            <div style={{ background: values.palette_forest || '#102c24', color: values.palette_on_dark || '#e9e4d6', padding: 14, borderRadius: 6, fontSize: '.85rem' }}>Chữ trong Footer</div>
                        </div>
                    </div>
                </Card>
            )}

            {isThemeGroup && (
                <Card title="Xem trước giao diện thật" description="Iframe dùng chính trang public. Thay đổi chỉ nằm trong trình duyệt Admin, không ghi database.">
                    <div className="theme-preview-toolbar">
                        <button type="button" className={`ui-btn ui-btn--sm ${previewPage === 'home' ? 'ui-btn--primary' : 'ui-btn--ghost'}`.trim()} onClick={() => setPreviewPage('home')}>Trang chủ</button>
                        <button type="button" className={`ui-btn ui-btn--sm ${previewPage === 'tour' ? 'ui-btn--primary' : 'ui-btn--ghost'}`.trim()} onClick={() => setPreviewPage('tour')}>Tour Index</button>
                        <span className="ui-hint">|</span>
                        {[[1440, 'Desktop'], [768, 'Tablet'], [390, 'Mobile']].map(([size, label]) => (
                            <button key={size} type="button" className={`ui-btn ui-btn--sm ${previewDevice === size ? 'ui-btn--primary' : 'ui-btn--ghost'}`.trim()} onClick={() => setPreviewDevice(size)}>{label}</button>
                        ))}
                        <span className="ui-hint">|</span>
                        <button type="button" className={`ui-btn ui-btn--sm ${previewMode === 'saved' ? 'ui-btn--primary' : 'ui-btn--ghost'}`.trim()} onClick={() => setPreviewMode('saved')}>Đang lưu</button>
                        <button type="button" className={`ui-btn ui-btn--sm ${previewMode === 'editing' ? 'ui-btn--primary' : 'ui-btn--ghost'}`.trim()} onClick={() => setPreviewMode('editing')}>Đang chỉnh</button>
                        <button type="button" className="ui-btn ui-btn--sm ui-btn--ghost" onClick={() => setPreviewKey((key) => key + 1)}>Làm mới</button>
                    </div>

                    <div className="theme-preview-canvas">
                        <iframe
                            key={`${previewPage}-${previewKey}`}
                            ref={previewRef}
                            title="Preview giao diện public"
                            src={previewPage === 'tour' ? '/tour' : '/'}
                            style={{ width: previewDevice }}
                            onLoad={() => setPreviewTick((tick) => tick + 1)}
                        />
                    </div>
                    <p className="ui-hint">“Đang lưu” hiển thị cấu hình đã lưu; “Đang chỉnh” hiển thị giá trị trên form. Không có dữ liệu nào được ghi khi xem trước.</p>
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
                        {groupConfig.notice && (
                <Card title="Thông báo" description={groupConfig.description}>
                    <p style={{ marginTop: 0 }}>{groupConfig.notice}</p>
                    {groupConfig.link && (
                        <Button href={groupConfig.link.url} variant="outline">{groupConfig.link.label}</Button>
                    )}
                </Card>
            )}
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
