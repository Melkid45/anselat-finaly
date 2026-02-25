<?php

namespace App\Orchid\Screens;

use App\Models\Counter;
use App\Models\Hero;
use App\Models\Partners;
use App\Models\WorkBlock;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class HomePageScreen extends Screen
{
    private const LOCALES = ['lv', 'en', 'ru'];

    public function query(): iterable
    {
        $hero = Hero::firstOrCreate([]);
        $partners = Partners::firstOrCreate([]);
        $counter = Counter::firstOrCreate([]);
        $workBlock = WorkBlock::firstOrCreate([]);

        return [
            'hero' => $this->localizedPayload($hero, ['first_title', 'second_title', 'description'], ['image']),
            'partners' => $this->localizedPayload($partners, ['title'], ['logos']),
            'counter' => $this->localizedPayload($counter, ['items']),
            'work_block' => $this->localizedPayload($workBlock, ['title']),
        ];
    }

    public function name(): ?string
    {
        return 'Home Page Information';
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
                'Hero LV' => Layout::rows([
                    Input::make('hero.lv.first_title')->title('First Title (LV)'),
                    Input::make('hero.lv.second_title')->title('Second Title (LV)'),
                    TextArea::make('hero.lv.description')->title('Description (LV)')->rows(5),
                    Upload::make('hero.image')->title('Image')->acceptedFiles('image/*')->maxFiles(1),
                ]),
                'Hero EN' => Layout::rows([
                    Input::make('hero.en.first_title')->title('First Title (EN)'),
                    Input::make('hero.en.second_title')->title('Second Title (EN)'),
                    TextArea::make('hero.en.description')->title('Description (EN)')->rows(5),
                ]),
                'Hero RU' => Layout::rows([
                    Input::make('hero.ru.first_title')->title('First Title (RU)'),
                    Input::make('hero.ru.second_title')->title('Second Title (RU)'),
                    TextArea::make('hero.ru.description')->title('Description (RU)')->rows(5),
                ]),
            ]),
            Layout::tabs([
                'Partners LV' => Layout::rows([
                    Input::make('partners.lv.title')->title('Partners Title (LV)'),
                    Upload::make('partners.logos')->title('Partner Logos')->acceptedFiles('image/*'),
                ]),
                'Partners EN' => Layout::rows([
                    Input::make('partners.en.title')->title('Partners Title (EN)'),
                ]),
                'Partners RU' => Layout::rows([
                    Input::make('partners.ru.title')->title('Partners Title (RU)'),
                ]),
            ]),
            Layout::tabs([
                'Work Block LV' => Layout::rows([
                    Input::make('work_block.lv.title')->title('Work Block Title (LV)'),
                ]),
                'Work Block EN' => Layout::rows([
                    Input::make('work_block.en.title')->title('Work Block Title (EN)'),
                ]),
                'Work Block RU' => Layout::rows([
                    Input::make('work_block.ru.title')->title('Work Block Title (RU)'),
                ]),
            ]),
            Layout::tabs([
                'Counter LV' => Layout::rows([
                    Matrix::make('counter.lv.items')
                        ->title('Counter Items (LV)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->fields([
                            Input::make('title')->title('Title'),
                            TextArea::make('description')->title('Description')->rows(3),
                        ])
                        ->button('Add Counter Item'),
                ]),
                'Counter EN' => Layout::rows([
                    Matrix::make('counter.en.items')
                        ->title('Counter Items (EN)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->fields([
                            Input::make('title')->title('Title'),
                            TextArea::make('description')->title('Description')->rows(3),
                        ])
                        ->button('Add Counter Item'),
                ]),
                'Counter RU' => Layout::rows([
                    Matrix::make('counter.ru.items')
                        ->title('Counter Items (RU)')
                        ->columns([
                            'title' => 'Title',
                            'description' => 'Description',
                        ])
                        ->fields([
                            Input::make('title')->title('Title'),
                            TextArea::make('description')->title('Description')->rows(3),
                        ])
                        ->button('Add Counter Item'),
                ]),
            ]),
        ];
    }

    public function save(Request $request): void
    {
        $request->validate([
            'hero.lv.first_title' => 'required|string',
            'hero.lv.second_title' => 'required|string',
            'partners.lv.title' => 'required|string',
            'work_block.lv.title' => 'required|string',
        ]);

        $hero = Hero::firstOrNew([]);
        $heroPayload = (array) $request->input('hero', []);
        $hero->fill($this->prepareLocalizedPayload($heroPayload, ['image']))->save();

        $partners = Partners::firstOrNew([]);
        $partnersPayload = (array) $request->input('partners', []);
        $partners->fill($this->prepareLocalizedPayload($partnersPayload, ['logos']))->save();

        $counter = Counter::firstOrNew([]);
        $counterPayload = (array) $request->input('counter', []);
        $counter->fill($this->prepareLocalizedPayload($counterPayload))->save();

        $workBlock = WorkBlock::firstOrNew([]);
        $workBlockPayload = (array) $request->input('work_block', []);
        $workBlock->fill($this->prepareLocalizedPayload($workBlockPayload))->save();

        Toast::success('Home Page Information saved successfully!');
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
