import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Card from '../../../Components/UI/Card';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

function SectionPreview({ section, values }) {
    const fields = Object.values(values || {});

    return (
        <div className="ui-preview">
            <div className="ui-preview__inner">
                {fields.map((value, index) => (
                    <div
                        key={index}
                        className={index === 0 ? 'ui-preview__eyebrow' : index === 1 ? 'ui-preview__title' : 'ui-preview__text'}
                    >
                        {value || <span className="ui-preview__empty">(trống)</span>}
                    </div>
                ))}
            </div>
        </div>
    );
}

export default function UiTextsIndex({ page, pages = [], config, values }) {
    const [forms, setForms] = useState(() => JSON.parse(JSON.stringify(values || {})));
    const [processing, setProcessing] = useState(false);

    const setValue = (section, field, value) =>
        setForms((previous) => ({ ...previous, [section]: { ...(previous[section] || {}), [field]: value } }));

    const save = (section) => {
        setProcessing(true);
        router.put(routes.admin.uiTexts.update(page, section), forms[section] || {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    const reset = (section) => {
        setProcessing(true);
        router.post(routes.admin.uiTexts.reset(page, section), {}, {
            preserveScroll: true,
            onFinish: () => setProcessing(false),
        });
    };

    return (
        <AdminLayout
            title="Nội dung giao diện"
            description="Chỉnh toàn bộ chữ hiển thị trên website: nhãn, tiêu đề, placeholder, nút, trạng thái trống. Không thay đổi logic."
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'Nội dung giao diện' }]}
        >
            <div className="ui-tabs">
                {pages.map((item) => (
                    <Link
                        key={item.key}
                        href={routes.admin.uiTexts.index(item.key)}
                        className={`ui-tab ${item.key === page ? 'is-active' : ''}`.trim()}
                    >
                        {item.label}
                    </Link>
                ))}
            </div>

            <div className="ui-stack">
                {Object.entries(config.sections).map(([section, sectionConfig]) => (
                    <Card key={section} title={sectionConfig.label} description="Sửa nội dung bên dưới — preview cập nhật ngay.">
                        <div className="ui-split">
                            <div>
                                <SectionPreview section={section} values={forms[section]} />
                                <p className="ui-hint" style={{ marginTop: 10 }}>
                                    Preview minh họa thứ tự nội dung. Chữ trên website sẽ dùng đúng các giá trị này.
                                </p>
                            </div>

                            <div className="ui-stack">
                                {Object.entries(sectionConfig.fields).map(([field, meta]) => (
                                    <label className="ui-field" key={field}>
                                        <span className="ui-label">{meta.label}</span>
                                        <input
                                            className="ui-input"
                                            value={forms[section]?.[field] ?? ''}
                                            placeholder={meta.default || ''}
                                            onChange={(event) => setValue(section, field, event.target.value)}
                                        />
                                        <span className="ui-hint">Mặc định: {meta.default}</span>
                                    </label>
                                ))}

                                <div className="ui-form-actions">
                                    <Button type="button" variant="outline" onClick={() => reset(section)} disabled={processing}>
                                        Khôi phục mặc định
                                    </Button>
                                    <Button type="button" onClick={() => save(section)} loading={processing}>
                                        Lưu mục này
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </Card>
                ))}
            </div>
        </AdminLayout>
    );
}
