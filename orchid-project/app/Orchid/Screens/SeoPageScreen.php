<?php

namespace App\Orchid\Screens;

use App\Models\SeoPage;
use App\Models\SeoPageTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SeoPageScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     */
    public function query(): iterable
    {
        $defaults = seo_page_defaults();
        $locales = seo_supported_locales();
        $data = [];

        if (! Schema::hasTable('seo_pages') || ! Schema::hasTable('seo_page_translations')) {
            foreach ($defaults as $pageKey => $config) {
                $data[$pageKey] = ['og_image' => []];

                foreach ($locales as $locale) {
                    $data[$pageKey][$locale] = [
                        'slug' => (string) data_get($config, 'slugs.'.$locale, ''),
                        'meta_title' => '',
                        'meta_description' => '',
                        'og_description' => '',
                        'twitter_description' => '',
                        'meta_keywords' => '',
                    ];
                }
            }

            return ['seo' => $data];
        }

        foreach ($defaults as $pageKey => $config) {
            $page = SeoPage::query()->firstOrCreate(['page_key' => $pageKey]);

            $data[$pageKey] = [
                'og_image' => $page->og_image,
            ];

            foreach ($locales as $locale) {
                $translation = $page->translate($locale);
                $data[$pageKey][$locale] = [
                    'slug' => (string) ($translation?->slug ?? data_get($config, 'slugs.'.$locale, '')),
                    'meta_title' => (string) ($translation?->meta_title ?? ''),
                    'meta_description' => (string) ($translation?->meta_description ?? ''),
                    'og_description' => (string) ($translation?->og_description ?? ''),
                    'twitter_description' => (string) ($translation?->twitter_description ?? ''),
                    'meta_keywords' => (string) ($translation?->meta_keywords ?? ''),
                ];
            }
        }

        return [
            'seo' => $data,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'SEO Settings';
    }

    /**
     * The screen's action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     */
    public function layout(): iterable
    {
        $defaults = seo_page_defaults();
        $locales = seo_supported_locales();
        $pageTabs = [];

        foreach ($defaults as $pageKey => $config) {
            $label = (string) data_get($config, 'label', $pageKey);
            $localeTabs = [];

            foreach ($locales as $locale) {
                $localeLabel = strtoupper($locale);
                $localeTabs["{$label} {$localeLabel}"] = Layout::rows([
                    Input::make("seo.{$pageKey}.{$locale}.slug")
                        ->title("Slug ({$localeLabel})")
                        ->help('If empty, will be generated from Meta Title.'),
                    Input::make("seo.{$pageKey}.{$locale}.meta_title")
                        ->title("Meta Title ({$localeLabel})"),
                    TextArea::make("seo.{$pageKey}.{$locale}.meta_description")
                        ->title("Meta Description ({$localeLabel})")
                        ->rows(3),
                    TextArea::make("seo.{$pageKey}.{$locale}.og_description")
                        ->title("OG Description ({$localeLabel})")
                        ->rows(3),
                    TextArea::make("seo.{$pageKey}.{$locale}.twitter_description")
                        ->title("Twitter Description ({$localeLabel})")
                        ->rows(3),
                    Input::make("seo.{$pageKey}.{$locale}.meta_keywords")
                        ->title("Meta Keywords ({$localeLabel})")
                        ->help('Comma separated'),
                ]);
            }

            $pageTabs[$label] = [
                Layout::rows([
                    Upload::make("seo.{$pageKey}.og_image")
                        ->title('OG Image')
                        ->acceptedFiles('image/*')
                        ->maxFiles(1),
                ])->title("{$label} Shared"),
                Layout::tabs($localeTabs),
            ];
        }

        return [
            Layout::tabs($pageTabs),
        ];
    }

    public function save(Request $request): void
    {
        if (! Schema::hasTable('seo_pages') || ! Schema::hasTable('seo_page_translations')) {
            Toast::error('Run migrations first: php artisan migrate');

            return;
        }

        $defaults = seo_page_defaults();
        $locales = seo_supported_locales();
        $payload = (array) $request->input('seo', []);
        $slugIndex = [];
        $errors = [];

        foreach ($defaults as $pageKey => $config) {
            foreach ($locales as $locale) {
                $metaTitle = trim((string) data_get($payload, "{$pageKey}.{$locale}.meta_title", ''));
                $requestedSlug = trim((string) data_get($payload, "{$pageKey}.{$locale}.slug", ''));
                $defaultSlug = (string) data_get($config, "slugs.{$locale}", '');
                $slugSource = $requestedSlug !== '' ? $requestedSlug : ($metaTitle !== '' ? $metaTitle : $defaultSlug);
                $slug = trim(Str::slug($slugSource));

                if ($slug === '') {
                    $slug = null;
                }

                data_set($payload, "{$pageKey}.{$locale}.slug", $slug);

                if ($slug === null) {
                    continue;
                }

                if (isset($slugIndex[$locale][$slug]) && $slugIndex[$locale][$slug] !== $pageKey) {
                    $errors["seo.{$pageKey}.{$locale}.slug"] = "Duplicate slug in {$locale}: {$slug}";
                } else {
                    $slugIndex[$locale][$slug] = $pageKey;
                }
            }
        }

        foreach ($slugIndex as $locale => $slugs) {
            if ($slugs === []) {
                continue;
            }

            $existing = SeoPageTranslation::query()
                ->select('seo_page_translations.slug', 'seo_pages.page_key')
                ->join('seo_pages', 'seo_pages.id', '=', 'seo_page_translations.seo_page_id')
                ->where('seo_page_translations.locale', $locale)
                ->whereIn('seo_page_translations.slug', array_keys($slugs))
                ->get();

            foreach ($existing as $row) {
                $targetPageKey = $slugs[$row->slug] ?? null;
                if ($targetPageKey !== null && $targetPageKey !== $row->page_key) {
                    $errors["seo.{$targetPageKey}.{$locale}.slug"] = "Slug already used in DB for {$locale}: {$row->slug}";
                }
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        foreach (array_keys($defaults) as $pageKey) {
            $page = SeoPage::query()->firstOrNew(['page_key' => $pageKey]);
            $fields = [
                'og_image' => (array) data_get($payload, "{$pageKey}.og_image", []),
            ];

            foreach ($locales as $locale) {
                $fields[$locale] = [
                    'slug' => data_get($payload, "{$pageKey}.{$locale}.slug"),
                    'meta_title' => data_get($payload, "{$pageKey}.{$locale}.meta_title"),
                    'meta_description' => data_get($payload, "{$pageKey}.{$locale}.meta_description"),
                    'og_description' => data_get($payload, "{$pageKey}.{$locale}.og_description"),
                    'twitter_description' => data_get($payload, "{$pageKey}.{$locale}.twitter_description"),
                    'meta_keywords' => data_get($payload, "{$pageKey}.{$locale}.meta_keywords"),
                ];
            }

            $page->fill($fields)->save();
        }

        Toast::success('SEO settings updated.');
    }
}
