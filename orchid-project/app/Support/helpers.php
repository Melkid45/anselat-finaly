<?php

use App\Models\SeoPage;
use App\Models\SeoPageTranslation;
use App\Models\Category;
use App\Models\Works;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Orchid\Attachment\Models\Attachment;

if (! function_exists('attachment_url')) {
    function attachment_url(mixed $model, string $field, ?string $default = null): ?string
    {
        $ids = (array) data_get($model, $field, []);
        $id = $ids[0] ?? null;

        return $id ? (Attachment::find($id)?->url() ?? $default) : $default;
    }
}

if (! function_exists('attachment_urls')) {
    function attachment_urls(mixed $model, string $field): array
    {
        $ids = (array) data_get($model, $field, []);

        if (empty($ids)) {
            return [];
        }

        return Attachment::query()
            ->whereIn('id', $ids)
            ->get()
            ->map(fn (Attachment $attachment) => $attachment->url())
            ->filter()
            ->values()
            ->all();
    }
}

if (! function_exists('seo_page_defaults')) {
    function seo_page_defaults(): array
    {
        return [
            'home' => [
                'label' => 'Home',
                'slugs' => ['lv' => null, 'en' => null, 'ru' => null],
            ],
            'about' => [
                'label' => 'About',
                'slugs' => ['lv' => 'par-mums', 'en' => 'about-us', 'ru' => 'o-nas'],
            ],
            'works' => [
                'label' => 'Works',
                'slugs' => ['lv' => 'works', 'en' => 'works', 'ru' => 'works'],
            ],
            'material' => [
                'label' => 'Materials',
                'slugs' => ['lv' => 'materiali', 'en' => 'materials', 'ru' => 'materialy'],
            ],
            'contacts' => [
                'label' => 'Contacts',
                'slugs' => ['lv' => 'kontakti', 'en' => 'contacts', 'ru' => 'kontakty'],
            ],
            'work' => [
                'label' => 'Work',
                'slugs' => ['lv' => null, 'en' => null, 'ru' => null],
            ],
        ];
    }
}

if (! function_exists('seo_supported_locales')) {
    function seo_supported_locales(): array
    {
        $locales = config('translatable.locales', ['lv', 'en', 'ru']);
        $locales = array_values(array_filter($locales, 'is_string'));

        return $locales !== [] ? $locales : ['lv', 'en', 'ru'];
    }
}

if (! function_exists('seo_page')) {
    function seo_page(string $pageKey): ?SeoPage
    {
        if (! Schema::hasTable('seo_pages')) {
            return null;
        }

        return SeoPage::query()->where('page_key', $pageKey)->first();
    }
}

if (! function_exists('seo_slug')) {
    function seo_slug(string $pageKey, ?string $locale = null, ?string $fallback = null): ?string
    {
        $locale ??= app()->getLocale();
        $page = seo_page($pageKey);
        $slug = $page?->translate($locale)?->slug;

        if (filled($slug)) {
            return $slug;
        }

        $default = data_get(seo_page_defaults(), $pageKey.'.slugs.'.$locale);
        if (filled($default)) {
            return (string) $default;
        }

        return $fallback;
    }
}

if (! function_exists('resolve_static_page_key_by_slug')) {
    function resolve_static_page_key_by_slug(?string $slug, ?string $locale = null): ?string
    {
        if (! filled($slug)) {
            return null;
        }

        $locale ??= app()->getLocale();
        $slug = trim((string) $slug, '/');

        $translation = null;
        if (Schema::hasTable('seo_page_translations')) {
            $translation = SeoPageTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->first();
        }

        if ($translation) {
            return $translation->seoPage?->page_key;
        }

        foreach (seo_page_defaults() as $pageKey => $config) {
            if (data_get($config, 'slugs.'.$locale) === $slug) {
                return $pageKey;
            }
        }

        return null;
    }
}

if (! function_exists('page_url')) {
    function page_url(string $pageKey, array $params = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($pageKey === 'home') {
            return route('home', ['locale' => $locale]);
        }

        if ($pageKey === 'works') {
            $categorySlug = (string) ($params['categorySlug'] ?? '');
            if ($categorySlug !== '') {
                return route('works.category', [
                    'locale' => $locale,
                    'worksPageSlug' => seo_slug('works', $locale, 'works'),
                    'categorySlug' => $categorySlug,
                ]);
            }

            $fallback = 'works';
            $slug = seo_slug('works', $locale, $fallback);

            return route('page.static', ['locale' => $locale, 'pageSlug' => $slug]);
        }

        if (in_array($pageKey, ['about', 'material', 'contacts'], true)) {
            $fallback = Str::slug($pageKey);
            $slug = seo_slug($pageKey, $locale, $fallback);

            return route('page.static', ['locale' => $locale, 'pageSlug' => $slug]);
        }

        return route('home', ['locale' => $locale]);
    }
}

