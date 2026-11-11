import React from 'react';
import { useForm, usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone, Send } from 'lucide-react';
import AppLayout from '../../Layouts/AppLayout';
import Seo from '../../Components/Seo';
import PageHero from '../../Components/Public/PageHero';
import { routes } from '../../lib/routes';

export default function Contact() {
    const { settings } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post('/lien-he', { preserveScroll: true, onSuccess: () => reset() });
    };

    return (
        <AppLayout>
            <Seo
                title="Liên hệ — Rừng Adventure"
                description="Liên hệ Rừng Adventure để được tư vấn hành trình trekking và khám phá thiên nhiên."
            />

            <PageHero
                eyebrow="Liên hệ"
                title="Nói cho chúng tôi nghe về chuyến đi của bạn"
                lead="Đội ngũ Rừng Adventure sẽ phản hồi trong thời gian sớm nhất."
                breadcrumbs={[{ label: 'Trang chủ', href: routes.home }, { label: 'Liên hệ' }]}
            />

            <section className="rp-section">
                <div className="rp-container rp-contact-layout">
                    <form className="rp-contact-form" onSubmit={submit} noValidate>
                        <h2 className="rp-h3">Gửi yêu cầu tư vấn</h2>

                        <div className="rp-form-grid">
                            <label className="rp-field">
                                <span>Họ tên *</span>
                                <input value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Nguyễn Văn A" />
                                {errors.name && <small className="rp-error">{errors.name}</small>}
                            </label>

                            <label className="rp-field">
                                <span>Email *</span>
                                <input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="email@example.com" />
                                {errors.email && <small className="rp-error">{errors.email}</small>}
                            </label>

                            <label className="rp-field">
                                <span>Số điện thoại</span>
                                <input value={data.phone} onChange={(e) => setData('phone', e.target.value)} placeholder="0123 456 789" />
                                {errors.phone && <small className="rp-error">{errors.phone}</small>}
                            </label>

                            <label className="rp-field">
                                <span>Chủ đề</span>
                                <input value={data.subject} onChange={(e) => setData('subject', e.target.value)} placeholder="Tư vấn tour, đặt chỗ..." />
                                {errors.subject && <small className="rp-error">{errors.subject}</small>}
                            </label>
                        </div>

                        <label className="rp-field">
                            <span>Nội dung *</span>
                            <textarea rows={5} value={data.message} onChange={(e) => setData('message', e.target.value)} placeholder="Bạn muốn đi đâu, khi nào, bao nhiêu người?" />
                            {errors.message && <small className="rp-error">{errors.message}</small>}
                        </label>

                        <button type="submit" className="rp-btn rp-btn--gold" disabled={processing}>
                            <Send size={15} /> {processing ? 'Đang gửi...' : 'Gửi yêu cầu'}
                        </button>
                    </form>

                    <aside className="rp-contact-info">
                        <div className="rp-widget">
                            <h3>Thông tin liên hệ</h3>
                            <div className="rp-widget__list">
                                {settings?.contact_address && (
                                    <span className="rp-contact-info__row"><MapPin size={16} /> {settings.contact_address}</span>
                                )}
                                {settings?.contact_phone && (
                                    <a href={`tel:${settings.contact_phone}`}><Phone size={16} /> {settings.contact_phone}</a>
                                )}
                                {settings?.contact_hours && (
                                    <span className="rp-contact-info__row"><Clock size={16} /> {settings.contact_hours}</span>
                                )}
                                {settings?.contact_email && (
                                    <a href={`mailto:${settings.contact_email}`}><Mail size={16} /> {settings.contact_email}</a>
                                )}
                            </div>
                        </div>
                    </aside>
                </div>
            </section>
        </AppLayout>
    );
}
