<?php

namespace App\Orchid\Screens;

use App\Models\Works;
use App\Orchid\Layouts\WorkListLayout;
use App\Orchid\Layouts\WorksMenu;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class WorkListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'works' => Works::with('workCategory')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Works List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create new')
                ->icon('pencil')
                ->route('platform.works.edit'),
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
            WorksMenu::class,
            WorkListLayout::class,
        ];
    }

    public function toggleHomeVisibility(Request $request)
    {
        $work = Works::query()->findOrFail((int) $request->get('id'));
        $work->update([
            'view_on_home_page' => !$work->view_on_home_page,
        ]);

        Toast::success('Visibility on home page updated.');

        return redirect()->route('platform.works.list');
    }
}
