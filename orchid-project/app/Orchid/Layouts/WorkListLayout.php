<?php

namespace App\Orchid\Layouts;

use App\Models\Works;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WorkListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'works';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
            ->render(function (Works $works) {
                    $title = (string) (
                        $works->translate('lv')?->name
                        ?? $works->translate('en')?->name
                        ?? $works->name
                        ?? ('#'.$works->id)
                    );

                    return Link::make($title)
                        ->route('platform.works.edit', $works);
                }),
            TD::make('category', 'Category')
                ->render(fn(Works $works) => (string) (
                    $works->workCategory?->translate('lv')?->name
                    ?? $works->workCategory?->translate('en')?->name
                    ?? $works->workCategory?->name
                    ?? '—'
                )),
            TD::make('view_on_home_page', 'View on home page')
                ->alignCenter()
                ->render(function (Works $works) {
                    $enabled = (bool) $works->view_on_home_page;

                    return Button::make($enabled ? 'On' : 'Off')
                        ->icon($enabled ? 'bs.toggle-on' : 'bs.toggle-off')
                        ->method('toggleHomeVisibility', ['id' => $works->id])
                        ->class(
                            $enabled
                                ? 'btn btn-sm btn-success rounded-pill px-3'
                                : 'btn btn-sm btn-outline-secondary rounded-pill px-3'
                        );
                }),
        ];
    }
}
