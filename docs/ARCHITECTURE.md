# ARCHITECTURE — Rừng Adventure

Cập nhật: 2026-11-16 · commit `3e7854a` (Laravel 10, PHP 8.1, MySQL 8).

## 1. Công nghệ

| Thành phần | Ghi chú |
|---|---|
| Backend | Laravel 10.50, PHP 8.1, MySQL 8 |
| Frontend | React 18, Inertia 2, Vite 5, Tailwind 3 |
| Auth | Session Laravel, role `admin` / `editor` |
| Editor | TinyMCE self-host `public/vendor/tinymce` |
| CSS | `app.css` → tokens, base, components, utilities, pages, react-ui, public |
| Realtime | Laravel Echo + Pusher protocol; Soketi tùy chọn (`:6001`) |

## 2. Cấu trúc chính

```
app/Http/Controllers/{Admin,Frontend,Auth}
app/Http/Middleware/HandleInertiaRequests.php   # props dùng chung
app/Models                                      # Eloquent (Tour, Post, Media, Release...)
app/Services/ImageService.php                   # upload + thumbnail
app/Console/Commands                            # tokens:migrate, media:import-the-window
config/settings.php                             # nhóm Settings
config/home_sections.php                        # section Gallery
config/visual_editor.php                        # registry Visual Editor
config/admin_docs.php                           # manifest tài liệu cho trang Trợ giúp
config/admin_guide.php                          # (legacy, không còn dùng)
docs/*.md                                       # nguồn tài liệu + Trợ giúp Admin
resources/js/Pages/Admin/**                     # trang quản trị
resources/js/Pages/**                           # trang public
resources/js/Components/{Data,Form,UI,Public}   # toolkit; Public gồm HeroSlider, MediaCarousel
resources/css/public.css                        # design system .rp-*
routes/{web,admin,auth}.php
scripts/dev.mjs                                 # npm run dev (Laravel+Vite+Soketi)
```

## 3. Luồng request

1. Route → Controller → Eloquent → `Inertia::render('Page', props)`.
2. `HandleInertiaRequests` chia sẻ: `auth.user`, `flash`, `settings` (gồm `home_gallery`, `home_gallery_media`), `theme`, `navigation`, `app`, `ui`, `visual`.
3. React render bằng `AppLayout` (public) / `AdminLayout` (admin).
4. Vite build → manifest; Blade `app.blade.php` chỉ là root.

## 4. Cấu hình & ưu tiên

| Hệ | Key | Nơi đọc |
|---|---|---|
| Settings | `site_*`, `contact_*`, `social_*`, `seo_*`, `hero_*`, `about_*` | `HandleInertiaRequests` (cache 1h) |
| Palette | `palette_*` (10) | `ThemeStyles` → `--rp-*` |
| Typography | `typo_*` (10 nhóm) | `ThemeStyles` → rule CSS |
| Home Gallery | `home_gallery_*` | props `settings.home_gallery` |
| Gallery media | bảng `media` collection `gallery` | props `settings.home_gallery_media` |
| Visual Editor | `ve_draft_home`, `ve_pub_home`, `ve_hist_home` | props `visual` |
| Releases | bảng `releases` | `ReleaseController`; version từ `VERSION` |

**Ưu tiên hiển thị:** Visual Editor override → Section override → Global Palette/Typography → CSS fallback.

## 5. Ảnh & Media

- Disk `public`: `storage/app/public`, URL `/storage` qua symlink `public/storage`.
- Upload: `ImageService::upload()` → `media/{folder}/{Y}/{m}/{random}.{ext}` + thumbnail `.../thumbs/...` (400px).
- DB lưu đường dẫn tương đối; frontend `mediaUrl()` thêm `/storage/`.
- Media Library: bảng `media` (collection, alt, caption, sort_order, soft delete).
- Bộ The Window: `media/gallery/the-window/*.webp` + `meta.json`, import bằng `media:import-the-window`.
- Trang chủ: `MediaCarousel` nhận ảnh curated (tối đa 24), fallback slider/tour/placeholder khi chưa có.

## 6. Lịch sử cập nhật

