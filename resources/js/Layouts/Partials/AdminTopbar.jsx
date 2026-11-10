import React, { useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { Menu, LogOut, ExternalLink, ChevronDown } from 'lucide-react';
import { routes } from '../../lib/routes';
import { ROLE } from '../../lib/constants';

export default function AdminTopbar({ onToggleSidebar }) {
    const { props } = usePage();
    const user = props.auth?.user;
    const [open, setOpen] = useState(false);

    const role = ROLE[user?.role]?.label || 'Quản trị';

    const logout = () => {
        router.post(routes.auth.logout);
    };

    return (
        <header className="admin-top">
            <button type="button" className="mobile-menu" onClick={onToggleSidebar} aria-label="Mở menu quản trị">
                <Menu size={20} />
            </button>

            <span className="admin-top__title">Khu vực quản trị</span>

            <div className="admin-top__right">
                <Link href={routes.home} className="admin-top__site" target="_blank" rel="noopener noreferrer">
                    Xem website <ExternalLink size={14} />
                </Link>

                <div className="admin-user">
                    <button type="button" className="admin-user__button" onClick={() => setOpen((value) => !value)}>
                        <span className="admin-user__avatar">
                            {(user?.name || 'A').trim().charAt(0).toUpperCase()}
                        </span>
                        <span className="admin-user__meta">
                            <strong>{user?.name || 'Quản trị viên'}</strong>
                            <small>{role}</small>
                        </span>
                        <ChevronDown size={14} />
                    </button>

                    {open && (
                        <div className="admin-user__menu">
                            <div className="admin-user__email">{user?.email}</div>
                            <button type="button" onClick={logout} className="admin-user__logout">
                                <LogOut size={15} /> Đăng xuất
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
