<?php

if (!function_exists('__t')) {
    /**
     * Get static translation.
     * 
     * @param string $key
     * @param string $default
     * @param string $group
     * @return string
     */
    function __t($key, $default = '', $group = 'general')
    {
        return \App\Models\StaticTranslation::get($key, $default, $group);
    }
}

if (!function_exists('form_t')) {
    function form_t(string $key, string $default = '', array $replace = []): string
    {
        $message = __t($key, $default, 'frontend');

        foreach ($replace as $placeholder => $value) {
            $message = str_replace(':' . $placeholder, (string) $value, $message);
        }

        return $message;
    }
}

if (!function_exists('setting_json')) {
    /**
     * @return array<int|string, mixed>
     */
    function setting_json(string $key, array $default = []): array
    {
        $raw = \App\Models\Setting::get($key);
        if ($raw === null || $raw === '') {
            return $default;
        }
        if (is_array($raw)) {
            return $raw;
        }
        $decoded = json_decode((string) $raw, true);

        return is_array($decoded) ? $decoded : $default;
    }
}

if (!function_exists('page_extras')) {
    function page_extras(?\App\Models\Page $page, array $defaults): array
    {
        $extras = is_array($page?->extras) ? $page->extras : [];
        $merged = array_replace_recursive($defaults, $extras);

        return page_extras_preserve_cleared($merged, $extras);
    }
}

if (!function_exists('page_extras_preserve_cleared')) {
    /**
     * Keep explicit empty/null extras (user deleted a field) instead of restoring defaults.
     */
    function page_extras_preserve_cleared(array $merged, array $extras): array
    {
        foreach ($extras as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = page_extras_preserve_cleared($merged[$key], $value);
                continue;
            }

            if ($value === null || $value === '') {
                $merged[$key] = '';
            }
        }

        return $merged;
    }
}

if (!function_exists('page_extra_field_value')) {
    /**
     * Admin extra field value: missing key → default, explicit clear → empty.
     */
    function page_extra_field_value(array $extras, string $key, mixed $default = null): mixed
    {
        if (! \Illuminate\Support\Arr::has($extras, $key)) {
            return $default;
        }

        return data_get($extras, $key);
    }
}

if (!function_exists('tech_detail_for')) {
    function tech_detail_for(?\App\Models\Product $product, ?array $defaults = null): array
    {
        $defaults = $defaults ?? [];

        $hero = $defaults['hero'] ?? [];
        $specs = $defaults['specs'] ?? [];
        $layers = $defaults['layers'] ?? [];
        $process = $defaults['process'] ?? [];
        $gallery = $defaults['gallery'] ?? [];
        $sampleCta = $defaults['sample_cta'] ?? [];

        if ($product) {
            $name = trim((string) $product->name);
            $title = trim((string) ($product->title ?? ''));
            $text = trim((string) ($product->short_description ?: $product->description));

            if ($name !== '') {
                $hero['tag'] = $name;
            }
            if ($text !== '' && $text !== $title) {
                $hero['text'] = $text;
            }

            $productSpecs = is_array($product->technical_specs) ? $product->technical_specs : [];
            if (!empty($productSpecs)) {
                $specs = array_replace_recursive($specs, $productSpecs);
            }
            if (!empty($productSpecs['hero_image'])) {
                $hero['image'] = $productSpecs['hero_image'];
            }
            if (!empty($productSpecs['hero_image_alt'])) {
                $hero['image_alt'] = $productSpecs['hero_image_alt'];
            } elseif ($name !== '') {
                $hero['image_alt'] = $name;
            }

            $productLayers = is_array($product->features) ? $product->features : [];
            if (!empty($productLayers['items'])) {
                $layers = array_replace_recursive($layers, $productLayers);
            }

            $productProcess = is_array($product->advantages) ? $product->advantages : [];
            if (!empty($productProcess['items'])) {
                $process = array_replace_recursive($process, $productProcess);
            }

            $productGallery = is_array($product->gallery) ? $product->gallery : [];
            if ($productGallery !== []) {
                $gallery['items'] = array_values(array_map(static function ($item, $index) {
                    if (is_array($item)) {
                        return [
                            'image' => $item['image'] ?? ($item['src'] ?? ''),
                            'alt' => $item['alt'] ?? ('Model örneği ' . ($index + 1)),
                            'caption' => $item['caption'] ?? ($item['alt'] ?? ('Model örneği ' . ($index + 1))),
                        ];
                    }

                    return [
                        'image' => (string) $item,
                        'alt' => 'Model örneği ' . ($index + 1),
                        'caption' => 'Model örneği ' . ($index + 1),
                    ];
                }, $productGallery, array_keys($productGallery)));
            }
        }

        return [
            'hero' => $hero,
            'specs' => $specs,
            'layers' => $layers,
            'process' => $process,
            'gallery' => $gallery,
            'sample_cta' => $sampleCta,
        ];
    }
}

if (!function_exists('setting_url')) {
    function setting_url(string $key, string $fallback = ''): string
    {
        $value = trim((string) (\App\Models\Setting::get($key, $fallback) ?? $fallback));

        if ($value === '' || $value === '#') {
            return '';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'mailto:') || str_starts_with($value, 'tel:')) {
            return $value;
        }

        if (str_starts_with($value, '/')) {
            return url($value);
        }

        if (str_contains($value, '.') || str_contains($value, '/')) {
            return url('/' . ltrim($value, '/'));
        }

        return $value;
    }
}

