import { spawn, spawnSync } from 'node:child_process';
import { existsSync, rmSync } from 'node:fs';
import net from 'node:net';
import path from 'node:path';

/**
 * Chạy toàn bộ môi trường dev bằng một lệnh: `npm run dev`.
 * - Copy TinyMCE assets
 * - Laravel: php artisan serve (mặc định http://127.0.0.1:8000)
 * - Vite dev server (HMR) kèm Laravel plugin (public/hot)
 *
 * Có thể đổi cổng Laravel bằng biến môi trường PORT (mặc định 8000).
 */

const ROOT = process.cwd();
const PHP_PORT = process.env.PORT || '8000';
const PHP_HOST = process.env.HOST || '127.0.0.1';

const children = [];
let shuttingDown = false;

function log(message) {
    console.log(`\x1b[36m[dev]\x1b[0m ${message}`);
}

function removeHotFile() {
    const hot = path.join(ROOT, 'public', 'hot');
    if (existsSync(hot)) {
        try {
            rmSync(hot);
        } catch {
            // bỏ qua
        }
    }
}

function killTree(child) {
    if (!child || child.killed) return;

    if (process.platform === 'win32') {
        spawnSync('taskkill', ['/pid', String(child.pid), '/T', '/F'], { stdio: 'ignore' });
        return;
    }

    try {
        process.kill(-child.pid, 'SIGTERM');
    } catch {
        child.kill('SIGTERM');
    }
}

function shutdown(code = 0) {
    if (shuttingDown) return;
    shuttingDown = true;

    log('Đang dừng server…');
    children.forEach(killTree);
    removeHotFile();

    setTimeout(() => process.exit(code), 400);
}

function run(name, command, args, options = {}) {
    const child = spawn(command, args, {
        cwd: ROOT,
        stdio: 'inherit',
        env: { ...process.env, FORCE_COLOR: '1' },
        ...options,
    });

    child.on('error', (error) => {
        console.error(`\x1b[31m[dev]\x1b[0m Không chạy được ${name}: ${error.message}`);
        shutdown(1);
    });

    child.on('exit', (code) => {
        if (!shuttingDown) {
            console.error(`\x1b[31m[dev]\x1b[0m ${name} đã dừng (exit ${code ?? 1}).`);
            shutdown(code ?? 1);
        }
    });

    children.push(child);

    return child;
}

function checkMysql() {
    const socket = net.connect({ host: '127.0.0.1', port: 3306 });

    socket.setTimeout(700);
    socket.once('connect', () => {
        log('MySQL: đã kết nối (127.0.0.1:3306).');
        socket.destroy();
    });
    socket.once('timeout', () => {
        console.warn('\x1b[33m[dev]\x1b[0m MySQL chưa chạy trên 127.0.0.1:3306 — hãy bật MySQL (Laragon) trước khi mở web.');
        socket.destroy();
    });
    socket.once('error', () => {
        console.warn('\x1b[33m[dev]\x1b[0m Không kết nối được MySQL — website có thể báo lỗi database.');
        socket.destroy();
    });
}

process.on('exit', removeHotFile);
process.on('SIGINT', () => shutdown(0));
process.on('SIGTERM', () => shutdown(0));

removeHotFile();
log('Chuẩn bị TinyMCE…');
const copy = spawnSync(process.execPath, ['scripts/copy-tinymce.mjs'], { cwd: ROOT, stdio: 'inherit' });
if (copy.status !== 0) {
    console.error('\x1b[31m[dev]\x1b[0m Copy TinyMCE thất bại.');
    process.exit(copy.status ?? 1);
}

checkMysql();

log(`Laravel: http://${PHP_HOST}:${PHP_PORT}`);
log('Vite: http://127.0.0.1:5173 (HMR)');
log('Nhấn Ctrl+C để dừng cả hai.');

run('Laravel', 'php', ['artisan', 'serve', '--host', PHP_HOST, '--port', PHP_PORT]);
run('Vite', process.execPath, [path.join('node_modules', 'vite', 'bin', 'vite.js')]);
