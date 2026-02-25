<?php

namespace App\Orchid\Screens;

use App\Models\Contact;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ContactScreen extends Screen
{
    private const LOCALES = ['lv', 'en', 'ru'];

    public function query(): iterable
    {
        $contact = Contact::firstOrCreate([]);
        $request = ModelsRequest::firstOrCreate([]);

        return [
            'contact' => $contact,
            'request' => $this->localizedPayload($request, ['title', 'description'], ['images']),
        ];
    }

    public function name(): ?string
    {
        return 'Contact Information';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('contact.address')->title('Address'),
                Input::make('contact.address_link')->title('Address Link'),
                Input::make('contact.address_iframe')->title('Address Iframe'),
                Input::make('contact.email')->title('Email'),
                Input::make('contact.phone')->title('Phone'),
                Input::make('contact.time')->title('Working Hours'),
                Input::make('contact.facebook')->title('Facebook Link'),
                Input::make('contact.instagram')->title('Instagram Link'),
            ])->title('Contact Information'),
            Layout::tabs([
                'Request LV' => Layout::rows([
                    Input::make('request.lv.title')->title('Request Title (LV)'),
                    TextArea::make('request.lv.description')->title('Request Description (LV)')->rows(5),
                    Upload::make('request.images')->title('Request Images')->maxFiles(1),
                ]),
                'Request EN' => Layout::rows([
                    Input::make('request.en.title')->title('Request Title (EN)'),
                    TextArea::make('request.en.description')->title('Request Description (EN)')->rows(5),
                ]),
                'Request RU' => Layout::rows([
                    Input::make('request.ru.title')->title('Request Title (RU)'),
                    TextArea::make('request.ru.description')->title('Request Description (RU)')->rows(5),
                ]),
            ]),
        ];
    }

    public function save(Request $request): void
    {
        $request->validate([
            'request.lv.title' => 'required|string|max:255',
        ]);

        $contact = Contact::firstOrNew([]);
        $contact->fill($request->get('contact'))->save();

        $requestModel = ModelsRequest::firstOrNew([]);
        $requestModel->fill($this->prepareLocalizedPayload((array) $request->input('request', []), ['images']))->save();

        Toast::success('Contact and request information saved successfully!');
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
