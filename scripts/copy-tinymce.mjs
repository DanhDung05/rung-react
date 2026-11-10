/**
 * Copy TinyMCE self-hosted vào public/vendor/tinymce.
 *
 * Lý do: bundle TinyMCE qua Vite tạo file >1MB khiến esbuild dùng temp file và
 * lỗi "Access is denied" trên Windows. Self-host trong public/ ổn định hơn và
 * không làm phình JS bundle.
 *
 * Chạy tự động trước `npm run dev` và `npm run build`.
 */
import { cpSync, existsSync, mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const source = join(root, 'node_modules', 'tinymce');
const target = join(root, 'public', 'vendor', 'tinymce');

if (!existsSync(source)) {
    console.warn('[tinymce] node_modules/tinymce không tồn tại — bỏ qua (chạy npm install trước).');
    process.exit(0);
}

const FILES = [
    'tinymce.min.js',
    'license.md',
    'icons/default/icons.min.js',
    'models/dom/model.min.js',
    'themes/silver/theme.min.js',
    'skins/ui/oxide/skin.min.css',
    'skins/ui/oxide/content.min.css',
    'skins/ui/oxide/content.inline.min.css',
    'skins/ui/oxide/skin.shadowdom.min.css',
    'skins/content/default/content.min.css',
    'plugins/lists/plugin.min.js',
    'plugins/link/plugin.min.js',
    'plugins/image/plugin.min.js',
    'plugins/table/plugin.min.js',
    'plugins/code/plugin.min.js',
];

let copied = 0;

for (const relative of FILES) {
    const from = join(source, relative);
    const to = join(target, relative);

    if (!existsSync(from)) {
        console.warn(`[tinymce] thiếu file: ${relative}`);
        continue;
    }

    mkdirSync(dirname(to), { recursive: true });
    cpSync(from, to);
    copied += 1;
}

console.log(`[tinymce] đã copy ${copied}/${FILES.length} file vào public/vendor/tinymce`);
