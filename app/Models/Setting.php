<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    protected $casts = [
        // Không cast cứng vì value có thể là string, number, boolean, json
    ];

    // ============================================================
    // CONSTANTS
    // ============================================================

    public const GROUP_GENERAL = 'general';
    public const GROUP_SEO = 'seo';
    public const GROUP_CONTACT = 'contact';
    public const GROUP_SOCIAL = 'social';
    public const GROUP_THEME = 'theme';
    public const GROUP_CACHE = 'cache';

    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_NUMBER = 'number';
    public const TYPE_JSON = 'json';
    public const TYPE_COLOR = 'color';
    public const TYPE_IMAGE = 'image';
    public const TYPE_SELECT = 'select';

    /** @deprecated Giữ để tương thích ngược, không dùng trong code mới. */
    public const CACHE_KEY_PREFIX = 'settings.';

    /** Cache key duy nhất chứa toàn bộ settings. */
    public const CACHE_KEY_ALL = 'settings.all';

    public const CACHE_TTL = 3600; // 1 giờ

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    // ============================================================
    // ACCESSORS & MUTATORS
    // ============================================================

    /**
     * Cast giá trị theo `type` khi đọc.
     */
    public function getCastedValueAttribute()
    {
        $value = $this->value;

        return match ($this->type) {
            self::TYPE_BOOLEAN => (bool) $value,
            self::TYPE_NUMBER  => is_numeric($value) ? (float) $value : null,
            self::TYPE_JSON    => $value ? json_decode($value, true) : null,
            default            => $value,
        };
    }

    /**
     * Cast giá trị theo `type` khi ghi.
     */
    public function setValueAttribute($value): void
    {
        if ($this->type === self::TYPE_JSON && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
            return;
        }

        if ($this->type === self::TYPE_BOOLEAN) {
            $this->attributes['value'] = $value ? '1' : '0';
            return;
        }

        $this->attributes['value'] = $value;
    }

    // ============================================================
    // STATIC HELPERS
    // ============================================================

    /**
     * Lấy giá trị setting theo key, có cache (toàn bộ settings trong 1 entry).
     */
    public static function get(string $key, $default = null)
    {
        $all = self::allCached();

        return array_key_exists($key, $all['values'])
            ? $all['values'][$key]
            : $default;
    }

    /**
     * Gán giá trị setting theo key.
     */
    public static function set(string $key, $value, string $group = self::GROUP_GENERAL, string $type = self::TYPE_STRING): void
    {
        $setting = static::firstOrNew(['key' => $key]);

        $setting->group = $group;
        $setting->type  = $type;
        $setting->value = $value;

        $setting->save();

        Cache::forget(self::CACHE_KEY_ALL);
    }

    /**
     * Xóa cache toàn bộ settings.
     */
    public static function forget(string $key): void
    {
        Cache::forget(self::CACHE_KEY_ALL);
    }

    /**
     * Xóa toàn bộ cache settings (1 lần duy nhất, không loop).
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL);
    }

    /**
     * Lấy toàn bộ setting trong 1 group, đọc từ cache.
     */
    public static function getGroup(string $group): array
    {
        $all = self::allCached();

        $result = [];
        foreach ($all['groups'][$group] ?? [] as $key) {
            $result[$key] = $all['values'][$key];
        }

        return $result;
    }

    /**
     * Nạp cache chủ động (dùng sau deploy hoặc trong seeder).
     */
    public static function warmUp(): void
    {
        Cache::forget(self::CACHE_KEY_ALL);
        self::allCached();
    }

    // ============================================================
    // INTERNAL
    // ============================================================

    /**
     * Đọc toàn bộ settings từ cache; nếu miss thì query DB 1 lần.
     *
     * Cấu trúc trả về:
     * [
     *   'values' => ['site_name' => '...', 'theme_h1_color' => '#...', ...],
     *   'groups' => ['general' => ['site_name', ...], 'theme' => [...], ...],
     * ]
     */
    protected static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY_ALL, self::CACHE_TTL, function () {
            $values = [];
            $groups = [];

            // Dùng query() để SoftDeletes tự lọc deleted_at IS NULL
            foreach (static::query()->get() as $setting) {
                $values[$setting->key] = $setting->casted_value;
                $groups[$setting->group][] = $setting->key;
            }

            return [
                'values' => $values,
                'groups' => $groups,
            ];
        });
    }
}