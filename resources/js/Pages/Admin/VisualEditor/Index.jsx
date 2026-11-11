import React, { useCallback, useEffect, useRef, useState } from 'react';
import { router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import Button from '../../../Components/UI/Button';
import { routes } from '../../../lib/routes';

const DEVICES = [
    ['desktop', 1440],
    ['tablet', 768],
    ['mobile', 390],
];

const STYLE_FIELDS = [
    ['color', 'Màu chữ', 'color'],
    ['fontSize', 'Cỡ chữ (px)', 'number'],
    ['fontWeight', 'Độ đậm', 'select'],
    ['fontStyle', 'Nghiêng', 'select'],
    ['lineHeight', 'Line-height', 'text'],
    ['fontFamily', 'Font', 'select'],
];

const FONTS = ['', 'Segoe UI', 'Arial', 'Cambria', 'Georgia', 'Times New Roman'];

export default function VisualEditorIndex({ config, published, draft, versions = [] }) {
    const frameRef = useRef(null);
    const [device, setDevice] = useState('desktop');
    const [values, setValues] = useState(() => (Object.keys(draft || {}).length ? draft : published || {}));
    const [selected, setSelected] = useState(null);
    const [current, setCurrent] = useState({ text: '', style: {} });
    const [history, setHistory] = useState([]);
    const [future, setFuture] = useState([]);
    const [processing, setProcessing] = useState(false);

    const pushHistory = (next) => {
        setHistory((previous) => [...previous.slice(-49), values]);
        setFuture([]);
        setValues(next);
    };

    const select = useCallback((id, doc) => {
        const el = doc.querySelector(`[data-ui="${id}"]`);
        if (!el) return;
        setSelected(id);
        setCurrent({
            text: el.textContent || '',
            style: {
                color: el.style.color || '',
                fontSize: el.style.fontSize || '',
                fontWeight: el.style.fontWeight || '',
                fontStyle: el.style.fontStyle || '',
                lineHeight: el.style.lineHeight || '',
                fontFamily: el.style.fontFamily || '',
            },
        });
        doc.querySelectorAll('[data-ve-selected]').forEach((node) => {
            node.removeAttribute('data-ve-selected');
            node.style.outline = '';
        });
        el.setAttribute('data-ve-selected', '1');
        el.style.outline = '2px solid #c58b45';
        el.style.outlineOffset = '3px';
    }, []);

    const attach = () => {
        const frame = frameRef.current;
        const doc = frame?.contentDocument;
        if (!doc) return;

        const onClick = (event) => {
            const el = event.target.closest('[data-ui]');
            if (!el) return;
            event.preventDefault();
            event.stopPropagation();
            select(el.getAttribute('data-ui'), doc);
        };

        doc.addEventListener('click', onClick, true);
    };

    useEffect(() => {
        attach();
    }, []);

    const applyField = (key, value) => {
        const next = { ...values, [selected]: { ...(values[selected] || {}), text: key === 'text' ? value : current.text, style: { ...(values[selected]?.style || {}), ...(key === 'text' ? {} : { [key]: value }) } } };
        pushHistory(next);
        setCurrent((previous) => ({ ...previous, ...(key === 'text' ? { text: value } : { style: { ...previous.style, [key]: value } }) }));

        const doc = frameRef.current?.contentDocument;
        const el = doc?.querySelector(`[data-ui="${selected}"]`);
        if (el) {
            if (key === 'text') el.textContent = value;
            else el.style[key] = value;
        }
    };

    const saveDraft = () => {
        setProcessing(true);
        router.post(routes.admin.visualEditor.draft, { values }, { preserveScroll: true, onFinish: () => setProcessing(false) });
    };

    const publish = () => {
        setProcessing(true);
        router.post(routes.admin.visualEditor.publish, { values }, { preserveScroll: true, onFinish: () => setProcessing(false) });
    };

    const restore = (index) => {
        setProcessing(true);
        router.post(routes.admin.visualEditor.restore, { index }, { preserveScroll: true, onFinish: () => setProcessing(false) });
    };

    const undo = () => {
        if (!history.length) return;
        setFuture((previous) => [values, ...previous]);
        setValues(history[history.length - 1]);
        setHistory((previous) => previous.slice(0, -1));
    };

    const redo = () => {
        if (!future.length) return;
        setHistory((previous) => [...previous, values]);
        setValues(future[0]);
        setFuture((previous) => previous.slice(1));
    };

    const width = DEVICES.find(([key]) => key === device)?.[1] || 1440;

    return (
        <AdminLayout title="Visual Editor" description="Bấm trực tiếp vào thành phần trên trang để chỉnh nội dung và kiểu chữ." breadcrumbs={[{ label: 'Quản trị' }, { label: 'Visual Editor' }]}>
            <div className="ve-shell">
                <div className="ve-toolbar">
                    {DEVICES.map(([key, size]) => (
                        <button key={key} type="button" className={`ve-btn ${device === key ? 'is-active' : ''}`.trim()} onClick={() => setDevice(key)}>
                            {key === 'desktop' ? 'Desktop' : key === 'tablet' ? 'Tablet' : 'Mobile'} ({size})
                        </button>
                    ))}
                    <span className="ve-sep" />
                    <button type="button" className="ve-btn" onClick={undo} disabled={!history.length}>↶ Hoàn tác</button>
                    <button type="button" className="ve-btn" onClick={redo} disabled={!future.length}>↷ Làm lại</button>
                    <span className="ve-sep" />
                    <button type="button" className="ve-btn" onClick={saveDraft} disabled={processing}>Lưu nháp</button>
                    <button type="button" className="ve-btn ve-btn--gold" onClick={publish} disabled={processing}>Xuất bản</button>
                    <a className="ve-btn" href={routes.admin.dashboard}>Thoát</a>
                </div>

                <div className="ve-body">
                    <div className="ve-canvas" style={{ width }}>
                        <iframe
                            ref={frameRef}
                            title="Xem trước giao diện"
                            src="/?ve=1"
                            className="ve-frame"
                            style={{ width }}
                            onLoad={attach}
                        />
                    </div>

                    <aside className="ve-panel">
                        <h3>{selected || 'Chọn một thành phần trên trang'}</h3>

                        {selected && (
                            <>
                                <label className="ui-field">
                                    <span className="ui-label">Nội dung</span>
                                    <textarea className="ui-input" rows={3} value={current.text} onChange={(event) => applyField('text', event.target.value)} />
                                </label>

                                {STYLE_FIELDS.map(([key, label, type]) => (
                                    <label className="ui-field" key={key}>
                                        <span className="ui-label">{label}</span>
                                        {type === 'select' && key === 'fontWeight' ? (
                                            <select className="ui-select" value={current.style.fontWeight} onChange={(event) => applyField(key, event.target.value)}>
                                                <option value="">Mặc định</option>
                                                {[300, 400, 500, 600, 700, 800].map((weight) => <option key={weight} value={weight}>{weight}</option>)}
                                            </select>
                                        ) : type === 'select' && key === 'fontStyle' ? (
                                            <select className="ui-select" value={current.style.fontStyle} onChange={(event) => applyField(key, event.target.value)}>
                                                <option value="">Mặc định</option>
                                                <option value="italic">Nghiêng</option>
                                                <option value="normal">Thường</option>
                                            </select>
                                        ) : type === 'select' ? (
                                            <select className="ui-select" value={current.style.fontFamily} onChange={(event) => applyField('fontFamily', event.target.value)}>
                                                {FONTS.map((font) => <option key={font} value={font}>{font || 'Mặc định'}</option>)}
                                            </select>
                                        ) : key === 'color' ? (
                                            <input type="color" className="ve-color" value={current.style.color || '#102c24'} onChange={(event) => applyField('color', event.target.value)} />
                                        ) : (
                                            <input className="ui-input" type={type} value={current.style[key] || ''} onChange={(event) => applyField(key, event.target.value)} />
                                        )}
                                    </label>
                                ))}
                            </>
                        )}

                        <div className="ve-versions">
                            <h4>Phiên bản đã xuất bản ({versions.length}/5)</h4>
                            {versions.length === 0 && <p className="ui-hint">Chưa có bản sao nào.</p>}
                            {versions.map((version, index) => (
                                <button key={index} type="button" className="ve-version" onClick={() => restore(index)}>
                                    {version.at || `Bản ${index + 1}`}
                                </button>
                            ))}
                        </div>
                    </aside>
                </div>
            </div>
        </AdminLayout>
    );
}
