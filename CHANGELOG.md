# Changelog

Mọi thay đổi đáng chú ý của dự án Rung Adventure được ghi tại đây.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/vi/1.1.0/),
phiên bản theo [Semantic Versioning](https://semver.org/lang/vi/).

> **Quy ước ghi:**
> - `Added` — chức năng mới
> - `Changed` — thay đổi hành vi
> - `Deprecated` — sắp bị loại bỏ
> - `Removed` — đã loại bỏ
> - `Fixed` — sửa lỗi
> - `Security` — vá bảo mật
>
> Mỗi mục ghi kèm module và file chính. Chi tiết kỹ thuật xem `docs/DEVELOPMENT_LOG.md`.

---

## [2.4.0] - 2026-11-16

### Added
- **Realtime Admin đồng bộ**: event `ContactStatusChanged`, `ReviewModerated` (PrivateChannel, afterCommit) khi đổi trạng thái liên hệ/duyệt-từ chối-xóa-khôi phục đánh giá; payload kèm `contacts_new`, `reviews_pending`.
- **`useAdminRealtime`**: một hook duy nhất cập nhật badge, toast (chỉ sự kiện mới), tiêu đề tab `(N)`, debounce reload 500ms cho Contacts Index/Reviews Index/Dashboard; cập nhật trạng thái tại chỗ ở Contacts Show.
- **Polling dự phòng**: khi không có Echo/socket mất kết nối, reload `admin_badges` mỗi 60 giây, chỉ khi tab hiển thị; kết nối lại thì reload bù và tắt polling.
- Dashboard thêm số **đánh giá chờ duyệt**, **lead tư vấn tour**, hiệu ứng nhấn khi số đổi (tôn trọng `prefers-reduced-motion`).

### Changed
- `AdminBadges` cache 10 giây + flush khi có thay đổi; middleware chỉ tính cho admin/editor.
- `toOthers()` cho thao tác admin; frontend gửi `X-Socket-ID` qua sự kiện `inertia:before`.
- `/broadcasting/auth` giới hạn 60 lần/phút.
- Thêm index `reviews.status`; `VERSION` → 2.4.0.

### Security
- Payload broadcast chỉ id/type/title/status/time + số đếm, không email/SĐT/nội dung; test quét `app/Events` đảm bảo không còn `Channel` public.
## [2.3.2] - 2026-11-16

### Changed
- Trang **Admin → Lịch sử cập nhật** chuyển sang đọc trực tiếp `CHANGELOG.md` qua `ChangelogService`; bỏ CRUD, nút thêm/sửa/xóa và `Form.jsx`.
- Bảng `releases` và migration cũ được giữ nguyên nhưng không còn dùng để hiển thị; dữ liệu cũ không xuất hiện.
- `CHANGELOG.md` dùng định dạng Keep a Changelog cho mục mới: `## [x.y.z] - YYYY-MM-DD` + `### Added/Changed/Fixed/Security`.
- `VERSION` → 2.3.2.

### Removed
- Route `releases.create/store/edit/update/destroy`, trang `Form.jsx`, model `Release` (bảng `releases` và migration cũ vẫn giữ, không hiển thị).

### Fixed
- Lịch sử cập nhật không còn trống dù CHANGELOG đã có nhiều bản.
## [2026-11-16] v2.3.1 — Vá lỗi 429, proxy, login IP, realtime TLS, cache related

### Fixed
- **E**: Form Inertia bị throttle nhận redirect về form kèm flash `error` tiếng Việt (“Bạn thao tác quá nhanh…”, giây/phút theo `Retry-After`); JSON/API vẫn 429. Form review/tư vấn/contact giữ state (`preserveState`) để không mất dữ liệu/modal.
- **F**: `TRUSTED_PROXIES` cấu hình qua env (rỗng/không tin, `*`, hoặc IP/CIDR); lấy đúng IP sau nginx/Cloudflare cho rate limit.
- **H**: Login thêm giới hạn **20 lần thất bại/phút theo IP** độc lập với email+IP; thành công chỉ xóa bộ đếm email+IP.
- **I**: Echo đọc `VITE_PUSHER_SCHEME`; https bật `forceTLS` + transports `ws,wss`, http giữ hành vi Soketi local.
- **J**: Cache “tour liên quan” đổi key theo từng tour (`tour_related_{id}`, TTL 300s) và `CacheHelper::clearTourRelated()` xóa tour + các tour cùng danh mục khi thêm/sửa/xóa/khôi phục; không còn tour tự hiện trong related.
- **G**: Bổ sung assert honeypot review/tư vấn không dispatch event, không queue mail (honeypot vẫn tính vào throttle của route, không bypass).

### Docs
- `docs/DEPLOYMENT.md`: proxy tin cậy, realtime WSS; `.env.example` thêm `TRUSTED_PROXIES`, chú thích Pusher.
- `VERSION` → **2.3.1**.
## [2026-11-16] v2.3.0 — Đánh giá khách, tư vấn tour, chống spam, realtime bảo mật

### Added
- **Đánh giá khách vãng lai**: migration cho `reviews.user_id` nullable + `author_name`/`author_email`; bỏ unique cũ, unique `(tour_id, author_email)`; `StoreReviewRequest`; route review bỏ `auth`, rate limit 3/giờ.
- **Yêu cầu tư vấn tour**: mở rộng `contacts` (`tour_id`, `people_count`, `preferred_date`, `type`); `StoreTourInquiryRequest`; route `POST /tour/{slug}/yeu-cau-tu-van`; `TourInquiryController`; mail + broadcast dùng chung `ContactNotifier`; modal form trên trang tour.
- **Chống spam**: RateLimiter `contact` (5/giờ/IP), `review` (3/giờ/IP), `login` (5 lần/phút theo email+IP); honeypot `website` cho contact/review/inquiry.
- **Realtime bảo mật**: `PrivateChannel('admin-notifications')` + `AdminChannel` authorize admin/editor đang hoạt động; payload chỉ id/type/title/time; Echo chỉ khởi tạo khi có `VITE_PUSHER_APP_KEY`; badge + toast trong Admin.
- Admin Contacts: cột Loại, lọc loại, thông tin tour/số người/ngày dự kiến, SĐT `tel:`.
- Admin Reviews: hiển thị tên + email người gửi (kể cả khách).

### Changed
- `LoginController` dùng `is_active=1` trong credentials; khóa 5 lần/phút; không lộ trạng thái tài khoản khi sai mật khẩu.
- Mail `NewContactNotification` hiển thị thông tin tour cho lead tư vấn.
- `HandleInertiaRequests` chia sẻ `admin_badges`; public review payload loại bỏ email người đánh giá.
- `VERSION` → **2.3.0**.

### Fixed
- Form đánh giá trước đây trỏ `/login` và chỉ dành cho user đăng nhập.
- Broadcast event từng dùng kênh public và gửi email/nội dung khách.

### Security
- Private channel chỉ admin/editor active; payload tối thiểu; throttle + honeypot; không trả `author_email` ra public.
## [2026-11-16] Kiểm kê tài liệu & Trợ giúp Admin từ Markdown

### Added
- `docs/ADMIN_GUIDE.md` — hướng dẫn quản trị đầy đủ 19 mục (đăng nhập → visual editor → releases → lỗi thường gặp).
- Trang **Admin → Hướng dẫn sử dụng** render Markdown trực tiếp từ `docs/` qua manifest `config/admin_docs.php`; có tìm kiếm, mục lục, tài liệu trước/sau, rewrite link nội bộ.
- `GuideController` đọc whitelist file, `Str::markdown` với `html_input=strip`, chặn file ngoài `docs/`, sinh anchor heading.

### Changed
- Cập nhật `ADMIN_FEATURES.md`, `PROJECT_STATUS.md`, `ARCHITECTURE.md`, `docs/README.md`, `DATABASE.md` (bảng `releases`, gallery) theo commit `3e7854a`.
- `DOCUMENTATION_RULES.md` thành quy tắc bắt buộc theo ma trận thay đổi; tài liệu phải cập nhật cùng đợt commit.
- Trang Trợ giúp không còn dùng `config/admin_guide.php` (legacy).

### Fixed
- Tài liệu dự án trước đó dừng ở commit `9f03af2` (thiếu releases, carousel, dark mode, brand, dev script).
## [2026-11-16] Carousel thư viện chuyển khung ảnh ngang

### Changed
- Card carousel dùng tỉ lệ **4:3** (ưu tiên ảnh ngang du lịch), rộng tối đa 760px; thumbnail ngang 88×58.
- Giảm góc xoay/độ lệch để ảnh ngang không bị che; mobile 72–86vw.
## [2026-11-16] Carousel Media kiểu “The Window”

### Added
- Component `MediaCarousel`: coverflow 3D vòng tròn, kéo/swipe, mũi tên, tự chạy/tạm dừng, số thứ tự, tiêu đề + nhóm ảnh, timeline thumbnail, modal xem lớn (Esc/←/→).
- Section Thư viện dùng carousel với toàn bộ ảnh Media `gallery` (20 ảnh), không trộn ảnh slider/tour khi đã có bộ ảnh curated.

### Changed
- `home_gallery_media` lấy tối đa 24 ảnh kèm caption.
## [2026-11-16] Thư viện ảnh “The Window” trên trang chủ

### Added
- 20 ảnh WebP + metadata (tiêu đề/nhóm) tại `storage/app/public/media/gallery/the-window/`.
- Command `media:import-the-window` (idempotent) tạo Media collection `gallery`.
- Chia sẻ `settings.home_gallery_media`; section Thư viện ưu tiên hiển thị ảnh này trước ảnh slider/tour.
- Test `HomeGalleryMediaTest`.

### Notes
- Layout collage hiển thị 5 ảnh đầu; 20 ảnh vẫn quản lý trong Media Admin.
## [2026-11-16] Soketi tùy chọn trong `npm run dev`

### Added
- `scripts/dev.mjs` tự chạy Soketi `:6001` khi `BROADCAST_DRIVER=pusher` và đã cài `@soketi/soketi`; dùng PUSHER_* trong `.env`.
- Script `npm run soketi`; README hướng dẫn bật realtime.
- Mặc định vẫn `BROADCAST_DRIVER=log` nên không bắt buộc Soketi.
## [2026-11-16] Một lệnh `npm run dev` cho toàn bộ môi trường dev

### Added
- `scripts/dev.mjs`: chạy song song Laravel (`:8000`) + Vite HMR, copy TinyMCE, kiểm tra MySQL, dọn `public/hot` khi dừng.
- Scripts: `dev`, `dev:client`, `serve`; hỗ trợ `PORT` để đổi cổng Laravel.

### Changed
- README hướng dẫn chạy dev bằng một lệnh duy nhất; không cần mở terminal thứ hai cho `php artisan serve`.
## [2026-11-14] Trang Lịch sử cập nhật (Admin)

### Added
- Bảng `releases` + model `Release` (version duy nhất, loại, trạng thái, nội dung, ngày, người tạo).
- Admin `/admin/lich-su-cap-nhat`: timeline, version hiện tại, lọc version/loại/trạng thái/ngày, xem chi tiết, thêm/sửa/xóa (có xác nhận).
- Menu “Lịch sử cập nhật”; hướng dẫn Admin; `docs/RELEASES.md`; tệp `VERSION` (2.2.0).
- 7 feature test cho CRUD/quyền/filter/version trùng.

### Notes
- Không seed bản ghi phát hành; không tự công bố bản “Đang phát triển”.
## [2026-11-14] Sắp xếp section linh động (kéo-thả + từng section bổ sung)

### Added
- Kéo-thả hoặc dùng ↑/↓ trong panel **Thứ tự section**.
- Mỗi **Section bổ sung** là một mục riêng, đặt được ở bất kỳ vị trí nào giữa các khối.
- Thêm section mới tự chèn sau Banner; xóa section tự bỏ khỏi thứ tự.
- `home.layout.order` lưu danh sách khóa (`custom:<id>` cho từng section).
## [2026-11-14] Sắp xếp thứ tự section trang chủ

### Added
- Panel **“Thứ tự section”** trong Visual Editor: đổi vị trí 7 khối (Chào mừng, Hành trình, Banner, Section bổ sung, Thư viện, Cẩm nang, CTA) bằng nút ↑/↓.
- Lưu ở `home.layout.order`; Home dùng CSS `order` (Hero luôn đầu) nên không đổi cấu trúc JSX.
## [2026-11-14] Visual Editor: section bổ sung + sửa toggle

### Added
- Khối **Section bổ sung (quote band)**: thêm/xóa nhiều section ảnh nền + nhãn + trích dẫn + nguồn, hiển thị giữa Banner và Thư viện trang chủ.
- Lưu trong `home.custom.sections` của bản nháp/xuất bản.

### Fixed
- Panel bật/tắt section cập nhật đúng trạng thái checkbox sau khi bấm; giá trị `enabled` được lưu và áp dụng khi tải lại.
## [2026-11-13] Visual Editor GĐ1b — nút, ảnh, bật/tắt section

### Added
- 6 section trang chủ có `enabled` + data-ui; tắt sẽ ẩn section (cả public lẫn preview).
- Ảnh nền Banner/CTA chỉnh được (field `image`, nhập đường dẫn Media `/storage/...`).
- Nút: chọn nút để sửa **liên kết**; chọn phần chữ để sửa nội dung (giữ icon).
- Panel editor hiện checkbox “Hiện section”, ô ảnh, ô liên kết nút; thay đổi áp dụng ngay trong iframe.
## [2026-11-13] Tắt lớp phủ Hero: giữ dải tối cho menu

### Changed
- Khi tắt `hero_overlay_enabled`, bỏ gradient tối toàn ảnh nhưng giữ **dải tối 190px trên cùng** để menu/header đọc rõ.
- Áp dụng cho cả Hero trang chủ và `PageHero` các trang con (trước đây chỉ trang chủ).
## [2026-11-13] Sửa lưu công tắc bật/tắt trong Settings

### Fixed
- Checkbox boolean luôn lưu `0` đúng: controller dùng `$request->boolean()` thay vì `isset()` (trước đó gửi 0 vẫn bị lưu thành 1).
- Trang Settings đọc lại boolean đã cast, checkbox hiển thị đúng trạng thái sau F5.
## [2026-11-13] Bật/tắt lớp phủ tối Hero

### Added
- Setting `hero_overlay_enabled` (nhóm Ảnh Hero): bật/tắt lớp phủ tối trên ảnh hero trang chủ.
- HeroSlider nhận prop `overlay`; mặc định bật. Tắt để ảnh sáng/rõ hơn (chữ có thể khó đọc trên ảnh sáng).
## [2026-11-13] Sửa font tiêu đề Admin

### Fixed
- Tiêu đề/card Admin dùng `Segoe UI` + weight 700 thật, tắt `font-synthesis-weight` (nguyên nhân chữ bị dày giả, răng cưa).
## [2026-11-13] Hoàn thiện dark mode Admin

### Fixed
- Dashboard dark: stat cards, alert cards, chart tracks, distribution bars, bảng, feed (liên hệ/tour/menu) — hết mảng trắng và chữ tối trên nền tối.
- Bổ sung màu link/ghi chú trong dark mode.
## [2026-11-13] Admin: giao diện Sáng/Tối + thu gọn sidebar

### Added
- Nút chuyển **Sáng / Tối** trên topbar Admin; lưu lựa chọn vào localStorage.
- Nút **thu nhỏ / mở rộng sidebar** (76px, chỉ còn icon); lưu trạng thái.

### Changed
- Sidebar vẫn cố định (sticky) khi cuộn; mobile giữ drawer.
- Dark mode áp cho nền, topbar, card, input, bảng, chữ — không ảnh hưởng public.
## [2026-11-13] Cố định sidebar Admin

### Changed
- Sidebar Admin `position: sticky; top: 0; height: 100vh` trên desktop — không trôi theo nội dung khi cuộn; mobile giữ nguyên drawer.
## [2026-11-13] Sửa logo trắng bị vỡ

### Fixed
- Bỏ `filter: invert()` trên logo (gây vỡ nét); dùng **logo trắng PNG riêng** (`logo-white.png`, alpha mượt).
- Header trong suốt, Footer và menu mobile hiển thị logo trắng sắc nét; header nền kem dùng logo xanh gốc.
## [2026-11-13] Đồng nhất màu brand

### Changed
- “ADVENTURE” trong logo không còn màu vàng riêng; toàn bộ brand (RUNG / ADVENTURE / EXPLORE THE WILD) dùng chung màu kế thừa (Header/Footer), tagline giữ opacity nhẹ.

## [2026-11-13] Đồng nhất font/màu chữ nhấn trong tiêu đề

### Changed
- Chữ nhấn mạnh (`<em>`) trong H1/H2/H3, Welcome, About, PageHead, CTA kế thừa **cùng font, cùng màu, cùng độ đậm** với tiêu đề (bỏ serif nghiêng + màu Earth riêng).
- Hero Slider giữ nguyên thiết kế riêng (không đổi).

## [2026-11-13] Đơn giản hóa trang Cài đặt màu

### Changed
- Trang Cài đặt ẩn nhóm **Màu nền (đã chuyển)**; nhóm **Giao diện** chỉ còn 2 trường màu Admin (12 key h1..h6 legacy không còn hiển thị/đếm).
- Đổi tên: “Bảng màu website”, “Màu giao diện Admin” để phân biệt màu web và màu Admin.
- Trang Bảng màu thêm khối **“Màu này ảnh hưởng phần nào?”**: 8 mẫu thành phần thật (Footer, nền section, nền trang, nhãn, chữ, nút, card/viền, chữ trên nền tối) cập nhật theo màu đang chỉnh.

## [2026-11-13] Heading kế thừa Palette Forest

### Changed
- Bỏ fallback màu legacy `theme_h1..h3_color` khỏi Typography: `typo_h1..h3_color` mặc định trống để H1/H2/H3 kế thừa `palette_forest`.
- `ThemeStyles` không còn inject `--color-h1..h6` từ theme cũ; `base.css` heading fallback về `--rp-forest`.
- **Không chạy** `tokens:migrate` cho 3 màu heading; legacy vẫn giữ nguyên dữ liệu, không ghi đè.
- `bg_primary/bg_alternate` tiếp tục SKIP vì Palette đích đã có giá trị.

### Tests
- Thêm test: legacy `theme_h1_color` không còn xuất hiện trong props typography; `typo_h1_color` đặt tay vẫn áp dụng.



### Added
- 4 palette key chuẩn: `palette_gold`, `palette_surface`, `palette_border`, `palette_on_dark` (map `--rp-gold/--rp-surface/--rp-line/--rp-on-dark`).
- Command `tokens:migrate [--dry-run]`: chuyển `bg_primary/bg_alternate/theme_h1..h3_color` sang key chuẩn, idempotent, chỉ ghi khi đích trống.
- `docs/DESIGN_TOKENS.md` — danh mục token, thứ tự ưu tiên, bản đồ legacy.

### Changed
- Định nghĩa gốc `--rp-*` chuyển từ `public.css` (tự tham chiếu) sang `tokens.css`.
- `public.css` dùng `var(--rp-*, fallback)` cho vàng/viền/chữ-trên-nền-tối và surface; giữ shade/overlay/trạng thái làm CSS constant.
- Preview Admin nhận thêm 4 token mới.

### Removed
- `google_analytics_id`: xóa khỏi config + row Settings (đã backup `storage/app/backups/`).

## [2026-11-12] Mô tả màu trong Bảng màu

### Added
- Gợi ý dưới từng màu Palette: phạm vi tác động (Footer/CTA, nền section, nền trang, nhãn nhấn, chữ chính, chữ phụ) để Admin biết sửa màu nào ảnh hưởng phần nào.

## [2026-11-11] Chuẩn hóa màu nền

### Changed
- Palette (`palette_cream`, `palette_white`) trở thành nguồn màu nền duy nhất cho `--rp-cream` / `--rp-white`.
- `bg_primary` / `bg_alternate` chỉ còn fallback tương thích ngược, không ghi đè Palette; dữ liệu cũ giữ nguyên.
- Admin → Cài đặt → Màu nền: ẩn 2 trường màu, thêm thông báo và liên kết tới Bảng màu.
- ThemeStyles kiểm tra HEX hợp lệ trước khi áp dụng; màu sai/trống fallback an toàn.

### Tests
- Thêm `BackgroundColorTest` (palette thắng, appearance không ghi bg, màu sai bị từ chối).
## [2026-11-11] Chuẩn hóa tài liệu dự án

### Added
- `docs/README.md`, `docs/PROJECT_STATUS.md`, `docs/ARCHITECTURE.md`, `docs/ADMIN_FEATURES.md`.
- `docs/development/DOCUMENTATION_RULES.md` — quy tắc bắt buộc cập nhật tài liệu sau mỗi task.

### Changed
- Tài liệu phản ánh trạng thái thực tế tại commit `9f03af2`.

### Ghi nhận các thay đổi lớn trước đó (đã có trong DEVELOPMENT_LOG)
- P2–P6: Tour, Điểm đến, Blog, Pages/Contact/FAQ/Search, SEO JSON-LD (`d6c831d`, `5ddb701`, `91508e1`, `eff3a9f`, `ce73462`).
- PageHead tách khỏi hero (`fc3286b`), font tiếng Việt (`2ec4fc3`).
- Settings: giờ làm việc, sửa lưu ảnh multipart (`d16cf42`, `f3c0632`, `e0a4307`).
- UI Text manager (`616b05a`), Home Section editor (`79899f8`).
- Visual Editor GĐ1 (`015f2db`).
- Palette/Typography toàn cục (`aa57f5b`, `eb2eb8b`, `0e89b15`), Gallery override (`7577077`), preview iframe (`9f03af2`).
## [Unreleased]

### Added — Frontend P0: nền tảng giao diện public

- `resources/css/public.css` (~970 dòng): design system public, namespace `rp-*` — hero, section, card, filter, gallery, accordion, prose, CTA, reveal animation, responsive 1024/768/560.
- 9 component nền: `PageHero`, `SectionHeading`, `Breadcrumb`, `Prose`, `RatingStars`, `Gallery` (lightbox không phụ thuộc thư viện), `Accordion`, `Reveal`, `ErrorPage`.
- `hooks/useReveal.js` (IntersectionObserver, tôn trọng `prefers-reduced-motion`).
- `lib/schema.js`: JSON-LD cho Breadcrumb, TouristTrip, Article, FAQPage, TouristDestination.
- 4 trang lỗi Inertia: `Errors/NotFound`, `Forbidden`, `ServerError`, `Expired`.
- `AppLayout`: skip-link + wrapper `.rp-app`; bỏ `public/hot` cũ (Vite đã dừng).

### Added — Backend props (được duyệt)

- `TourController@show` eager-load `reviews` (đã duyệt) kèm `user`.
- Shared props `settings.hero_images` (6 ảnh) và `settings.about` (9 khoá).
- `Handler::render` trả trang lỗi Inertia cho public (404/403/419/500 khi tắt debug); giữ nguyên Admin/API.

### Added — Frontend P1: redesign Home / Header / Footer

- `Header`: trong suốt trên hero, chuyển nền cream khi cuộn, dropdown 2 cấp, drawer mobile toàn màn hình, nút tìm kiếm.
- `Footer`: 4 cột (thương hiệu + social, khám phá, liên hệ, CTA), bottom bar.
- `Home` thiết kế editorial: hero slider (autoplay, dots, arrows, reduced-motion), intro bất đối xứng, tour nổi bật (1 lớn + 4 nhỏ), điểm đến band nền rừng, 4 giá trị dạng số, gallery lightbox, 3 bài viết, CTA rộng.
- Component mới: `HeroSlider`, `TourCard`, `DestinationCard`, `PostCard`.
- `public.css` +927 dòng: header/footer/slider/editorial/responsive.
- Dùng dữ liệu thật: `sliders`, `featuredTours`, `featuredDestinations`, `latestPosts`, `settings.hero_images`, `settings.about`.

### Added — Frontend P1.1: redesign section nội dung trang chủ

- **Giới thiệu**: bố cục media + editorial copy 12 cột, ảnh dọc 4:4.6 với caption đè, trích dẫn lớn, chữ ký.
- **Tour nổi bật**: feature lớn toàn chiều cao (ảnh tràn + overlay) bên trái, 4 tour dạng danh sách đánh số bên phải (thay grid card).
- **Điểm đến**: mosaic bất đối xứng — 1 ảnh lớn dọc + 2 card ngang overlay.
- **Giá trị**: manifesto 2 cột (tuyên ngôn lớn + 4 giá trị dạng cột có đường kẻ, hover đổi màu viền).
- **Thư viện**: collage 4 cột với ô lớn 2×3, ô dọc, ô ngang (`layout="collage"`).
- **Bài viết**: journal 1 bài lớn + 2 dòng tiêu đề tối giản có đường kẻ.
- **CTA**: band full-bleed nền ảnh + overlay tối, tiêu đề serif, khối liên hệ bên phải.
- `public.css` +857 dòng; giữ nguyên HeroSlider và Header.

### Changed — Frontend P1.2: visual refinement trang chủ

- **Giới thiệu**: bố cục 2 cột media + editorial; tiêu đề lớn hơn, trích dẫn serif, chữ ký; ảnh chỉ render khi tải được.
- **Tour**: khối boxed split; cover có pattern khi thiếu ảnh, thêm mô tả + CTA; cột danh sách chia đều chiều cao, thumbnail chỉ hiện với ảnh thật.
- **Điểm đến**: chuyển sang **danh mục typographic** (số thứ tự, tên lớn, vùng, số tour) — loại bỏ hoàn toàn khối tối trống do thiếu ảnh.
- **Giá trị**: manifesto 2 cột, chữ tăng cỡ và tương phản.
- **Gallery**: preload và loại ảnh lỗi/MIME sai; layout duo/solo; không còn khung trống.
- **Bài viết**: journal 1 bài lớn + 2 dòng tiêu đề, không phụ thuộc ảnh.
- **CTA**: band full-bleed nền rừng, khối liên hệ bên phải.
- Thêm guard `overflow-x`, `min-width:0`, tăng cỡ chữ các nhãn nhỏ.

### Fixed — Frontend P1.2

- Hero title bị màu xanh đậm (kế thừa `h1` base) → trắng, có text-shadow.
- Chữ `em` trên nền rừng thiếu tương phản → vàng sáng `#e8c48d`.
- Ảnh lỗi để lại caption/khung trống → ẩn toàn bộ figure hoặc loại khỏi gallery.

### Changed — Frontend P1.3: cấu trúc tổng thể trang chủ

- Thêm **Lựa chọn hành trình**: các nhóm tour thật (từ quan hệ category của featuredTours) dạng danh mục typographic.
- Thêm **band toàn chiều rộng** với ảnh slider + trích dẫn triết lý hành trình.
- **Giá trị** chuyển thành các chương full-width (số thứ tự, tiêu đề, mô tả 2 cột) thay lưới 2×2.
- **Giới thiệu** chuyển hẳn sang text 2 cột (theo yêu cầu), bỏ khung ảnh — không còn khoảng trống.
- **Thư viện** chỉ dùng ảnh thật tải được (slider + hero settings), tự chuyển solo/duo.
- Nhịp section xen kẽ: trắng → kem → trắng → xanh rừng → ảnh toàn khung → xanh rừng → trắng → kem.

### Changed — Frontend P1.5: tinh gọn homepage

- Xóa hoàn toàn section Câu chuyện, Bạn muốn đi theo cách nào, Giá trị cốt lõi.
- Gộp toàn bộ tour vào một lưới duy nhất (3/2/1 cột) + nút Xem tất cả.
- Nền xen kẽ: Welcome kem → Tour trắng → Banner ảnh → Gallery kem → Cẩm nang trắng → CTA ảnh → Footer forest.
- TourCard/Gallery có placeholder nền dự phòng khi ảnh lỗi.

### Planned — Giai đoạn 2 (React hoàn thiện)

- Tầng nền React: `AppLayout`, `AdminLayout`, `AuthLayout`, design system, shared props (settings/menu/theme).
- 71 page component còn thiếu (58 admin + 13 frontend).
- Media Library có upload, chọn nhiều, MediaPicker.
- Trang lỗi Inertia (404/403/419/500).
- Loại bỏ `GenericPage` fallback.

### Planned — Giai đoạn 3–6

- Phân quyền Policy/Gate; vá lỗi leo thang đặc quyền của `editor`.
- Dashboard nâng cao.
- Version Management, Changelog, What's New, `/cap-nhat`.
- Activity Log / Audit Log.
- Bộ test tự động + build production.

---

## [0.3.1] — 2026-11-10

**Sửa lỗi Dashboard trắng do gọi route helper không tồn tại.**

### Fixed

- `Uncaught TypeError: routes.admin.menus.items is not a function` tại `Dashboard.jsx`.
- **Nguyên nhân gốc:** `routes.admin.menus` được tạo bằng `resource('/admin/menu')` — helper này chỉ sinh 9 method CRUD chuẩn (`index/create/store/show/edit/update/destroy/trash/restore/forceDestroy`), **không có** `items`. Route `admin.menus.items` là route bổ sung trong `routes/admin.php`, trước đó được khai báo rời ở `routes.admin.menuItems`.
- **Cách sửa:** bổ sung `items` và `itemsSave` vào chính `routes.admin.menus` (khớp tên route Laravel), bỏ 2 alias trùng `menuItems`/`menuItemsSave`, cập nhật `Menus/Items.jsx` sang `menus.itemsSave`.
- Sửa luôn 2 chỗ cùng lỗi tiềm ẩn: `Menus/Index.jsx`, `Menus/Show.jsx` (dùng chung helper nên không phải sửa code).

### Verified

- Node kiểm tra **76 lời gọi route** trong toàn bộ `resources/js` → tất cả là hàm hợp lệ.
- `menus.items(5)` → `/admin/menu/5/items`; `menus.itemsSave(5)` → cùng URL (GET/PUT).
- `npm run build`: exit 0; bundle chứa đúng `items: e => \`/admin/menu/${e}/items\``.
- `DashboardTest` + `AdminPagesExistTest`: 8 passed (75 assertions).
- Không đổi URL, không đổi route backend, không đổi DB, không đổi giao diện.

---

## [0.3.0] — 2026-11-10

**Toàn bộ giao diện quản trị chuyển sang React + Inertia.**

### Added

- **59 trang Admin React** — không còn trang nào dùng `GenericPage`:
  - Dashboard (đủ 11 prop: thống kê, biểu đồ 7 ngày, thùng rác, top nội dung, feed, phân bố độ khó, menu).
  - Tour (5), Bài viết (5), Tag (5), Điểm đến (5), Danh mục (5), Trang tĩnh (5), FAQ (5), Slider (5).
  - Liên hệ (2), Đánh giá (2), Media (2), Menu (6, gồm trình sửa cấu trúc), Người dùng (4), Cài đặt (2).
- **Toolkit Admin**: `DataTable`, `FilterBar`, `ImageField`, `SeoFields`, `StatusField`, `SlugField`, `RichTextEditor` (TinyMCE self-host), `useConfirm`.
- **Cài đặt render động** theo `config/settings.php`: string/text/boolean/number/color/select/image/json.
- **Menu Items editor**: thêm/xóa/sắp xếp/phân cấp, lưu qua `PUT admin/menu/{id}/items`.
- **Test Admin**: 9 file, 50 test — bao gồm test chống hồi quy `AdminPagesExistTest` (quét controller, đảm bảo 59/59 component tồn tại và không dùng GenericPage).
- `scripts/copy-tinymce.mjs` — tự copy TinyMCE vào `public/vendor` trước khi dev/build.

### Changed

- `package.json`: `dev`/`build` chạy copy TinyMCE trước.
- `Admin/Dashboard.jsx` viết lại dùng toàn bộ dữ liệu backend.
- `resources/css/react-ui.css` mở rộng cho toàn bộ Admin.

### Fixed

- TinyMCE bundle gây lỗi esbuild `Access is denied` trên Windows → chuyển sang self-host.
- Đường dẫn import sai ở trang Settings (phát hiện qua build, đã sửa trước khi bàn giao).

### Security

- Editor bị **403** khi truy cập Người dùng và Cài đặt (kiểm tra ở policy + controller, không chỉ ẩn menu).

### Notes

- **Không** xóa 132 file Blade, không sửa giao diện Public, không đổi dependency, không đổi database.
- **Chưa triển khai**: module "Đơn đặt tour" (không có backend) và upload trong Media Library (thiếu endpoint) — đã báo cáo, chờ phê duyệt.
- Tổng test toàn dự án: **89 passed / 534 assertions**. Build exit 0.

---

## [0.2.1] — 2026-11-10

**Bản vá phân quyền module Cài đặt (Settings).**

### Security

- **Chặn editor truy cập Settings**: thêm `App\Policies\SettingPolicy`.
  - `viewAny`/`view`/`update` chỉ dành cho `admin`.
  - `SettingController` gọi `authorize()` ở **cả 3 action** (index, edit, update) — không chỉ ẩn menu.
  - `UpdateSettingRequest::authorize()` chỉ cho admin.
  - Editor truy cập trực tiếp URL nhận **HTTP 403**; nhóm không tồn tại cũng trả 403 (không lộ thông tin).

### Added

- `tests/Feature/Security/SettingManagementTest.php` — 9 test, 44 assertion.

### Changed

- Không đổi route, URL, controller logic nghiệp vụ hay giao diện.
- Không đổi dependency, không đổi database.

### Verified

- Toàn bộ suite: **28 passed / 140 assertions**.
- `npm run build`: exit 0.
- Settings thật trong DB không đổi.

---

## [0.2.0] — 2026-11-10

**Giai đoạn 0 + vá bảo mật + Giai đoạn 2.0 (tầng nền React).**

### Added

- **Shared props Inertia**: `settings` (7 group), `theme` (màu + font H1–H6), `navigation` (menu main/footer), `app`, `flash`, `auth.user` rút gọn — `HandleInertiaRequests`.
- **`App\Services\NavigationService`**: dựng cây menu 3 cấp, cache 1 giờ, tự xóa cache khi admin sửa menu.
- **3 layout dùng chung**: `AppLayout` (công khai), `AdminLayout` (sidebar + topbar), `AuthLayout`.
- **4 partial layout**: `Header`, `Footer`, `AdminSidebar`, `AdminTopbar`.
- **14 UI component**: Button, FormField, Input, Textarea, Select, Checkbox, Badge, Card, Modal, Pagination, EmptyState, Spinner, FlashMessage, ConfirmDialog.
- **`Seo` component**: title, description, canonical, Open Graph, Twitter card, JSON-LD.
- **`ThemeStyles`**: inject màu/font từ Settings vào CSS variables → theme trong admin có tác dụng thật.
- **Thư viện tiện ích**: `lib/routes.js` (toàn bộ URL), `lib/format.js`, `lib/constants.js`, `hooks/useFlash.js`.
- **`resources/css/react-ui.css`**: CSS cho design system, tách khỏi khối inline cũ.
- **Test**: `InertiaFoundationTest` (6 test), `UserManagementTest` (11 test).

### Changed

- `resources/js/app.jsx`: bỏ `eager: true` → lazy-load từng page, tách bundle theo trang; cảnh báo ở dev khi thiếu page component.
- `Home.jsx`, `GenericPage.jsx`, `Admin/Dashboard.jsx`, `Auth/Login.jsx` chuyển sang dùng layout chung; bỏ header/footer/sidebar tự dựng.
- `Home.jsx` dùng slider thật từ DB thay vì ảnh nền hardcode.
- `CacheHelper` bổ sung `clearMenuCaches()`.
- `phpunit.xml`: trỏ DB test riêng `rungadventure_test`.

### Fixed

- **404 trả 500** khi build production: thêm `resources/js/app.js` vào Vite input.
- **`GenericPage` fallback im lặng**: nay có cảnh báo rõ trong môi trường dev.
- **Menu / Settings / Theme không tới được React**: đã chia sẻ qua Inertia.
- **`admin.css` rỗng gây warning build**: bỏ import, thay bằng `react-ui.css`.
- **Môi trường không chạy được**: `.env` + `APP_KEY` + `storage/logs` + `npm install` + build.
- **`public/storage` là thư mục copy** → đã chuyển thành symlink sau khi xác minh SHA256.

### Security

- **Vá lỗ hổng High**: editor tự nâng quyền thành admin.
  - `App\Policies\UserPolicy` — chỉ admin quản lý người dùng.
  - `UserController` gọi `authorize()` ở 7 action.
  - Chặn tự đổi `role` và tự vô hiệu hóa tài khoản.
  - `StoreUserRequest`/`UpdateUserRequest` chỉ cho admin.
- **Xóa cache cũ** chứa hash bcrypt admin + email/PII.

### Notes

- **Không sửa nghiệp vụ backend** nào ngoài phần phân quyền người dùng.
- **Không chạy `migrate:fresh`**, không import lại `rungadventure.sql`.
- DB thật sau toàn bộ thay đổi: users 3, tours 9, posts 9, settings 45, contacts 23, menu_items 14 — **không đổi**.
- Backup tại `C:\laragon\www\_backups-rung-react\20261110-115656` — **chưa xóa**, chờ xác nhận website ổn định.
- 71 page component vẫn còn thiếu (thuộc 2A/2B/2C/2D).

---

## [0.1.0] — 2026-11-10

**Giai đoạn 1 — Audit Source.** Bản phát hành **chỉ gồm tài liệu**, không thay đổi source nghiệp vụ.

### Added

- `docs/PROJECT_AUDIT.md` — báo cáo kiểm toán toàn bộ source:
  - Hiện trạng stack, môi trường, kiến trúc.
  - 152 route (120 admin + 22 frontend), 0 trùng lặp.
  - Ma trận đối chiếu 74 `Inertia::render()` ↔ 4 page component ↔ 142 Blade view.
  - Danh sách 71 component React còn thiếu.
  - Phân tích Models, 23 migration, dữ liệu thật 23 bảng.
  - 11 vấn đề bảo mật, 6 vấn đề dữ liệu, 6 vấn đề hiệu năng.
  - Rủi ro tổng hợp và điều kiện bắt đầu Giai đoạn 2.
- `docs/IMPLEMENTATION_PLAN.md` — kế hoạch 6 giai đoạn:
  - Giai đoạn 0 (ổn định môi trường), Giai đoạn 2 (React), 3 (CMS nâng cao), 4 (Version), 5 (Activity Log), 6 (Test & Deploy).
  - Chi tiết từng đợt, file cần tạo, tiêu chí nghiệm thu, ước lượng 68–87 dev-day.
  - Kiến trúc thư mục đích, quy ước code, rủi ro và biện pháp.
- `docs/FEATURES.md` — danh mục 32 chức năng theo 3 tầng (backend / admin UI / frontend UI).
- `docs/DATABASE.md` — 23 bảng, quan hệ, ràng buộc, quy ước bảng mới, quy tắc backup.
- `docs/DEPLOYMENT.md` — yêu cầu hệ thống, cài đặt, build, web server, queue, checklist, troubleshooting, rollback.
- `docs/DEVELOPMENT_LOG.md` — nhật ký Giai đoạn 1.
- `CHANGELOG.md` — file này.

### Changed

- Không thay đổi source nghiệp vụ.

### Fixed

- Không sửa lỗi nào trong Giai đoạn 1 (theo phạm vi). 12 lỗi đã được ghi nhận trong `docs/DEVELOPMENT_LOG.md`.

### Security

- Ghi nhận **1 lỗ hổng High**: tài khoản `editor` có thể tự nâng thành `admin` qua `/admin/tai-khoan/{id}/edit`.
- Ghi nhận 4 vấn đề Medium: không throttle đăng nhập, broadcast channel công khai, cho upload SVG, `APP_DEBUG=true` trong `.env.example`.
- Ghi nhận 5 vấn đề Low: admin route thiếu `auth`, cache cũ chứa hash/PII, `HOME` sai, thiếu security headers, contact form không captcha.
- **Chưa vá** — thuộc Giai đoạn 3.

### Notes

- Source hiện **chưa chạy được** ở môi trường sạch: thiếu `.env`, `public/build`, `node_modules`; `public/storage` là thư mục copy thay vì symlink.
- DB thật đã migrate đủ 23/23. **Không** import `rungadventure.sql` (dump cũ, thiếu bảng `jobs` và `reviews`).
- **Không** chạy `migrate:fresh`.

---

## Mốc tham chiếu của source tại thời điểm audit

| Hạng mục | Giá trị |
|---|---|
| Laravel | 10.50.4 |
| Inertia Laravel | 2.0.28 |
| React / @inertiajs/react | 18.3.1 / 2.0 |
| Vite / Tailwind | 5 / 3.4 |
| PHP | 8.1.10 |
| Migration | 23/23 |
| Route | 152 |
| Controller / Model / FormRequest | 26 / 16 / 25 |
| Blade view | 142 (132 dead code) |
| React page | 4 (3 thật + GenericPage) |
| Test nghiệp vụ | 0 |