- Nguồn duy nhất: `CHANGELOG.md`; `App\Services\ChangelogService` parse heading `##`, nhóm `###`, bullet.
- Cache theo `md5(path) + filemtime`; file đổi tự cập nhật, không cần flush.
- `ReleaseController@index/show` chỉ đọc; route `GET /admin/lich-su-cap-nhat[/{version}]`; không có CRUD.
- Bảng `releases` + migration cũ giữ nguyên nhưng không còn dùng để hiển thị.
- `VERSION` quyết định badge “Hiện tại”; quy trình tại `docs/RELEASES.md`.

## 7. Trợ giúp Admin từ Markdown

- Manifest `config/admin_docs.php` (whitelist slug → file trong `docs/`).
- `GuideController` đọc file, `Str::markdown()` với `html_input=strip`, sinh mục lục heading + anchor, rewrite link `.md` nội bộ sang `/admin/huong-dan?doc=...`.
- Trang `Admin/Guide/Index` hiển thị sidebar tài liệu + tìm kiếm + nội dung HTML đã sanitize.
- Không đọc file ngoài `docs/`; route nằm trong middleware `admin`.

## 8. Admin ↔ Public

- Admin ghi DB → public đọc lại qua controller/props.
- Settings cache 1h; ghi qua `Setting::flushCache()`.
- Visual Editor tách draft/published; middleware chỉ trả draft cho admin khi preview.
- Public không nhận draft/cấu hình chưa lưu.

## 9. Môi trường dev

`npm run dev` → `scripts/dev.mjs`: copy TinyMCE, chạy `php artisan serve` (:8000), Vite HMR (:5173), kiểm tra MySQL, tự chạy Soketi (:6001) nếu `BROADCAST_DRIVER=pusher` và đã cài; dọn `public/hot` khi dừng.

## 10. Bảo mật form & realtime

- **RateLimiter** (`RouteServiceProvider` + `LoginController`): `contact` 5/giờ/IP, `review` 3/giờ/IP, `login` 5 lần/phút theo email+IP và **20 lần/phút theo IP**.
- **Honeypot** `website` ở contact/review/tour inquiry; có giá trị → trả success nhưng không lưu/gửi mail, vẫn tính vào throttle của route.
- **Đánh giá khách**: `reviews.user_id` nullable, `author_name`/`author_email`, unique `(tour_id, author_email)`; payload public chỉ select id/tour_id/user_id/rating/comment/author_name/created_at + user id/name.
- **Tư vấn tour**: `contacts.tour_id/people_count/preferred_date/type`; route `POST /tour/{slug}/yeu-cau-tu-van`; `ContactNotifier` dùng chung mail queue + event.
- **Broadcast**: `PrivateChannel('admin-notifications')`; `AdminChannel::allows()` yêu cầu đăng nhập + `is_active` + role admin/editor; payload chỉ id/type/title/time; Echo tạo khi có `VITE_PUSHER_APP_KEY`, scheme https → WSS.
- **429**: `Handler` đổi throttle thành redirect + flash tiếng Việt cho form Inertia; JSON/API giữ 429.
- **Proxy**: `TRUSTED_PROXIES` (rỗng/không tin, `*`, hoặc IP/CIDR) để `$request->ip()` đúng sau nginx/Cloudflare.

## 11. Realtime Admin (v2.4.0)

- Kênh: `PrivateChannel('admin-notifications')`; authorize qua `AdminChannel` (đăng nhập, `is_active`, admin/editor).
- Event khách: `NewContactNotification`, `NewReviewNotification`. Event admin: `ContactStatusChanged`, `ReviewModerated` (afterCommit).
- Payload chỉ `id`, `type`, `title`, `status` (nếu có), `time`, `contacts_new`, `reviews_pending`; không PII.
- `AdminBadges::counts()` cache 10 giây, flush khi có thay đổi; middleware chỉ tính cho admin/editor.
- `useAdminRealtime` trong `AdminLayout`: subscribe một lần, cập nhật badge/title, toast cho sự kiện mới, debounce reload 500ms chỉ ở Contacts/Reviews Index + Dashboard; trang form không reload.
- `toOthers()` + header `X-Socket-ID` (gắn qua `inertia:before`) để admin thao tác không nhận lại event của mình.
- Không có Echo/socket mất kết nối → polling `admin_badges` 60 giây, chỉ khi tab hiển thị; kết nối lại reload bù và tắt polling.
- `/broadcasting/auth` giới hạn 60 lần/phút.
