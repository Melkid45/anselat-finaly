<?php

namespace App\Orchid\Screens;

use App\Models\MaterialPage;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MaterialPageScreen extends Screen
{
    private const LOCALES = ['lv', 'en', 'ru'];

    public function query(): iterable
    {
        $materialPage = MaterialPage::firstOrCreate([]);

        return [
            'materialPage' => $this->localizedPayload($materialPage, ['title', 'soft_title', 'description'], ['images']),
        ];
    }

    public function name(): ?string
    {
        return 'Material Page Information';
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
                'Material LV' => Layout::rows([
                    Input::make('materialPage.lv.title')->title('Title (LV)'),
                    Input::make('materialPage.lv.soft_title')->title('Soft Title (LV)'),
                    TextArea::make('materialPage.lv.description')->title('Description (LV)'),
                    Upload::make('materialPage.images')->title('Images')->maxFiles(1),
                ]),
                'Material EN' => Layout::rows([
                    Input::make('materialPage.en.title')->title('Title (EN)'),
                    Input::make('materialPage.en.soft_title')->title('Soft Title (EN)'),
                    TextArea::make('materialPage.en.description')->title('Description (EN)'),
                ]),
                'Material RU' => Layout::rows([
                    Input::make('materialPage.ru.title')->title('Title (RU)'),
                    Input::make('materialPage.ru.soft_title')->title('Soft Title (RU)'),
                    TextArea::make('materialPage.ru.description')->title('Description (RU)'),
                ]),
            ]),
        ];
    }

    public function save(Request $request): void
    {
        $request->validate([
            'materialPage.lv.title' => 'required|string|max:255',
        ]);

        $materialPage = MaterialPage::firstOrNew([]);
        $materialPage->fill($this->prepareLocalizedPayload((array) $request->input('materialPage', []), ['images']));
        $materialPage->save();

        Toast::success('Material page information saved successfully!');
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
                $payload[$locale][$field] = data_get($translation, $field, '');
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
