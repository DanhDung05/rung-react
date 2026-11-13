# DESIGN TOKENS — Rừng Adventure

Cập nhật: 2026-11-13. Nguồn: `config/settings.php`, `resources/css/tokens.css`, `resources/js/Components/ThemeStyles.jsx`.

## 1. Thứ tự ưu tiên

```
Section Override → Component Token (nếu có) → Global Palette/Typography → CSS fallback
```

- Section tắt override → kế thừa Global.
- Visual Editor style chỉ áp cho phần tử được gán.
- Preview iframe và Public dùng cùng token.

## 2. Global Palette — 10 key

| Key | CSS var | Mặc định | Phạm vi |
|---|---|---|---|
| `palette_forest` | `--rp-forest` | `#102c24` | Footer, CTA, nền tối, chữ đậm |
| `palette_cream` | `--rp-cream` | `#f7f3e9` | Section kem, PageHead, author box |
| `palette_white` | `--rp-white` | `#ffffff` | Nền trang, section trắng |
| `palette_earth` | `--rp-earth` | `#a77942` | Nhãn nhỏ, chữ nhấn, số thứ tự |
| `palette_text` | `--rp-ink` | `#1b2a22` | Chữ chính |
| `palette_muted` | `--rp-muted` | `#6b7a70` | Meta, caption, ghi chú |
| `palette_gold` | `--rp-gold` | `#c58b45` | Nút CTA vàng, badge, sao |
| `palette_surface` | `--rp-surface` | `#ffffff` | Card, input, form |
| `palette_border` | `--rp-line` | `#e4e0d5` | Viền, divider |
| `palette_on_dark` | `--rp-on-dark` | `#e9e4d6` | Chữ trên nền tối |

Định nghĩa gốc nằm trong `tokens.css`; `ThemeStyles` chỉ override khi Admin cấu hình.

## 3. Global Typography — 10 nhóm

`h1, h2, h3, page_title, section_title, body, lead, eyebrow, breadcrumb, footer`

Mỗi nhóm: `font, color, weight, size, line_height` → `--typo-<nhóm>-*`.
Chỉ inject rule khi Admin đặt giá trị; trống = giữ mặc định.
Ngoại lệ: Hero Slider H1 (`:not(.rp-slider__title)`) không nhận typography toàn cục.

## 4. Không phải Global Token (giữ CSS constant)

Shade (`#0d2620`, `#143b2f`, `#2c5a49`), sand, overlay `rgba`, màu trạng thái (`#b3261e`), gradient CTA.
Không đưa vào Palette vì làm mất sắc thái thiết kế.

## 5. Section Override

Gallery: `home_gallery_override_enabled` (mặc định false) + `home_gallery_*_color`.
Tắt → kế thừa Global; bật → dùng màu section.

## 6. Legacy & migration

| Legacy | Chuẩn | Xử lý |
|---|---|---|
| `bg_primary` | `palette_cream` | `php artisan tokens:migrate` |
| `bg_alternate` | `palette_white` | như trên |
| `theme_h1..h3_color` | `typo_h1..h3_color` | như trên |
| `theme_h1..h6_font` | — | giữ dữ liệu, không map (font cũ không có trên máy) |
| `theme_primary/secondary_color` | — | **Admin UI only**, không trộn Public Palette |
| `google_analytics_id` | — | đã xóa code + row (backup `storage/app/backups/`) |

`tokens:migrate` idempotent, có `--dry-run`, chỉ ghi khi đích trống, không xóa legacy.
