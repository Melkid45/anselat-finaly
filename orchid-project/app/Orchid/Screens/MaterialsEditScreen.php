<?php

namespace App\Orchid\Screens;

use App\Models\Materials;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MaterialsEditScreen extends Screen
{
    private const LOCALES = ['lv', 'en', 'ru'];

    public ?Materials $material = null;

    public function query(Materials $materials): iterable
    {
        $this->material = $materials;

        return [
            'materials' => $this->localizedPayload($materials, ['name', 'description'], ['image']),
        ];
    }

    public function name(): ?string
    {
        return $this->materialModel()->exists ? 'Edit material' : 'Creating a new material';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(! $this->materialModel()->exists),
            Button::make('Update')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->materialModel()->exists),
            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->materialModel()->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::tabs([
                'LV' => Layout::rows([
                    Input::make('materials.lv.name')->title('Name (LV)')->placeholder('Enter the name of the material'),
                    TextArea::make('materials.lv.description')->title('Description (LV)')->placeholder('Enter a description of the material'),
                    Upload::make('materials.image')->title('Image')->help('Upload an image representing the material.'),
                ]),
                'EN' => Layout::rows([
                    Input::make('materials.en.name')->title('Name (EN)')->placeholder('Enter the name of the material'),
                    TextArea::make('materials.en.description')->title('Description (EN)')->placeholder('Enter a description of the material'),
                ]),
                'RU' => Layout::rows([
                    Input::make('materials.ru.name')->title('Name (RU)')->placeholder('Enter the name of the material'),
                    TextArea::make('materials.ru.description')->title('Description (RU)')->placeholder('Enter a description of the material'),
                ]),
            ]),
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $request->validate([
            'materials.lv.name' => 'required|string|max:255',
        ]);

        $payload = (array) $request->input('materials', []);
        $this->materialModel()->fill($this->prepareLocalizedPayload($payload, ['image']))->save();

        Toast::success('Material was saved.');

        return redirect()->route('platform.materials.list');
    }

    public function remove()
    {
        $material = $this->materialModel();
        if ($material->exists) {
            $material->delete();
        }

        Toast::success('Material was removed.');

        return redirect()->route('platform.materials.list');
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

    private function materialModel(): Materials
    {
        return $this->material ??= new Materials();
    }
}
