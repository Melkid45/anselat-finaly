<?php

namespace App\Orchid\Screens;

use App\Models\WorksPage;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class WorkPageScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $worksPage = WorksPage::firstOrCreate([]);
        return [
            'worksPage' => $worksPage,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Works Page Information';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
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
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('worksPage.title')
                    ->title('Title')
                    ->placeholder('Enter the title for the works page'),
                Upload::make('worksPage.images')
                    ->title('Images')
                    ->maxFiles(1)
                    ->help('Upload images for the works page (up to 1 image)'),
            ])
        ];
    }

    function save(Request $request){
        $worksPage = WorksPage::firstOrNew([]);
        $worksPage->fill($request->get('worksPage'));
        $worksPage->save();
        Toast::success('Works page information saved successfully!');
    }
}