if (! function_exists('current_page_key')) {
    function current_page_key(): ?string
    {
        $routeName = Route::currentRouteName();
        $route = request()->route();

        return match ($routeName) {
            'home' => 'home',
            'works.category' => 'works',
            'works' => 'works',
            'work.show' => 'work',
            'page.static' => resolve_static_page_key_by_slug((string) ($route?->parameter('pageSlug') ?? '')),
            default => null,
        };
    }
}

if (! function_exists('localized_current_url')) {
    function localized_current_url(string $locale): string
    {
        $routeName = Route::currentRouteName();
        $route = request()->route();

        if ($routeName === 'page.static') {
            $pageKey = current_page_key();
            if ($pageKey) {
                return page_url($pageKey, [], $locale);
            }
        }

        if ($routeName === 'works' || $routeName === 'works.category') {
            $currentLocale = app()->getLocale();
            $currentCategorySlug = (string) ($route?->parameter('categorySlug') ?? '');
            $targetCategorySlug = null;

            if ($currentCategorySlug !== '') {
                $category = Category::query()
                    ->whereHas('translations', fn ($query) => $query
                        ->where('locale', $currentLocale)
                        ->where('slug', $currentCategorySlug))
                    ->first();

                if ($category) {
                    $targetCategorySlug = (string) ($category->translate($locale)?->slug ?: $currentCategorySlug);
                } else {
                    $targetCategorySlug = $currentCategorySlug;
                }
            }

            return page_url('works', [
                'categorySlug' => $targetCategorySlug,
            ], $locale);
        }

        if ($routeName === 'work.show') {
            return route('work.show', [
                'locale' => $locale,
                'categorySlug' => (function () use ($route, $locale) {
                    $currentLocale = app()->getLocale();
                    $currentCategorySlug = (string) ($route?->parameter('categorySlug') ?? '');

                    if ($currentCategorySlug === '') {
                        return $currentCategorySlug;
                    }

                    $category = Category::query()
                        ->whereHas('translations', fn ($query) => $query
                            ->where('locale', $currentLocale)
                            ->where('slug', $currentCategorySlug))
                        ->first();

                    if (! $category) {
                        return $currentCategorySlug;
                    }

                    return (string) ($category->translate($locale)?->slug ?: $currentCategorySlug);
                })(),
                'workSlug' => (function () use ($route, $locale) {
                    $currentLocale = app()->getLocale();
                    $currentWorkSlug = (string) ($route?->parameter('workSlug') ?? '');
                    $categorySlug = (string) ($route?->parameter('categorySlug') ?? '');

                    $work = Works::query()
                        ->whereHas('translations', fn ($query) => $query
                            ->where('locale', $currentLocale)
                            ->where('slug', $currentWorkSlug))
                        ->whereHas('workCategory', fn ($query) => $query
                            ->whereHas('translations', fn ($translationQuery) => $translationQuery
                                ->where('locale', $currentLocale)
                                ->where('slug', $categorySlug)))
                        ->first();

                    if (! $work) {
                        return $currentWorkSlug;
                    }

                    return (string) ($work->translate($locale)?->slug ?: $currentWorkSlug);
                })(),
                'worksPageSlug' => seo_slug('works', $locale, 'works'),
            ]);
        }

        if ($routeName === 'home') {
            return page_url('home', [], $locale);
        }

        return route('home', ['locale' => $locale]);
    }
}

