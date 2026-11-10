import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from '../../../../Layouts/AdminLayout';
import Card from '../../../../Components/UI/Card';
import Button from '../../../../Components/UI/Button';
import Input from '../../../../Components/UI/Input';
import Textarea from '../../../../Components/UI/Textarea';
import Checkbox from '../../../../Components/UI/Checkbox';
import { routes } from '../../../../lib/routes';

export default function FaqForm({ faq = null }) {
    const isEdit = !!faq;

    const { data, setData, post, put, processing, errors } = useForm({
        category: faq?.category ?? '',
        question: faq?.question ?? '',
        answer: faq?.answer ?? '',
        sort_order: faq?.sort_order ?? 0,
        is_active: faq ? !!faq.is_active : true,
    });

    const set = (key, value) => setData(key, value);

    const submit = (event) => {
        event.preventDefault();
        if (isEdit) put(routes.admin.faqs.update(faq.id));
        else post(routes.admin.faqs.store);
    };

    return (
        <AdminLayout
            title={isEdit ? 'Sửa câu hỏi' : 'Thêm câu hỏi mới'}
            breadcrumbs={[{ label: 'Quản trị' }, { label: 'FAQ', href: routes.admin.faqs.index }, { label: isEdit ? 'Sửa' : 'Tạo mới' }]}
            actions={<Button href={routes.admin.faqs.index} variant="outline">← Danh sách</Button>}
        >
            <form onSubmit={submit} noValidate className="admin-form-narrow">
                <Card title="Nội dung câu hỏi">
                    <Input label="Nhóm câu hỏi" name="category" value={data.category} error={errors.category} hint="Ví dụ: Đặt tour, Thanh toán, Hành lý…" onChange={(e) => set('category', e.target.value)} />
                    <Input label="Câu hỏi" name="question" required value={data.question} error={errors.question} onChange={(e) => set('question', e.target.value)} />
                    <Textarea label="Câu trả lời" name="answer" rows={6} required value={data.answer} error={errors.answer} onChange={(e) => set('answer', e.target.value)} />
                    <div className="ui-form-grid">
                        <Input label="Thứ tự" name="sort_order" type="number" min="0" value={data.sort_order} error={errors.sort_order} onChange={(e) => set('sort_order', e.target.value)} />
                        <div className="ui-form-grid__checkbox">
                            <Checkbox label="Đang hiển thị" name="is_active" checked={!!data.is_active} error={errors.is_active} onChange={(e) => set('is_active', e.target.checked)} />
                        </div>
                    </div>
                    <div className="ui-form-actions">
                        <Button href={routes.admin.faqs.index} variant="outline">Hủy</Button>
                        <Button type="submit" loading={processing}>{isEdit ? 'Lưu thay đổi' : 'Tạo câu hỏi'}</Button>
                    </div>
                </Card>
            </form>
        </AdminLayout>
    );
}
