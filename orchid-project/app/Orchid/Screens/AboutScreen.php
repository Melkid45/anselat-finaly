<?php

namespace App\Orchid\Screens;

use App\Models\About;
use App\Models\Company;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AboutScreen extends Screen
{
    private const LOCALES = ['lv', 'en', 'ru'];

    public function query(): iterable
    {
        $about = About::firstOrCreate([]);
        $company = Company::firstOrCreate([]);

        return [
            'about' => $this->localizedPayload($about, ['title', 'description'], ['images']),
            'company' => $this->localizedPayload($company, ['title', 'items'], ['images']),
        ];
    }

    public function name(): ?string
    {
        return 'About Information';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'About LV' => Layout::rows([
                    Input::make('about.lv.title')->title('Title (LV)'),
                    TextArea::make('about.lv.description')->title('Description (LV)'),
                    Upload::make('about.images')->title('Image')->maxFiles(1),
                ]),
                'About EN' => Layout::rows([
                    Input::make('about.en.title')->title('Title (EN)'),
                    TextArea::make('about.en.description')->title('Description (EN)'),
                ]),
                'About RU' => Layout::rows([
                    Input::make('about.ru.title')->title('Title (RU)'),
                    TextArea::make('about.ru.description')->title('Description (RU)'),
                ]),
            ]),
            Layout::tabs([
                'Company LV' => Layout::rows([
                    Input::make('company.lv.title')->title('Company Title (LV)'),
                    Upload::make('company.images')->title('Company Images')->maxFiles(5),
                    Matrix::make('company.lv.items')
                        ->title('Company Items (LV)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->button('Add Item')
                        ->fields([
                            Input::make('title')->title('Title'),
                            Input::make('description')->title('Description'),
                        ]),
                ]),
                'Company EN' => Layout::rows([
                    Input::make('company.en.title')->title('Company Title (EN)'),
                    Matrix::make('company.en.items')
                        ->title('Company Items (EN)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->button('Add Item')
                        ->fields([
                            Input::make('title')->title('Title'),
                            Input::make('description')->title('Description'),
                        ]),
                ]),
                'Company RU' => Layout::rows([
                    Input::make('company.ru.title')->title('Company Title (RU)'),
                    Matrix::make('company.ru.items')
                        ->title('Company Items (RU)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->button('Add Item')
                        ->fields([
                            Input::make('title')->title('Title'),
                            Input::make('description')->title('Description'),
                        ]),
                ]),
            ]),
        ];
    }

    public function save(Request $request): void
    {
        $request->validate([
            'about.lv.title' => 'required|string|max:255',
            'company.lv.title' => 'required|string|max:255',
        ]);

        $about = About::firstOrNew([]);
        $about->fill($this->prepareLocalizedPayload((array) $request->input('about', []), ['images']));
        $about->save();

        $company = Company::firstOrNew([]);
        $company->fill($this->prepareLocalizedPayload((array) $request->input('company', []), ['images']));
        $company->save();

        Toast::success('About information saved successfully!');
    }

    private function localizedPayload(object $model, array $fields, array $sharedFields = []): array
    {
        $payload = [];

        foreach ($sharedFields as $field) {
            $payload[$field] = data_get($model, $field);
        }

        foreach (self::LOCALES as $locale) {
            $translation = method_exists($model, 'translate') ? $model->translate($locale) : null;
            foreach ($fields as $field) {
                $payload[$locale][$field] = data_get($translation, $field, is_array(data_get($translation, $field)) ? [] : '');
            }
        }

        return $payload;
    }

    private function prepareLocalizedPayload(array $payload, array $sharedFields = []): array
    {
        $result = [];

        foreach ($sharedFields as $field) {
            $result[$field] = $payload[$field] ?? null;
        }

        foreach (self::LOCALES as $locale) {
            $result[$locale] = is_array($payload[$locale] ?? null) ? $payload[$locale] : [];
        }

        return $result;
    }
}
