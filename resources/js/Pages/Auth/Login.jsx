import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { LockKeyhole, Mail } from 'lucide-react';
import AuthLayout from '../../Layouts/AuthLayout';
import Button from '../../Components/UI/Button';
import Checkbox from '../../Components/UI/Checkbox';
import { routes } from '../../lib/routes';

export default function Login() {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event) => {
        event.preventDefault();
        post(routes.auth.login, {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout title="Đăng nhập" description="Đăng nhập vào khu vực quản trị Rung Adventure.">
            <Head title="Đăng nhập — Rung Adventure" />

            <form onSubmit={submit} className="login-form" noValidate>
                <div className="ui-field">
                    <label className="ui-label" htmlFor="login-email">
                        Email
                    </label>
                    <div className="field-wrap">
                        <Mail size={17} />
                        <input
                            id="login-email"
                            type="email"
                            name="email"
                            autoComplete="username"
                            placeholder="admin@example.com"
                            value={data.email}
                            onChange={(event) => setData('email', event.target.value)}
                            required
                        />
                    </div>
                    {errors.email && <span className="ui-error">{errors.email}</span>}
                </div>

                <div className="ui-field">
                    <label className="ui-label" htmlFor="login-password">
                        Mật khẩu
                    </label>
                    <div className="field-wrap">
                        <LockKeyhole size={17} />
                        <input
                            id="login-password"
                            type="password"
                            name="password"
                            autoComplete="current-password"
                            placeholder="Nhập mật khẩu"
                            value={data.password}
                            onChange={(event) => setData('password', event.target.value)}
                            required
                        />
                    </div>
                    {errors.password && <span className="ui-error">{errors.password}</span>}
                </div>

                <Checkbox
                    label="Ghi nhớ đăng nhập"
                    name="remember"
                    checked={data.remember}
                    onChange={(event) => setData('remember', event.target.checked)}
                />

                <Button type="submit" variant="dark" loading={processing} className="login-submit">
                    {processing ? 'Đang đăng nhập…' : 'Đăng nhập'}
                </Button>
            </form>

            <p className="auth-help">
                <Link href={routes.home}>← Về trang chủ</Link>
            </p>
        </AuthLayout>
    );
}
