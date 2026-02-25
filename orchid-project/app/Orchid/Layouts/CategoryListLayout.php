<?php

namespace App\Orchid\Layouts;

use App\Models\Category;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'category';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Name')
                ->render(function (Category $category) {
                    $title = (string) (
                        $category->translate('lv')?->name
                        ?? $category->translate('en')?->name
                        ?? $category->name
                        ?? ('#'.$category->id)
                    );

                    return Link::make($title)
                        ->route('platform.category.edit', $category);
                }),
        ];
    }
}
