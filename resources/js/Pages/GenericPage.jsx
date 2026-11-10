import React, { useMemo, useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Menu, X, Mountain, ArrowRight, ChevronRight, LayoutDashboard, MapPinned, Compass, BookOpen, Images, MessageSquare, Settings, Users, LogOut, Search, ExternalLink, Phone, Mail, MapPin } from 'lucide-react';

const nav = [
    ['Trang chủ', '/', Compass], ['Tour khám phá', '/tour', Mountain],
    ['Điểm đến', '/diem-den', MapPinned], ['Cẩm nang', '/bai-viet', BookOpen],
    ['Giới thiệu', '/gioi-thieu', Images], ['Liên hệ', '/lien-he', MessageSquare],
];
const adminNav = [
    ['Tổng quan', '/admin/dashboard', LayoutDashboard], ['Tour', '/admin/tour', Mountain],
    ['Điểm đến', '/admin/diem-den', MapPinned], ['Danh mục', '/admin/danh-muc', Compass],
    ['Bài viết', '/admin/bai-viet', BookOpen], ['Thư viện media', '/admin/media', Images],
    ['Liên hệ', '/admin/lien-he', MessageSquare], ['Người dùng', '/admin/nguoi-dung', Users],
    ['Cài đặt', '/admin/cai-dat', Settings],
];
const titleMap = {
    Home: 'Khám phá thiên nhiên cùng Rung Adventure',
    'Admin/Dashboard': 'Tổng quan quản trị',
    'Auth/Login': 'Đăng nhập hệ thống',
};
const label = (s='') => String(s).replaceAll('_',' ').replace(/\b\w/g, c => c.toUpperCase());
const pretty = (v) => {
    if (v === null || v === undefined || v === '') return '—';
    if (typeof v === 'boolean') return v ? 'Có' : 'Không';
    if (typeof v === 'object') return Array.isArray(v) ? `${v.length} mục` : (v.name || v.title || v.full_name || '');
    return String(v);
};
function Header({user}) {
    const [open,setOpen] = useState(false);
    return <header className="site-header"><div className="container header-inner">
        <Link href="/" className="brand"><span className="brand-mark"><Mountain size={23}/></span><span>RUNG<span className="brand-gold">ADVENTURE</span><small>EXPLORE THE WILD</small></span></Link>
        <nav className="desktop-nav">{nav.map(([t,u])=><Link key={u} href={u}>{t}</Link>)}</nav>
        <div className="header-actions"><Link className="btn btn-gold header-cta" href="/tour">Khám phá ngay <ArrowRight size={16}/></Link><button className="mobile-menu" onClick={()=>setOpen(!open)} aria-label="Menu">{open?<X/>:<Menu/>}</button></div>
        {open && <nav className="mobile-nav">{nav.map(([t,u])=><Link key={u} href={u} onClick={()=>setOpen(false)}>{t}</Link>)}</nav>}
    </div></header>
}
function Footer(){return <footer className="site-footer"><div className="container footer-grid"><div><div className="brand footer-brand"><span className="brand-mark"><Mountain/></span><span>RUNG<span className="brand-gold">ADVENTURE</span><small>EXPLORE THE WILD</small></span></div><p>Hành trình khám phá thiên nhiên, chạm đến những trải nghiệm nguyên bản.</p></div><div><b>Khám phá</b>{nav.slice(1,4).map(([t,u])=><Link key={u} href={u}>{t}</Link>)}</div><div><b>Kết nối</b><span><Phone size={15}/> Hotline tư vấn</span><span><Mail size={15}/> Email liên hệ</span><span><MapPin size={15}/> Việt Nam</span></div></div><div className="copyright">© {new Date().getFullYear()} Rung Adventure. All rights reserved.</div></footer>}
function AdminLayout({children}){const [open,setOpen]=useState(false);return <div className="admin-shell"><aside className={`admin-sidebar ${open?'is-open':''}`}><Link href="/" className="brand admin-brand"><span className="brand-mark"><Mountain/></span><span>RUNG<span className="brand-gold">ADVENTURE</span><small>ADMIN CONSOLE</small></span></Link><div className="sidebar-caption">QUẢN LÝ HỆ THỐNG</div>{adminNav.map(([t,u,I])=><Link key={u} href={u} className="side-link"><I size={18}/>{t}</Link>)}<Link href="/" className="side-link"><ExternalLink size={18}/> Xem website</Link></aside><div className="admin-main"><header className="admin-top"><button className="mobile-menu" onClick={()=>setOpen(!open)}><Menu/></button><span>Khu vực quản trị</span><Link href="/">Rung Adventure ↗</Link></header><main className="admin-content">{children}</main></div>{open&&<button className="sidebar-overlay" onClick={()=>setOpen(false)} aria-label="Đóng menu"/>}</div>}
function Hero({title,subtitle}){return <section className="page-hero"><div className="container"><div className="eyebrow">RUNG ADVENTURE <span/> EXPLORE MORE</div><h1>{title}</h1>{subtitle&&<p>{subtitle}</p>}</div></section>}
function DataTable({name,items}){const rows=Array.isArray(items)?items:(items?.data||[]);if(!rows.length)return null;const cols=Object.keys(rows[0]||{}).filter(k=>!['pivot','created_at','updated_at','deleted_at','password','remember_token'].includes(k)).slice(0,6);return <section className="data-card"><div className="data-card-head"><h2>{label(name)}</h2><span>{rows.length} bản ghi</span></div><div className="table-wrap"><table><thead><tr>{cols.map(c=><th key={c}>{label(c)}</th>)}</tr></thead><tbody>{rows.map((r,i)=><tr key={r.id||i}>{cols.map(c=><td key={c}>{pretty(r[c])}</td>)}</tr>)}</tbody></table></div></section>}
function GenericPage(){const page=usePage();const {component,props}=page;const isAdmin=component.startsWith('Admin/');const isAuth=component.startsWith('Auth/');const title=titleMap[component]||props.title||label(component.split('/').pop());const data=useMemo(()=>Object.entries(props).filter(([k,v])=>!['auth','flash','errors','ziggy','title','pageKey'].includes(k)&&v&&typeof v==='object'),[props]);
    if(isAdmin)return <AdminLayout><Head title={`${title} — Admin`}/><div className="admin-page-heading"><div><div className="eyebrow dark">RUNG ADVENTURE / QUẢN TRỊ</div><h1>{title}</h1><p>Quản lý và cập nhật nội dung website.</p></div></div><div className="generic-grid">{data.map(([k,v])=><DataTable key={k} name={k} items={v}/>)}</div></AdminLayout>;
    if(isAuth)return <><Head title={title}/><div className="auth-screen"><div className="auth-card"><div className="brand"><span className="brand-mark"><Mountain/></span><span>RUNG<span className="brand-gold">ADVENTURE</span><small>ADMINISTRATION</small></span></div><h1>{title}</h1><p>Vui lòng sử dụng biểu mẫu đăng nhập của hệ thống.</p><a className="btn btn-dark" href="/">Quay về trang chủ</a></div></div></>;
    return <><Head title={title}/><Header user={props.auth?.user}/><Hero title={title} subtitle="Những hành trình đáng nhớ bắt đầu từ đây."/><main className="container generic-content">{data.map(([k,v])=><DataTable key={k} name={k} items={v}/>)}</main><Footer/></>;
}
export default GenericPage;
