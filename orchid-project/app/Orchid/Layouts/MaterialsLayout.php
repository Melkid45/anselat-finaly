<?php

namespace App\Orchid\Layouts;

use App\Models\Materials;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MaterialsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'materials';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('name', 'Name')
                ->render(function (Materials $materials) {
                    return Link::make($materials->name)
                        ->route('platform.materials.edit', $materials);
                }),
        ];
    }
}