if (!function_exists('uploaded_file_url')) {
    function uploaded_file_url(?string $filename): string
    {
        $filename = trim((string) $filename);

        if ($filename === '') {
            return '';
        }

        if (str_starts_with($filename, 'http://') || str_starts_with($filename, 'https://')) {
            return $filename;
        }

        $relative = ltrim($filename, '/');
        $path = public_path('uploads/' . $relative);

        if (! is_file($path)) {
            $webp = preg_replace('/\.(jpe?g|png|gif|ico)$/i', '.webp', $relative);
            if (is_string($webp) && $webp !== $relative && is_file(public_path('uploads/' . $webp))) {
                $relative = $webp;
            } else {
                return '';
            }
        }

        return asset('uploads/' . $relative);
    }
}

if (!function_exists('site_logo_url')) {
    function site_logo_url(?string $fallback = null): string
    {
        $url = uploaded_file_url(\App\Models\Setting::get('site_logo'));

        if ($url !== '') {
            return $url;
        }

        return $fallback ?? '';
    }
}

if (!function_exists('has_site_logo')) {
    function has_site_logo(): bool
    {
        return site_logo_url() !== '';
    }
}

if (!function_exists('site_favicon_url')) {
    function site_favicon_url(?string $fallback = null): string
    {
        $url = uploaded_file_url(\App\Models\Setting::get('site_favicon'));

        if ($url !== '') {
            return $url;
        }

        return $fallback ?? '';
    }
}

if (!function_exists('recaptcha_site_key')) {
    function recaptcha_site_key(): string
    {
        $envKey = trim((string) config('services.recaptcha.site_key', ''));

        if ($envKey !== '') {
            return $envKey;
        }

        return trim((string) (\App\Models\Setting::get('recaptcha_site_key') ?? ''));
    }
}

if (!function_exists('recaptcha_secret_key')) {
    function recaptcha_secret_key(): string
    {
        $envKey = trim((string) config('services.recaptcha.secret_key', ''));

        if ($envKey !== '') {
            return $envKey;
        }

        return trim((string) (\App\Models\Setting::get('recaptcha_secret_key') ?? ''));
    }
}

if (!function_exists('recaptcha_enabled')) {
    function recaptcha_enabled(): bool
    {
        $siteKey = recaptcha_site_key();
        $secretKey = recaptcha_secret_key();

        if ($siteKey === '' || $secretKey === '') {
            return false;
        }

        $envSite = trim((string) config('services.recaptcha.site_key', ''));
        $envSecret = trim((string) config('services.recaptcha.secret_key', ''));

        // .env'de anahtarlar varsa otomatik aktif
        if ($envSite !== '' && $envSecret !== '') {
            $explicit = config('services.recaptcha.enabled');

            if ($explicit === false || $explicit === 0 || $explicit === '0' || $explicit === 'false') {
                return false;
            }

            return true;
        }

        return (bool) \App\Models\Setting::get('recaptcha_status');
    }
}

if (!function_exists('legal_document_url')) {
    function legal_document_url(string $pdfSettingKey, string $urlSettingKey, string $fallback = ''): string
    {
        if ($pdfSettingKey !== '') {
            $pdfUrl = uploaded_file_url(\App\Models\Setting::get($pdfSettingKey, ''));

            if ($pdfUrl !== '') {
                return $pdfUrl;
            }
        }

        $customUrl = setting_url($urlSettingKey);

        return $customUrl !== '' ? $customUrl : $fallback;
    }
}

if (!function_exists('footer_technical_support_url')) {
    function footer_technical_support_url(): string
    {
        return legal_document_url('', 'footer_technical_support_url', menu_page_url('contact'));
    }
}

if (!function_exists('footer_kvkk_url')) {
    function footer_kvkk_url(): string
    {
        return legal_document_url('', 'footer_kvkk_url', menu_page_url('kvkk'));
    }
}

if (!function_exists('footer_cookie_policy_url')) {
    function footer_cookie_policy_url(): string
    {
        return legal_document_url('legal_cookie_policy_pdf', 'legal_cookie_policy_url', menu_page_url('cookie-policy'));
    }
}

if (!function_exists('footer_terms_url')) {
    function footer_terms_url(): string
    {
        return legal_document_url('legal_terms_pdf', 'legal_terms_url', menu_page_url('terms'));
    }
}


if (!function_exists('catalog_pdf_url')) {
    /**
     * Single catalog PDF used by both header "Teknik Katalog" and footer "E-Katalog".
     */
    function catalog_pdf_url(): string
    {
        return uploaded_file_url(\App\Models\Setting::get('catalog_pdf', ''));
    }
}

if (!function_exists('footer_social_links')) {
    function footer_social_links(): array
    {
        $links = [
            ['key' => 'social_facebook', 'label' => 'FB'],
            ['key' => 'social_linkedin', 'label' => 'IN'],
            ['key' => 'social_youtube', 'label' => 'YT'],
            ['key' => 'social_instagram', 'label' => 'IG'],
            ['key' => 'social_x', 'label' => 'X'],
        ];

        return collect($links)
            ->map(function (array $item) {
                $url = setting_url($item['key']);

                return $url !== '' ? ['label' => $item['label'], 'url' => $url] : null;
            })
            ->filter()
            ->values()
            ->all();
    }
}

if (!function_exists('prefer_optimized_media_path')) {
    /**
     * Prefer sibling .webp when available (png/jpg/gif → webp).
     */
    function prefer_optimized_media_path(string $absolutePath): string
    {
        if (! is_file($absolutePath)) {
            return $absolutePath;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        if (! in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'mp4', 'mov', 'm4v'], true)) {
            return $absolutePath;
        }

        if (in_array($ext, ['mp4', 'mov', 'm4v'], true)) {
            $webm = preg_replace('/\.[^.]+$/', '.webm', $absolutePath);
            if (is_string($webm) && is_file($webm)) {
                return $webm;
            }

            return $absolutePath;
        }

        $webp = preg_replace('/\.[^.]+$/', '.webp', $absolutePath);
        if (is_string($webp) && is_file($webp)) {
            return $webp;
        }

        return $absolutePath;
    }
}

