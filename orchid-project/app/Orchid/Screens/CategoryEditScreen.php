<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryEditScreen extends Screen
{
    protected ?Category $categoryModel = null;

    private const LOCALES = ['lv', 'en', 'ru'];

    /**
     * Fetch data to be displayed on the screen.
     */
    public function query(Category $category): iterable
    {
        $this->categoryModel = $category;
        $categoryData = [];

        foreach (self::LOCALES as $locale) {
            $translation = $category->translate($locale);
            $categoryData[$locale] = [
                'name' => (string) ($translation?->name ?? ''),
                'slug' => (string) ($translation?->slug ?? ''),
                'description' => (string) ($translation?->description ?? ''),
                'meta_title' => (string) ($translation?->meta_title ?? ''),
                'meta_description' => (string) ($translation?->meta_description ?? ''),
                'og_description' => (string) ($translation?->og_description ?? ''),
                'twitter_description' => (string) ($translation?->twitter_description ?? ''),
                'meta_keywords' => (string) ($translation?->meta_keywords ?? ''),
            ];
        }

        return [
            'category' => $categoryData,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->categoryModel?->exists ? 'Edit Category' : 'Create Category';
    }

    /**
     * The screen's action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->method('save'),
            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee((bool) $this->categoryModel?->exists),
        ];
    }

    /**
     * The screen's layout elements.
     */
    public function layout(): iterable
    {
        $localeTabs = [];

        foreach (self::LOCALES as $locale) {
            $label = strtoupper($locale);
            $localeTabs[$label] = Layout::rows([
                Input::make("category.{$locale}.name")
                    ->title("Name ({$label})")
                    ->required($locale === 'lv'),
                Input::make("category.{$locale}.slug")
                    ->title("Slug ({$label})")
                    ->placeholder('Auto generated from name if empty')
                    ->help('Unique inside locale.'),
                TextArea::make("category.{$locale}.description")
                    ->title("Description ({$label})")
                    ->rows(4),
                Input::make("category.{$locale}.meta_title")
                    ->title("Meta Title ({$label})"),
                TextArea::make("category.{$locale}.meta_description")
                    ->title("Meta Description ({$label})")
                    ->rows(3),
                TextArea::make("category.{$locale}.og_description")
                    ->title("OG Description ({$label})")
                    ->rows(3),
                TextArea::make("category.{$locale}.twitter_description")
                    ->title("Twitter Description ({$label})")
                    ->rows(3),
                Input::make("category.{$locale}.meta_keywords")
                    ->title("Meta Keywords ({$label})")
                    ->placeholder('keyword1, keyword2'),
            ]);
        }

        return [
            Layout::tabs($localeTabs),
        ];
    }

    public function save(Request $request)
    {
        if (! $this->categoryModel?->exists) {
            $routeCategory = $request->route('category');

            if ($routeCategory instanceof Category) {
                $this->categoryModel = $routeCategory;
            } elseif (is_numeric($routeCategory)) {
                $this->categoryModel = Category::query()->find((int) $routeCategory);
            }
        }

        $this->categoryModel ??= new Category();
        $payload = (array) $request->input('category', []);

        foreach (self::LOCALES as $locale) {
            $localePayload = (array) ($payload[$locale] ?? []);
            $inputSlug = trim((string) ($localePayload['slug'] ?? ''));
            $localePayload['slug'] = $inputSlug !== '' ? (Str::slug($inputSlug) ?: null) : null;
            $payload[$locale] = $localePayload;
        }

        $request->merge(['category' => $payload]);

        $rules = [];
        foreach (self::LOCALES as $locale) {
            $rules["category.{$locale}.name"] = $locale === 'lv' ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules["category.{$locale}.slug"] = [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ];
            $rules["category.{$locale}.description"] = 'nullable|string';
            $rules["category.{$locale}.meta_title"] = 'nullable|string|max:255';
            $rules["category.{$locale}.meta_description"] = 'nullable|string';
            $rules["category.{$locale}.og_description"] = 'nullable|string';
            $rules["category.{$locale}.twitter_description"] = 'nullable|string';
            $rules["category.{$locale}.meta_keywords"] = 'nullable|string';
        }

        $validated = $request->validate($rules);
        $data = array_replace_recursive($payload, (array) ($validated['category'] ?? []));

        foreach (self::LOCALES as $locale) {
            $localePayload = (array) ($data[$locale] ?? []);
            if (blank($localePayload['slug'] ?? null) && filled($localePayload['name'] ?? null)) {
                $localePayload['slug'] = $this->makeUniqueSlugForLocale((string) $localePayload['name'], $locale, $this->categoryModel?->id);
            }
            $data[$locale] = $localePayload;
        }

        $slugErrors = [];
        foreach (self::LOCALES as $locale) {
            $slug = trim((string) data_get($data, "{$locale}.slug", ''));
            if ($slug === '') {
                continue;
            }

            $exists = CategoryTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->when($this->categoryModel?->id, fn ($query) => $query->where('category_id', '!=', $this->categoryModel?->id))
                ->exists();

            if ($exists) {
                $slugErrors["category.{$locale}.slug"] = "Slug '{$slug}' already exists for locale {$locale}.";
            }
        }

        if ($slugErrors !== []) {
            throw ValidationException::withMessages($slugErrors);
        }

        $this->categoryModel->fill(array_intersect_key($data, array_flip(self::LOCALES)));
        $this->categoryModel->save();

        Toast::success('Category saved.');

        return redirect()->route('platform.category.list');
    }

    public function remove(Category $category)
    {
        if (! $category->exists) {
            return redirect()->route('platform.category.list');
        }

        $category->delete();
        Toast::success('Category removed.');

        return redirect()->route('platform.category.list');
    }

    private function makeUniqueSlugForLocale(string $value, string $locale, ?int $ignoreCategoryId = null): string
    {
        $baseSlug = Str::slug($value);
        if ($baseSlug === '') {
            $baseSlug = 'category';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (
            CategoryTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->when($ignoreCategoryId, fn ($query) => $query->where('category_id', '!=', $ignoreCategoryId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