if (! function_exists('seo_image_url')) {
    function seo_image_url(?SeoPage $page): ?string
    {
        if (! $page) {
            return null;
        }

        $url = attachment_url($page, 'og_image');
        if (! filled($url)) {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return url($url);
    }
}

if (! function_exists('current_seo_meta')) {
    function current_seo_meta(): array
    {
        $locale = app()->getLocale();
        $routeName = Route::currentRouteName();
        $route = request()->route();
        $appName = config('app.name', 'Anselat');

        if ($routeName === 'work.show') {
            $workSlug = (string) ($route?->parameter('workSlug') ?? '');
            $categorySlug = (string) ($route?->parameter('categorySlug') ?? '');

            $work = Works::query()
                ->with('workCategory')
                ->whereHas('translations', fn ($query) => $query
                    ->where('locale', $locale)
                    ->where('slug', $workSlug))
                ->whereHas('workCategory', fn ($query) => $query
                    ->whereHas('translations', fn ($translationQuery) => $translationQuery
                        ->where('locale', $locale)
                        ->where('slug', $categorySlug)))
                ->first();

            if ($work) {
                $title = (string) ($work->meta_title ?: $work->name ?: $appName);
                $description = (string) ($work->meta_description ?: $work->description ?: '');
                $ogDescription = (string) ($work->og_description ?: $description);
                $twitterDescription = (string) ($work->twitter_description ?: $description);
                $keywords = (string) ($work->meta_keywords ?: '');
                $ogImage = attachment_url($work, 'og_image') ?: attachment_url($work, 'preview');

                if (filled($ogImage) && ! Str::startsWith($ogImage, ['http://', 'https://'])) {
                    $ogImage = url($ogImage);
                }

                return [
                    'title' => $title,
                    'description' => $description,
                    'og_description' => $ogDescription,
                    'twitter_description' => $twitterDescription,
                    'keywords' => $keywords,
                    'og_image' => $ogImage,
                    'canonical' => url()->current(),
                ];
            }
        }

        if ($routeName === 'works' || $routeName === 'works.category') {
            $categorySlug = (string) ($route?->parameter('categorySlug') ?? '');
            if ($categorySlug !== '') {
                $category = Category::query()
                    ->whereHas('translations', fn ($query) => $query
                        ->where('locale', $locale)
                        ->where('slug', $categorySlug))
                    ->first();

                if (! $category) {
                    $category = Category::query()
                        ->whereHas('translations', fn ($query) => $query->where('slug', $categorySlug))
                        ->first();
                }

                if ($category) {
                    $translation = $category->translate($locale);
                    $fallbackTranslation = $category->translate(config('app.fallback_locale', 'lv'))
                        ?? $category->translations->first();
                    $categoryName = (string) ($translation?->name ?: $fallbackTranslation?->name ?: 'Category');
                    $title = (string) ($translation?->meta_title ?: $fallbackTranslation?->meta_title ?: ($appName.' - '.$categoryName));
                    $description = (string) ($translation?->meta_description ?: $fallbackTranslation?->meta_description ?: $translation?->description ?: $fallbackTranslation?->description ?: '');
                    $ogDescription = (string) ($translation?->og_description ?: $fallbackTranslation?->og_description ?: $description);
                    $twitterDescription = (string) ($translation?->twitter_description ?: $fallbackTranslation?->twitter_description ?: $description);
                    $keywords = (string) ($translation?->meta_keywords ?: $fallbackTranslation?->meta_keywords ?: '');

                    return [
                        'title' => $title,
                        'description' => $description,
                        'og_description' => $ogDescription,
                        'twitter_description' => $twitterDescription,
                        'keywords' => $keywords,
                        'og_image' => null,
                        'canonical' => url()->current(),
                    ];
                }
            }
        }

        $pageKey = current_page_key();
        $defaults = seo_page_defaults();

        $titleFallback = $appName;
        if ($pageKey) {
            $label = (string) data_get($defaults, $pageKey.'.label', '');
            if ($label !== '' && $pageKey !== 'home') {
                $titleFallback = $appName.' - '.$label;
            }
        }

        $page = $pageKey ? seo_page($pageKey) : null;
        $translation = $page?->translate($locale);

        $title = (string) ($translation?->meta_title ?: $titleFallback);
        $description = (string) ($translation?->meta_description ?: '');
        $ogDescription = (string) ($translation?->og_description ?: $description);
        $twitterDescription = (string) ($translation?->twitter_description ?: $description);
        $keywords = (string) ($translation?->meta_keywords ?: '');

        return [
            'title' => $title,
            'description' => $description,
            'og_description' => $ogDescription,
            'twitter_description' => $twitterDescription,
            'keywords' => $keywords,
            'og_image' => seo_image_url($page),
            'canonical' => url()->current(),
        ];
    }
}

if (! function_exists('category_route_slug')) {
    function category_route_slug(?Category $category, ?string $locale = null): ?string
    {
        if (! $category) {
            return null;
        }

        $locale ??= app()->getLocale();

        return (string) (
            $category->translate($locale)?->slug
            ?? $category->translate(config('app.fallback_locale', 'lv'))?->slug
            ?? $category->translations->first()?->slug
            ?? ''
        ) ?: null;
    }
}

if (! function_exists('work_route_slug')) {
    function work_route_slug(?Works $work, ?string $locale = null): ?string
    {
        if (! $work) {
            return null;
        }

        $locale ??= app()->getLocale();

        return (string) (
            $work->translate($locale)?->slug
            ?? $work->translate(config('app.fallback_locale', 'lv'))?->slug
            ?? $work->translations->first()?->slug
            ?? ''
        ) ?: null;
    }
}

if (! function_exists('work_url')) {
    function work_url(?Works $work, ?string $locale = null): string
    {
        if (! $work) {
            return page_url('works', [], $locale);
        }

        $locale ??= app()->getLocale();
        $categorySlug = category_route_slug($work->workCategory, $locale);
        $workSlug = work_route_slug($work, $locale);

        if (! $categorySlug || ! $workSlug) {
            return page_url('works', [], $locale);
        }

        return route('work.show', [
            'locale' => $locale,
            'worksPageSlug' => seo_slug('works', $locale, 'works'),
            'categorySlug' => $categorySlug,
            'workSlug' => $workSlug,
        ]);
    }
}