if (!function_exists('resolve_public_media_path')) {
    /**
     * Resolve a stored media path to an existing public file, with webp/png fallbacks.
     * Prefer public/assets (Silva frontend) over leftover uploads from old projects.
     */
    function resolve_public_media_path(string $normalized): ?string
    {
        $normalized = ltrim(str_replace('\\', '/', $normalized), '/');
        $candidates = [];

        if (str_starts_with($normalized, 'front-assets/')) {
            $candidates[] = public_path('assets/' . substr($normalized, strlen('front-assets/')));
        } elseif (str_starts_with($normalized, 'silvastone/')) {
            $candidates[] = public_path($normalized);
        } elseif (str_starts_with($normalized, 'assets/')) {
            $candidates[] = public_path($normalized);
        } elseif (str_starts_with($normalized, 'uploads/')) {
            $candidates[] = public_path($normalized);
        } else {
            // Exact upload first, then Silva assets — never old project leftovers first.
            $candidates[] = public_path('uploads/' . $normalized);
            $candidates[] = public_path('assets/img/' . basename($normalized));
            $candidates[] = public_path('assets/' . $normalized);
        }

        $basename = basename($normalized);
        $stem = pathinfo($basename, PATHINFO_FILENAME);
        $stem = preg_replace('/^\d+_/', '', (string) $stem) ?: (string) $stem;
        $stem = preg_replace('/^(cat_|icon_home_|icon_|page_extra_)+/i', '', $stem) ?: $stem;

        foreach ([$basename, $stem . '.webp', $stem . '.png', $stem . '.jpg', $stem . '.jpeg'] as $name) {
            $candidates[] = public_path('assets/img/' . $name);
            $candidates[] = public_path('uploads/' . $name);
        }

        $expanded = [];
        foreach ($candidates as $candidate) {
            $expanded[] = $candidate;
            $altStem = preg_replace('/\.[^.]+$/', '', $candidate);
            if (! is_string($altStem) || $altStem === '') {
                continue;
            }
            foreach (['webp', 'png', 'jpg', 'jpeg', 'gif', 'svg'] as $ext) {
                $expanded[] = $altStem . '.' . $ext;
            }
        }

        foreach ($expanded as $candidate) {
            if (is_file($candidate)) {
                return prefer_optimized_media_path($candidate);
            }
        }

        return null;
    }
}

if (!function_exists('public_path_to_asset_url')) {
    function public_path_to_asset_url(string $absolutePath): string
    {
        $public = rtrim(str_replace('\\', '/', public_path()), '/');
        $absolute = str_replace('\\', '/', $absolutePath);

        if (str_starts_with($absolute, $public . '/')) {
            return asset(ltrim(substr($absolute, strlen($public)), '/'));
        }

        return asset(basename($absolutePath));
    }
}

if (!function_exists('asset_cache_buster')) {
    /**
     * Cache-bust token for public assets (deploy version + optional filemtime).
     */
    function asset_cache_buster(?string $absolutePath = null): string
    {
        static $deploy = null;

        if ($deploy === null) {
            $deploy = trim((string) config('app.asset_version', ''));

            if ($deploy === '') {
                $headFile = base_path('.git/HEAD');
                if (is_file($headFile)) {
                    $head = trim((string) file_get_contents($headFile));
                    if (str_starts_with($head, 'ref: ')) {
                        $refPath = base_path('.git/' . trim(substr($head, 5)));
                        $deploy = is_file($refPath)
                            ? substr(trim((string) file_get_contents($refPath)), 0, 10)
                            : substr(md5($head), 0, 10);
                    } else {
                        $deploy = substr($head, 0, 10);
                    }
                }
            }

            if ($deploy === '') {
                $deploy = '1';
            }
        }

        if ($absolutePath && is_file($absolutePath)) {
            return $deploy . '.' . filemtime($absolutePath);
        }

        return $deploy;
    }
}

if (!function_exists('front_asset')) {
    function front_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $source = public_path('assets/' . $path);
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Only image paths may swap to sibling .webp
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif'], true)) {
            $source = prefer_optimized_media_path($source);
        }

        $publicRoot = rtrim(str_replace('\\', '/', public_path()), '/');
        $absolute = str_replace('\\', '/', $source);
        $relative = str_starts_with($absolute, $publicRoot . '/')
            ? ltrim(substr($absolute, strlen($publicRoot)), '/')
            : 'assets/' . $path;

        $url = asset($relative);
        $version = asset_cache_buster(is_file($source) ? $source : null);

        return $url . '?v=' . rawurlencode($version);
    }
}

if (!function_exists('homepage_media_url')) {
    function homepage_media_url(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalized = ltrim($path, '/');
        $localPath = resolve_public_media_path($normalized);

        if ($localPath && is_file($localPath)) {
            $url = public_path_to_asset_url($localPath);

            return $url . (str_contains($url, '?') ? '&' : '?') . 'v=' . rawurlencode(asset_cache_buster($localPath));
        }

        // Last resort: keep original URL shape even if missing on disk.
        if (str_starts_with($normalized, 'front-assets/')) {
            return asset('assets/' . substr($normalized, strlen('front-assets/')));
        }
        if (str_starts_with($normalized, 'silvastone/') || str_starts_with($normalized, 'assets/') || str_starts_with($normalized, 'uploads/')) {
            return asset($normalized);
        }

        return asset('uploads/' . $normalized);
    }
}

if (!function_exists('silva_asset')) {
    function silva_asset(string $path = ''): string
    {
        $path = ltrim($path, '/');
        if ($path === '') {
            return asset('silvastone');
        }
        if (str_starts_with($path, 'silvastone/')) {
            return asset($path);
        }

        return asset('silvastone/' . $path);
    }
}

