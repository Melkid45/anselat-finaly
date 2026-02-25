<?php

namespace App\Orchid\Screens;

use App\Models\Category;
use App\Models\Works;
use App\Models\WorksTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class WorkEditScreen extends Screen
{
    protected ?Works $workModel = null;

    private const LOCALES = ['lv', 'en', 'ru'];

    /**
     * Fetch data to be displayed on the screen.
     */
    public function query(Works $works): iterable
    {
        $this->workModel = $works;

        $workData = [
            'view_on_home_page' => (bool) $works->view_on_home_page,
            'category_id' => $works->category_id,
            'preview' => $works->preview,
            'gallery' => $works->gallery,
            'og_image' => $works->og_image,
        ];
        $extra = [];

        foreach (self::LOCALES as $locale) {
            $translation = $works->translate($locale);
            $normalizedInfo = collect((array) ($translation?->info ?? []))
                ->map(function ($item): array {
                    $row = (array) $item;
                    $title = $this->extractRowField($row, 'title');
                    $description = $this->extractRowField($row, 'description');

                    return [
                        'Title' => $title,
                        'Description' => $description,
                    ];
                })
                ->values()
                ->all();

            $workData[$locale] = [
                'name' => (string) ($translation?->name ?? ''),
                'slug' => (string) ($translation?->slug ?? ''),
                'description' => (string) ($translation?->description ?? ''),
                'about_title' => (string) ($translation?->about_title ?? ''),
                'client' => (string) ($translation?->client ?? ''),
                'date' => (string) ($translation?->date ?? ''),
                'place' => (string) ($translation?->place ?? ''),
                'meta_title' => (string) ($translation?->meta_title ?? ''),
                'meta_description' => (string) ($translation?->meta_description ?? ''),
                'og_description' => (string) ($translation?->og_description ?? ''),
                'twitter_description' => (string) ($translation?->twitter_description ?? ''),
                'meta_keywords' => (string) ($translation?->meta_keywords ?? ''),
            ];

            $extra["works_info_{$locale}"] = $normalizedInfo;
        }

        return array_merge([
            'works' => $workData,
        ], $extra);
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->workModel?->exists ? 'Edit work' : 'Creating a new work';
    }

    /**
     * The screen's action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(! $this->workModel?->exists),
            Button::make('Update')
                ->icon('bs.check-circle')
                ->method('createOrUpdate')
                ->canSee((bool) $this->workModel?->exists),
            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee((bool) $this->workModel?->exists),
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
            $localeTabs[$label] = [
                Layout::rows([
                    Input::make("works.{$locale}.name")
                        ->title("Name ({$label})")
                        ->placeholder('Title of the work'),
                    Input::make("works.{$locale}.slug")
                        ->title("Slug ({$label})")
                        ->placeholder('Auto generated from name if empty')
                        ->help('Used in frontend URL. Must be unique inside locale.'),
                    TextArea::make("works.{$locale}.description")
                        ->title("Description ({$label})")
                        ->rows(4),
                    Input::make("works.{$locale}.about_title")
                        ->title("About block title ({$label})"),
                    Group::make([
                        Input::make("works.{$locale}.client")
                            ->title("Client ({$label})"),
                        Input::make("works.{$locale}.date")
                            ->title("Date ({$label})"),
                        Input::make("works.{$locale}.place")
                            ->title("Place ({$label})"),
                    ])->fullWidth(),
                    Matrix::make("works_info_{$locale}")
                        ->title("Info block ({$label})")
                        ->columns([
                            'Title' => 'Title',
                            'Description' => 'Description',
                        ])
                        ->fields([
                            'Title' => Input::make('Title'),
                            'Description' => TextArea::make('Description'),
                        ])
                        ->button('Add info item'),
                ]),
                Layout::rows([
                    Input::make("works.{$locale}.meta_title")
                        ->title("Meta Title ({$label})"),
                    TextArea::make("works.{$locale}.meta_description")
                        ->title("Meta Description ({$label})")
                        ->rows(3),
                    TextArea::make("works.{$locale}.og_description")
                        ->title("OG Description ({$label})")
                        ->rows(3),
                    TextArea::make("works.{$locale}.twitter_description")
                        ->title("Twitter Description ({$label})")
                        ->rows(3),
                    Input::make("works.{$locale}.meta_keywords")
                        ->title("Meta Keywords ({$label})")
                        ->placeholder('keyword1, keyword2'),
                ])->title('SEO'),
            ];
        }

        return [
            Layout::rows([
                Select::make('works.category_id')
                    ->title('Category')
                    ->options(
                        Category::query()
                            ->get()
                            ->mapWithKeys(fn (Category $category) => [
                                $category->id => (string) (
                                    $category->translate('lv')?->name
                                    ?? $category->translate('en')?->name
                                    ?? $category->name
                                    ?? ('#'.$category->id)
                                ),
                            ])
                            ->toArray()
                    )
                    ->empty('Select category')
                    ->required(),
                Upload::make('works.preview')
                    ->title('Preview Image')
                    ->maxFiles(1),
                Upload::make('works.gallery')
                    ->title('Gallery')
                    ->maxFiles(10),
                Upload::make('works.og_image')
                    ->title('OG Image')
                    ->maxFiles(1)
                    ->acceptedFiles('image/*'),
            ])->title('Shared fields'),
            Layout::tabs($localeTabs),
        ];
    }

    public function createOrUpdate(Request $request)
    {
        if (! $this->workModel?->exists) {
            $routeWork = $request->route('works');

            if ($routeWork instanceof Works) {
                $this->workModel = $routeWork;
            } elseif (is_numeric($routeWork)) {
                $this->workModel = Works::query()->find((int) $routeWork);
            }
        }

        $this->workModel ??= new Works();
        $worksData = (array) $request->input('works', []);

        foreach (self::LOCALES as $locale) {
            $localePayload = (array) ($worksData[$locale] ?? []);
            $requestedSlug = trim((string) ($localePayload['slug'] ?? ''));

            if ($requestedSlug !== '') {
                $slug = Str::slug($requestedSlug);
                $localePayload['slug'] = $slug !== '' ? $slug : null;
            } else {
                $localePayload['slug'] = null;
            }

            $worksData[$locale] = $localePayload;
        }

        $request->merge(['works' => $worksData]);

        $rules = [
            'works.category_id' => 'required|integer|exists:category,id',
            'works.preview' => 'nullable|array',
            'works.preview.*' => 'integer|exists:attachments,id',
            'works.gallery' => 'nullable|array',
            'works.gallery.*' => 'integer|exists:attachments,id',
            'works.og_image' => 'nullable|array',
            'works.og_image.*' => 'integer|exists:attachments,id',
        ];

        foreach (self::LOCALES as $locale) {
            $rules["works.{$locale}.name"] = $locale === 'lv' ? 'required|string|max:255' : 'nullable|string|max:255';
            $rules["works.{$locale}.slug"] = [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            ];
            $rules["works.{$locale}.description"] = 'nullable|string';
            $rules["works.{$locale}.about_title"] = 'nullable|string|max:255';
            $rules["works.{$locale}.client"] = 'nullable|string|max:255';
            $rules["works.{$locale}.date"] = 'nullable|string|max:255';
            $rules["works.{$locale}.place"] = 'nullable|string';
            $rules["works_info_{$locale}"] = 'nullable|array';
            $rules["works_info_{$locale}.*.title"] = 'nullable|string|max:255';
            $rules["works_info_{$locale}.*.description"] = 'nullable|string';
            $rules["works_info_{$locale}.*.Title"] = 'nullable|string|max:255';
            $rules["works_info_{$locale}.*.Description"] = 'nullable|string';
            $rules["works.{$locale}.meta_title"] = 'nullable|string|max:255';
            $rules["works.{$locale}.meta_description"] = 'nullable|string';
            $rules["works.{$locale}.og_description"] = 'nullable|string';
            $rules["works.{$locale}.twitter_description"] = 'nullable|string';
            $rules["works.{$locale}.meta_keywords"] = 'nullable|string';
        }

        $validated = $request->validate($rules);
        $data = array_replace_recursive(
            $worksData,
            (array) ($validated['works'] ?? [])
        );

        foreach (self::LOCALES as $locale) {
            $localePayload = (array) ($data[$locale] ?? []);
            $existingInfo = (array) ($this->workModel->translate($locale)?->info ?? []);
            $incomingInfo = $request->input("works_info_{$locale}");
            $localePayload['info'] = $this->normalizeInfoRows($incomingInfo, $existingInfo);

            if (blank($localePayload['slug'] ?? null) && filled($localePayload['name'] ?? null)) {
                $localePayload['slug'] = $this->makeUniqueSlugForLocale((string) $localePayload['name'], $locale, $this->workModel?->id);
            }

            $data[$locale] = $localePayload;
        }

        $slugErrors = [];
        foreach (self::LOCALES as $locale) {
            $slug = trim((string) data_get($data, "{$locale}.slug", ''));
            if ($slug === '') {
                continue;
            }

            $exists = WorksTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $slug)
                ->when($this->workModel?->id, fn ($query) => $query->where('works_id', '!=', $this->workModel?->id))
                ->exists();

            if ($exists) {
                $slugErrors["works.{$locale}.slug"] = "Slug '{$slug}' already exists for locale {$locale}.";
            }
        }

        if ($slugErrors !== []) {
            throw ValidationException::withMessages($slugErrors);
        }

        $shared = [
            'category_id' => $data['category_id'] ?? null,
            'preview' => $data['preview'] ?? [],
            'gallery' => $data['gallery'] ?? [],
            'og_image' => $data['og_image'] ?? [],
        ];

        $this->workModel->fill(array_merge($shared, array_intersect_key($data, array_flip(self::LOCALES))));
        $this->workModel->save();

        Toast::success('Work was saved.');

        return redirect()->route('platform.works.list');
    }

    public function remove(Works $works)
    {
        if (! $works->exists) {
            return redirect()->route('platform.works.list');
        }

        $works->delete();

        Toast::success('Work was removed.');

        return redirect()->route('platform.works.list');
    }

    private function makeUniqueSlugForLocale(string $value, string $locale, ?int $ignoreWorkId = null): string
    {
        $baseSlug = Str::slug($value);
        if ($baseSlug === '') {
            $baseSlug = 'work';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (
            \App\Models\WorksTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->when($ignoreWorkId, fn($query) => $query->where('works_id', '!=', $ignoreWorkId))
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function normalizeInfoRows(mixed $incomingInfo, array $fallback): array
    {
        if ($incomingInfo === null) {
            return $fallback;
        }

        $rows = collect((array) $incomingInfo)
            ->map(function ($item): array {
                $row = (array) $item;

                return [
                    'title' => $this->extractRowField($row, 'title'),
                    'description' => $this->extractRowField($row, 'description'),
                ];
            })
            ->filter(fn (array $row) => $row['title'] !== '' || $row['description'] !== '')
            ->values()
            ->all();

        return $rows;
    }

    private function extractScalarText(mixed $value): string
    {
        if (is_string($value) || is_numeric($value)) {
            return (string) $value;
        }

        if (! is_array($value)) {
            return '';
        }

        foreach ($value as $item) {
            $text = $this->extractScalarText($item);
            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }

    private function extractRowField(array $row, string $field): string
    {
        $candidates = [
            $row[$field] ?? null,
            $row[ucfirst($field)] ?? null,
            data_get($row, "columns.{$field}"),
            data_get($row, 'columns.'.ucfirst($field)),
        ];

        foreach ($candidates as $candidate) {
            $text = trim($this->extractScalarText($candidate));
            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }
}
