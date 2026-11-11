# ARCHITECTURE — Rừng Adventure

## 1. Công nghệ

| Thành phần | Phiên bản / ghi chú |
|---|---|
| Backend | Laravel 10, PHP 8.1, MySQL 8 |
| Frontend | React 18, Inertia 2, Vite 5, Tailwind 3 |
| Auth | Session Laravel, role `admin` / `editor` |
| Editor | TinyMCE self-host tại `public/vendor/tinymce` |
| CSS | `resources/css/app.css` → tokens, base, components, utilities, pages, react-ui, public |

## 2. Cấu trúc thư mục chính

```
app/Http/Controllers/{Admin,Frontend,Auth}
app/Http/Middleware/HandleInertiaRequests.php   # chia sẻ props
app/Models                                      # Eloquent
config/settings.php                             # nhóm Settings + field
config/ui_texts.php                             # text/label theo trang
config/home_sections.php                        # section trang chủ
config/visual_editor.php                        # registry Visual Editor
resources/js/Pages/Admin/**                     # trang quản trị
resources/js/Pages/**                           # trang public
resources/js/Components/{Data,Form,UI,Public}    # toolkit dùng chung
resources/css/{public.css,react-ui.css,...}
routes/{web,admin,auth}.php
```

## 3. Luồng request

1. Route → Controller → Eloquent → `Inertia::render('Page/Name', props)`.
2. `HandleInertiaRequests` chia sẻ: `auth.user`, `flash`, `settings`, `theme`, `navigation`, `app`, `ui`, `visual`, `home_gallery`.
3. React nhận props, render bằng layout `AppLayout` (public) hoặc `AdminLayout` (admin).
4. Vite build tạo manifest; Blade `app.blade.php` chỉ còn vai trò root.

## 4. Quản lý cấu hình

| Hệ | Key | Nơi đọc | Ghi chú |
|---|---|---|---|
| Settings | `site_*`, `contact_*`, `social_*`, `seo_*`, `hero_image_*`, `about_*` | `HandleInertiaRequests` | key-value, cache `settings.all` |
| Palette | `palette_*` | `ThemeStyles` → `--rp-*` | 6 màu |
| Typography | `typo_*` | `ThemeStyles` → CSS rule | 10 nhóm |
| Appearance | `bg_primary`, `bg_alternate` | `ThemeStyles` | nền kem/trắng |
| UI Text | `ui_{page}_{section}_{field}` | props `ui` + `lib/ui.js` | text/label |
| Home Section | `home_gallery_*` | props `home_gallery` | gallery + override |
| Visual Editor | `ve_pub_home`, `ve_draft_home`, `ve_hist_home` | props `visual` | draft/publish/history |

**Thứ tự ưu tiên hiển thị:** Visual Editor override → Section override (`override_enabled`) → Global Palette/Typography → CSS fallback.

## 5. Admin ↔ Public

- Admin CRUD ghi DB → Public đọc lại qua controller/props.
- Settings lưu DB, cache 1 giờ; mỗi lần ghi gọi `Setting::flushCache()`.
- Visual Editor: draft/published tách biệt; middleware chỉ trả draft khi admin + `?ve`/`?preview`.
- Public không bao giờ nhận draft hoặc cấu hình chưa lưu.