if (!function_exists('silva_url')) {
    function silva_url(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '#';
        }
        if (str_starts_with($path, '#') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'mailto:') || str_starts_with($path, 'tel:')) {
            return $path;
        }
        if (str_starts_with($path, '/')) {
            return url($path);
        }
        if (str_starts_with($path, 'silvastone/')) {
            return homepage_media_url($path);
        }

        return url('/' . ltrim($path, '/'));
    }
}

if (!function_exists('homepage_intro_video_sources')) {
    /**
     * Resolve intro cinema video URLs. Uploaded files live in public/uploads/.
     *
     * @return array{webm:string,mp4:string,has:bool}
     */
    function homepage_intro_video_sources(?string $path): array
    {
        $path = trim((string) $path);
        $empty = ['webm' => '', 'mp4' => '', 'has' => false];

        if ($path === '') {
            return $empty;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $ext = strtolower(pathinfo((string) (parse_url($path, PHP_URL_PATH) ?: $path), PATHINFO_EXTENSION));
            $webm = $ext === 'webm' ? $path : '';
            $mp4 = in_array($ext, ['mp4', 'm4v', 'mov', 'ogg'], true) ? $path : '';

            return ['webm' => $webm, 'mp4' => $mp4, 'has' => $webm !== '' || $mp4 !== ''];
        }

        $resolved = resolve_public_media_path(ltrim($path, '/'));
        if (! $resolved || ! is_file($resolved)) {
            return $empty;
        }

        $dir = dirname($resolved);
        $stem = pathinfo($resolved, PATHINFO_FILENAME);
        $ext = strtolower(pathinfo($resolved, PATHINFO_EXTENSION));
        $webmFile = $dir . DIRECTORY_SEPARATOR . $stem . '.webm';
        $mp4File = $dir . DIRECTORY_SEPARATOR . $stem . '.mp4';
        $webm = is_file($webmFile)
            ? public_path_to_asset_url($webmFile) . '?v=' . rawurlencode(asset_cache_buster($webmFile))
            : '';
        $mp4 = is_file($mp4File)
            ? public_path_to_asset_url($mp4File) . '?v=' . rawurlencode(asset_cache_buster($mp4File))
            : '';

        if ($mp4 === '' && in_array($ext, ['mov', 'm4v', 'ogg'], true)) {
            $mp4 = public_path_to_asset_url($resolved) . '?v=' . rawurlencode(asset_cache_buster($resolved));
        }

        return ['webm' => $webm, 'mp4' => $mp4, 'has' => $webm !== '' || $mp4 !== ''];
    }
}

if (!function_exists('is_homepage_media_video')) {
    function is_homepage_media_video(?string $path): bool
    {
        $path = trim((string) $path);
        if ($path === '') {
            return false;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $path = parse_url($path, PHP_URL_PATH) ?: $path;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['mp4', 'webm', 'mov', 'ogg', 'm4v'], true);
    }
}

