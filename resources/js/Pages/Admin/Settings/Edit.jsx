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
        const payload = {};

        Object.entries(fields).forEach(([key, field]) => {
            if (field.type === 'image') return;
            payload[key] = field.type === 'boolean' ? (field.default ? 1 : null) : field.default ?? null;
        });

        setProcessing(true);
        router.post(routes.admin.settings.update(group), { ...payload, _method: 'put' }, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

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

    return (
        <AdminLayout
            title={groupConfig.label || `Cấu hình: ${group}`}
            description={groupConfig.description}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Cài đặt', href: routes.admin.settings.index }, { label: groupConfig.label || group }]}
            actions={<Button href={routes.admin.settings.index} variant="outline">← Cài đặt</Button>}
        >
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
                        <Button href={routes.admin.settings.index} variant="outline">Hủy</Button>
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