if (!function_exists('image_alt_from_src')) {
    function image_alt_from_src(?string $src): string
    {
        $src = trim((string) $src);
        if ($src === '') {
            return 'Silva Stone';
        }

        $path = parse_url($src, PHP_URL_PATH) ?: $src;
        $base = pathinfo($path, PATHINFO_FILENAME);
        $base = urldecode((string) $base);
        $base = preg_replace('/[_-]+/', ' ', $base) ?? $base;
        $base = trim(preg_replace('/\s+/', ' ', $base) ?? $base);

        $map = [
            'logo' => 'Silva Stone',
            'logobig' => 'Silva Stone',
            'logored' => 'Silva Stone',
            'logo2' => 'Silva Stone',
            'logo3' => 'Silva Stone',
            'arrowup' => 'Ok',
            'arrowdown' => 'Menü',
            'arrowbig' => 'Ok',
            'arrowbig2' => 'Ok',
            'arrowbig3' => 'Ok',
            'search' => 'Arama',
            'eye' => 'Görüntüle',
            'mainslideimg' => 'Silva Stone',
            'footerimg' => 'Silva Stone',
            'footertext' => 'Silva Stone',
            'shield' => 'Silva Stone',
            'featuresshield' => 'Silva Stone',
            'sectoralimg1' => app()->getLocale() === 'en' ? 'Sector solutions' : 'Sektörel çözüm',
            'thenew' => 'Silva Stone',
            'thenew2' => 'Silva Stone',
            'shape' => 'Silva Stone',
            'leaf' => 'Silva Stone',
            'co2' => 'CO2',
            'lock' => 'Güvenlik',
            'file' => 'Dosya',
            '3dicon' => '3D görünüm',
            'qr' => 'QR',
        ];

        $key = strtolower(str_replace(' ', '', pathinfo($path, PATHINFO_FILENAME)));
        if (isset($map[$key])) {
            return $map[$key];
        }
        foreach ($map as $needle => $label) {
            if (strlen($needle) >= 4 && str_contains($key, $needle)) {
                return $label;
            }
        }

        if ($base === '') {
            return 'Silva Stone';
        }

        return mb_convert_case($base, MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('seo_social_defaults')) {
    /**
     * @return array{title: string, description: string, image: string, url: string, type: string}
     */
    function seo_social_defaults(?object $page = null): array
    {
        $title = trim((string) (data_get($page, 'seo_title') ?: data_get($page, 'title') ?: 'Silva Stone'));
        $description = trim((string) (data_get($page, 'seo_description') ?: ''));
        if ($description === '' && class_exists(\App\Models\Setting::class)) {
            try {
                $description = (string) \App\Models\Setting::get('seo_description', 'Silva Stone duvar panelleri.');
            } catch (\Throwable $e) {
                $description = 'Silva Stone duvar panelleri.';
            }
        }
        if ($description === '') {
            $description = 'Silva Stone duvar panelleri.';
        }
        $image = front_asset('img/logobig.png');
        if (! is_file(prefer_optimized_media_path(public_path('assets/img/logobig.png')))) {
            $image = front_asset('img/svg/logo.svg');
        }

        return [
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'url' => seo_canonical_url(),
            'type' => 'website',
        ];
    }
}

if (!function_exists('seo_clean_url')) {
    function seo_clean_url(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        $parts = parse_url($url);
        if (! is_array($parts) || empty($parts['host'])) {
            return strtok($url, '?') ?: $url;
        }

        $scheme = ($parts['scheme'] ?? 'https') . '://';
        $host = $parts['host'];
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '/';

        return $scheme . $host . $port . $path;
    }
}

if (!function_exists('seo_iso_date')) {
    function seo_iso_date(mixed $value): ?string
    {
        if (! $value) {
            return null;
        }

        try {
            return \Illuminate\Support\Carbon::parse($value)->toAtomString();
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('seo_json_ld')) {
    /**
     * Schema.org JSON-LD graph for the current frontend page.
     * Does not alter existing title/description/canonical/OG tags.
     *
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    function seo_json_ld(array $context = []): array
    {
        $page = $context['page'] ?? null;
        $product = $context['product'] ?? null;
        $currentCategory = $context['currentCategory'] ?? null;
        $social = $context['social'] ?? seo_social_defaults(is_object($page) ? $page : null);

        $locale = app()->getLocale();
        $homeUrl = seo_clean_url(home_url($locale));
        $pageUrl = seo_clean_url($social['url'] ?? seo_canonical_url());
        $title = trim((string) ($context['title'] ?? $social['title'] ?? 'Silva Stone'));
        $description = trim((string) ($context['description'] ?? $social['description'] ?? ''));
        $image = seo_clean_url($context['image'] ?? $social['image'] ?? front_asset('img/logobig.png'));

        if ($product) {
            $title = trim((string) ($product->seo_title ?: (trim(($product->name ?? '') . ' | Silva Stone') ?: $title)));
            $description = trim((string) ($product->seo_description ?: $product->short_description ?: $product->description ?: $description));
            if (! empty($product->main_image)) {
                $image = seo_clean_url(homepage_media_url($product->main_image)) ?: $image;
            }
        } elseif ($currentCategory) {
            $title = trim((string) ($currentCategory->seo_title ?: $currentCategory->name ?: $title));
            $description = trim((string) ($currentCategory->seo_description ?: $currentCategory->description ?: $description));
            $categoryImage = $currentCategory->image ?: $currentCategory->icon_home;
            if (! empty($categoryImage)) {
                $image = seo_clean_url(homepage_media_url($categoryImage)) ?: $image;
            }
        }

        $pageType = (string) (data_get($page, 'type') ?: '');

        $orgId = $homeUrl . '#organization';
        $websiteId = $homeUrl . '#website';
        $webpageId = $pageUrl . '#webpage';

        $sameAs = array_values(array_filter(array_map(
            fn ($item) => $item['url'] ?? null,
            footer_social_links()
        )));

        $organization = array_filter([
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => 'Silva Stone',
            'url' => $homeUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => seo_clean_url(site_logo_url(front_asset('img/svg/logo.svg'))),
            ],
            'image' => $image,
            'sameAs' => $sameAs ?: null,
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);

        $website = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $homeUrl,
            'name' => 'Silva Stone',
            'inLanguage' => $locale,
            'publisher' => ['@id' => $orgId],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url($locale . '/arama') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        $published = seo_iso_date(
            $context['datePublished']
                ?? data_get($product, 'created_at')
                ?? data_get($page, 'created_at')
                ?? data_get($currentCategory, 'created_at')
        );
        $modified = seo_iso_date(
            $context['dateModified']
                ?? data_get($product, 'updated_at')
                ?? data_get($page, 'updated_at')
                ?? data_get($currentCategory, 'updated_at')
                ?? $published
        );

        $graph = [$organization, $website];
        $breadcrumbs = [
            ['name' => 'Silva Stone', 'item' => $homeUrl],
        ];

        if ($product) {
            $productName = trim((string) ($product->name ?? $title));
            $productDesc = trim((string) ($product->seo_description ?: $product->short_description ?: $product->description ?: $description));
            $productImage = $product->main_image
                ? seo_clean_url(homepage_media_url($product->main_image))
                : $image;

            $productNode = array_filter([
                '@type' => 'Product',
                '@id' => $pageUrl . '#product',
                'name' => $productName,
                'description' => $productDesc !== '' ? $productDesc : null,
                'image' => $productImage !== '' ? [$productImage] : null,
                'sku' => (string) ($product->id ?? ''),
                'url' => $pageUrl,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => 'Silva Stone',
                ],
                'category' => $product->category?->name ?: null,
                'dateModified' => $modified,
                'datePublished' => $published,
            ], fn ($v) => $v !== null && $v !== '' && $v !== []);

            $graph[] = $productNode;

            $breadcrumbs[] = [
                'name' => __t('header_products', 'Ürünlerimiz', 'frontend'),
                'item' => seo_clean_url(m_url('products')),
            ];
            if ($product->category) {
                $breadcrumbs[] = [
                    'name' => (string) $product->category->name,
                    'item' => seo_clean_url(m_url('products', $product->category->slug)),
                ];
            }
            $breadcrumbs[] = ['name' => $productName, 'item' => $pageUrl];

            $webpageType = 'ItemPage';
        } else {
            $webpageType = match ($pageType) {
                'index' => 'WebPage',
                'contact' => 'ContactPage',
                'corporate' => 'AboutPage',
                'products' => 'CollectionPage',
                'get_quote', 'application' => 'WebPage',
                default => 'WebPage',
            };

            if ($pageType === 'products' || $currentCategory) {
                $breadcrumbs[] = [
                    'name' => __t('header_products', 'Ürünlerimiz', 'frontend'),
                    'item' => seo_clean_url(m_url('products')),
                ];
                if ($currentCategory) {
                    $breadcrumbs[] = [
                        'name' => (string) ($currentCategory->name ?? $title),
                        'item' => $pageUrl,
                    ];
                }
            } elseif ($pageType && $pageType !== 'index') {
                $breadcrumbs[] = ['name' => $title, 'item' => $pageUrl];
            }
        }

        $graph[] = array_filter([
            '@type' => $webpageType === 'ItemPage' ? 'WebPage' : $webpageType,
            '@id' => $webpageId,
            'url' => $pageUrl,
            'name' => $title,
            'description' => $description !== '' ? $description : null,
            'inLanguage' => $locale,
            'isPartOf' => ['@id' => $websiteId],
            'about' => isset($product) && $product ? ['@id' => $pageUrl . '#product'] : null,
            'primaryImageOfPage' => $image !== '' ? [
                '@type' => 'ImageObject',
                'url' => $image,
            ] : null,
            'datePublished' => $published,
            'dateModified' => $modified,
            'publisher' => ['@id' => $orgId],
        ], fn ($v) => $v !== null && $v !== '' && $v !== []);

        if (count($breadcrumbs) > 1) {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $pageUrl . '#breadcrumb',
                'itemListElement' => array_values(array_map(function (array $crumb, int $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'name' => $crumb['name'],
                        'item' => $crumb['item'],
                    ];
                }, $breadcrumbs, array_keys($breadcrumbs))),
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => array_values($graph),
        ];
    }
}

if (!function_exists('lang_url')) {
    /**
     * Generate URL with current or specified locale.
     * 
     * @param string $url
     * @param string|null $lang
     * @return string
     */
    function lang_url($url = '', $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        
        // Remove current lang prefix if exists
        $segments = explode('/', ltrim($url, '/'));
        if (count($segments) > 0 && strlen($segments[0]) == 2) {
            array_shift($segments);
        }

        if (count($segments) > 0) {
            $segment0 = $segments[0];

            // 1. Check if Segment 0 is a dynamic Page slug in any language
            $pageTranslation = \App\Models\PageTranslation::where('slug', $segment0)->first();
            if ($pageTranslation) {
                $targetTranslation = \App\Models\PageTranslation::where('page_id', $pageTranslation->page_id)
                    ->where('lang_key', $lang)
                    ->first();
                if ($targetTranslation && $targetTranslation->slug) {
                    $segments[0] = $targetTranslation->slug;
                }

                if (isset($segments[1]) && $segments[1] !== '') {
                    $pageType = \App\Models\Page::whereKey($pageTranslation->page_id)->value('type');

                    if ($pageType === 'products') {
                        $segment1 = $segments[1];
                        if (is_products_all_slug($segment1)) {
                            $segments[1] = products_all_slug($lang);
                        } else {
                            $prodCatTranslation = \App\Models\ProductCategoryTranslation::where('slug', $segment1)->first();
                            if ($prodCatTranslation) {
                                $targetCat = \App\Models\ProductCategoryTranslation::where('product_category_id', $prodCatTranslation->product_category_id)
                                    ->where('lang_key', $lang)
                                    ->first();
                                if ($targetCat?->slug) {
                                    $segments[1] = $targetCat->slug;
                                }
                            } else {
                                $prodTranslation = \App\Models\ProductTranslation::where('slug', $segment1)->first();
                                if ($prodTranslation) {
                                    $targetProd = \App\Models\ProductTranslation::where('product_id', $prodTranslation->product_id)
                                        ->where('lang_key', $lang)
                                        ->first();
                                    if ($targetProd?->slug) {
                                        $segments[1] = $targetProd->slug;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // 2. Check if it matches a static module route default segment
                $defaults = [
                    'products'      => ['tr' => 'urunler', 'en' => 'products', 'ru' => 'produkty'],
                    'product'       => ['tr' => 'urun', 'en' => 'product', 'ru' => 'produkt'],
                    'contact'       => ['tr' => 'iletisim', 'en' => 'contact', 'ru' => 'kontakt'],
                    'get_quote'     => ['tr' => 'teklif-al', 'en' => 'get-quote', 'ru' => 'poluchit-tsenu'],
                    'corporate'     => ['tr' => 'kurumsal', 'en' => 'corporate', 'ru' => 'korporativnyy'],
                    'search'        => ['tr' => 'arama', 'en' => 'search', 'ru' => 'poisk'],
                ];

                $matchedKey = null;
                foreach ($defaults as $key => $langs) {
                    if (in_array($segment0, $langs)) {
                        $matchedKey = $key;
                        $defaultVal = $langs[$lang] ?? ($langs['en'] ?? $key);
                        $segments[0] = \App\Models\StaticTranslation::get('route_' . $key, $defaultVal, 'routes', $lang);
                        break;
                    }
                }

                // 3. Resolve Segment 1 (sub-slug) if present
                if (count($segments) > 1 && $segments[1]) {
                    $segment1 = $segments[1];
                    
                    if ($matchedKey === 'products' || $matchedKey === 'product') {
                        if (is_products_all_slug($segment1)) {
                            $segments[1] = products_all_slug($lang);
                        } else {
                        // Product Category
                        $prodCatTranslation = \App\Models\ProductCategoryTranslation::where('slug', $segment1)->first();
                        if ($prodCatTranslation) {
                            $targetTranslation = \App\Models\ProductCategoryTranslation::where('product_category_id', $prodCatTranslation->product_category_id)
                                ->where('lang_key', $lang)
                                ->first();
                            if ($targetTranslation && $targetTranslation->slug) {
                                $segments[1] = $targetTranslation->slug;
                            }
                        } else {
                            // Product
                            $prodTranslation = \App\Models\ProductTranslation::where('slug', $segment1)->first();
                            if ($prodTranslation) {
                                $targetTranslation = \App\Models\ProductTranslation::where('product_id', $prodTranslation->product_id)
                                    ->where('lang_key', $lang)
                                    ->first();
                                if ($targetTranslation && $targetTranslation->slug) {
                                    $segments[1] = $targetTranslation->slug;
                                }
                            }
                        }
                        }
                    }
                }
            }
        }
        
        $newUrl = implode('/', $segments);
        return url($lang . ($newUrl ? '/' . $newUrl : ''));
    }
}
if (!function_exists('m_url')) {
    /**
     * Generate translated module URL.
     * 
     * @param string $module
     * @param string|null $slug
     * @param string|null $lang
     * @return string
     */
    function m_url($module, $slug = null, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        
        // 1. Check if this module is a defined Page type
        $page = \App\Models\Page::where('type', $module)->first();
        if ($page) {
            $pageTranslation = $page->translations()->where('lang_key', $lang)->first();
            if ($pageTranslation && $pageTranslation->slug) {
                $url = $lang . '/' . $pageTranslation->slug;
                if ($slug) {
                    $url .= '/' . $slug;
                }
                return url($url);
            }
        }

        // 2. Fallback to Static Translations (group: routes)
        $defaults = [
            'products'      => ['tr' => 'urunler', 'en' => 'products', 'ru' => 'produkty'],
            'product'       => ['tr' => 'urun', 'en' => 'product', 'ru' => 'produkt'],
            'contact'       => ['tr' => 'iletisim', 'en' => 'contact', 'ru' => 'kontakt'],
            'get_quote'     => ['tr' => 'b2b-toptan', 'en' => 'b2b-wholesale', 'ru' => 'b2b'],
            'privacy_policy'=> ['tr' => 'gizlilik-politikasi', 'en' => 'privacy-policy', 'ru' => 'konfidencialnost'],
            'return_policy' => ['tr' => 'iade-kosullari', 'en' => 'return-policy', 'ru' => 'vozvrat'],
            'shipping_policy'=> ['tr' => 'kargo-teslimat', 'en' => 'shipping-delivery', 'ru' => 'dostavka'],
            'references'    => ['tr' => 'referanslar', 'en' => 'references', 'ru' => 'referensy'],
            'projects'      => ['tr' => 'projeler', 'en' => 'projects', 'ru' => 'proekty'],
            'project'       => ['tr' => 'proje', 'en' => 'project', 'ru' => 'proekt'],
            'faq'           => ['tr' => 'sss', 'en' => 'faq', 'ru' => 'faq'],
            'login'         => ['tr' => 'giris', 'en' => 'login', 'ru' => 'vhod'],
            'search'        => ['tr' => 'arama', 'en' => 'search', 'ru' => 'poisk'],
            'corporate'     => ['tr' => 'kurumsal', 'en' => 'corporate', 'ru' => 'korporativnyy'],
        ];

        $defaultVal = $defaults[$module][$lang] ?? ($defaults[$module]['en'] ?? $module);
        $modulePath = \App\Models\StaticTranslation::get('route_' . $module, $defaultVal, 'routes', $lang);

        $url = $lang . '/' . $modulePath;
        if ($slug) {
            $url .= '/' . $slug;
        }
        return url($url);
    }
}

if (!function_exists('products_all_slug')) {
    function products_all_slug(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();
        $defaults = ['tr' => 'tum-urunler', 'en' => 'all-products', 'ru' => 'vse-produkty'];

        return \App\Models\StaticTranslation::get(
            'route_products_all',
            $defaults[$lang] ?? 'all-products',
            'routes',
            $lang
        );
    }
}

if (!function_exists('is_products_all_slug')) {
    function is_products_all_slug(?string $slug): bool
    {
        if (!$slug) {
            return false;
        }

        $reserved = ['tum-urunler', 'all-products', 'vse-produkty', 'all', 'tum-teknolojiler', 'all-technologies', 'vse-tehnologii'];
        if (in_array($slug, $reserved, true)) {
            return true;
        }

        foreach (\App\Models\Language::active()->pluck('code') as $langCode) {
            if ($slug === products_all_slug($langCode)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('products_all_url')) {
    function products_all_url(?string $lang = null): string
    {
        return m_url('products', 'all', $lang);
    }
}

if (!function_exists('localized_route')) {
    /**
     * Named route with lang param; throws clear error if route missing.
     */
    function localized_route(string $name, array $parameters = [], ?string $lang = null): string
    {
        if (!\Illuminate\Support\Facades\Route::has($name)) {
            throw new \InvalidArgumentException("Route [{$name}] is not defined.");
        }

        return route($name, array_merge(['lang' => $lang ?? app()->getLocale()], $parameters));
    }
}

if (!function_exists('home_url')) {
    function home_url(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();

        return url($lang);
    }
}

if (!function_exists('contact_form_url')) {
    function contact_form_url(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();

        return url($lang . '/iletisim');
    }
}

if (!function_exists('page_public_url')) {
  function page_public_url(\App\Models\Page $page, ?string $lang = null): string
  {
    $lang = $lang ?? app()->getLocale();

    if ($page->type && $page->type !== 'generic') {
      return m_url($page->type, null, $lang);
    }

    $trans = $page->translations()->where('lang_key', $lang)->first();
    if ($trans && ! empty($trans->slug)) {
      return url($lang . '/' . $trans->slug);
    }

    if (! empty($page->slug)) {
      return url($lang . '/' . $page->slug);
    }

    return url($lang);
  }
}

if (!function_exists('menu_page_url')) {
    function menu_page_url(string $type): string
    {
        $lang = app()->getLocale();
        $knownTypes = [
            'corporate', 'contact', 'application', 'solution', 'sustainability', 'kvkk', 'cookie-policy', 'terms',
            'products',
        ];

        if (in_array($type, $knownTypes, true)) {
            return m_url($type, null, $lang);
        }

        return url($lang);
    }
}

if (!function_exists('solution_page_url')) {
    function solution_page_url(?string $key = null, ?string $lang = null): string
    {
        $url = rtrim(menu_page_url('solution') ?: url(($lang ?? app()->getLocale()) . '/sektorel-cozumler'), '/');
        $key = trim((string) $key);
        if ($key === '' || $key === '#') {
            return $url;
        }

        return $url . '#' . ltrim($key, '#');
    }
}

if (!function_exists('technology_url')) {
    function technology_url(?string $slug = null, ?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();
        $slug = trim((string) $slug);
        $aliases = [
            'pu' => 'pu-enjeksiyon-sistemleri',
            'eva' => 'eva-faylon-urunler',
            'memory' => 'acik-hucreli-memory-sungerler',
            'insole' => 'ic-tabanlik-sistemleri',
            'gel' => 'jel-destek-sistemleri',
            'esd' => 'esd-antistatik-tabanliklar',
            'lamination' => 'teknik-laminasyon-uygulamalari',
            'thermoform' => 'thermoform-sunger-cozumleri',
            'fabric' => 'teknik-kumas-sistemleri',
            'steel' => 'celik-ve-koruyucu-astarliklar',
            'print' => 'markalama-ve-baski-teknolojileri',
            'packaging' => 'ambalaj-promosyon-endustriyel-sungerler',
        ];

        if ($slug !== '' && isset($aliases[$slug])) {
            $slug = $aliases[$slug];
        }

        if ($slug === '' || $slug === '#') {
            return m_url('products', null, $lang);
        }

        $match = \App\Models\ProductTranslation::where('slug', $slug)->first();
        if ($match) {
            $localized = \App\Models\ProductTranslation::where('product_id', $match->product_id)
                ->where('lang_key', $lang)
                ->first();
            if ($localized && trim((string) $localized->slug) !== '') {
                $slug = $localized->slug;
            }
        }

        return m_url('products', $slug, $lang);
    }
}

if (!function_exists('ini_size_to_bytes')) {
    function ini_size_to_bytes(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($value, -1));
        $number = (float) $value;

        return (int) match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => $number,
        };
    }
}

if (!function_exists('upload_limits')) {
    function upload_limits(): array
    {
        return [
            'upload_max_filesize' => ini_get('upload_max_filesize') ?: '16M',
            'post_max_size' => ini_get('post_max_size') ?: '20M',
            'post_max_bytes' => ini_size_to_bytes(ini_get('post_max_size') ?: '20M'),
            'upload_max_bytes' => ini_size_to_bytes(ini_get('upload_max_filesize') ?: '16M'),
        ];
    }
}

if (!function_exists('seo_canonical_url')) {
    /**
     * Absolute canonical URL for the current (or given) page.
     * Keeps meaningful query params (search q); drops the rest.
     */
    function seo_canonical_url(?string $url = null): string
    {
        if ($url) {
            return str_starts_with($url, 'http://') || str_starts_with($url, 'https://')
                ? $url
                : url($url);
        }

        $base = url()->current();
        $query = seo_localized_query(app()->getLocale());

        return $query === [] ? $base : $base . '?' . http_build_query($query);
    }
}

if (!function_exists('seo_localized_query')) {
    /**
     * Meaningful query params translated for the target locale (category, q).
     */
    function seo_localized_query(?string $lang = null): array
    {
        $lang = $lang ?? app()->getLocale();
        $query = [];

        $categorySlug = trim((string) request()->query('category', ''));
        if ($categorySlug !== '') {
            $source = \App\Models\ProductCategoryTranslation::where('slug', $categorySlug)->first();
            $target = $source
                ? \App\Models\ProductCategoryTranslation::where('product_category_id', $source->product_category_id)
                    ->where('lang_key', $lang)
                    ->first()
                : null;

            if ($target?->slug) {
                $query['category'] = $target->slug;
            } elseif ($lang === app()->getLocale()) {
                $query['category'] = $categorySlug;
            }
        }

        $q = trim((string) request()->query('q', ''));
        if ($q !== '') {
            $query['q'] = $q;
        }

        return $query;
    }
}

if (!function_exists('seo_hreflang_alternates')) {
    /**
     * Alternate language URLs with translated route + entity slugs.
     *
     * @return list<array{hreflang: string, href: string}>
     */
    function seo_hreflang_alternates(?string $path = null): array
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('languages')) {
            return [];
        }

        $path = $path ?? request()->path();
        $alternates = [];

        foreach (\App\Models\Language::active() as $language) {
            $href = lang_url($path, $language->code);
            $query = seo_localized_query($language->code);
            if ($query !== []) {
                $href .= (str_contains($href, '?') ? '&' : '?') . http_build_query($query);
            }

            $alternates[] = [
                'hreflang' => $language->code,
                'href' => $href,
            ];
        }

        $default = \App\Models\Language::default();
        if ($default) {
            $href = lang_url($path, $default->code);
            $query = seo_localized_query($default->code);
            if ($query !== []) {
                $href .= (str_contains($href, '?') ? '&' : '?') . http_build_query($query);
            }

            $alternates[] = [
                'hreflang' => 'x-default',
                'href' => $href,
            ];
        }

        return $alternates;
    }
}
