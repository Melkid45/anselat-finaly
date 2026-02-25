<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Home')
                ->icon('bs.house')
                ->title('Site Pages')
                ->route('platform.home.page'),
            Menu::make('About')
                ->icon('bs.info-circle')
                ->route('platform.about.page'),
            Menu::make('Works')
                ->icon('bs.briefcase')
                ->route('platform.works.page'),
            Menu::make('Materials')
                ->icon('bs.box-seam')
                ->route('platform.material.page'),
            Menu::make('Contacts')
                ->icon('bs.envelope')
                ->route('platform.contact.page'),
            Menu::make('SEO')
                ->icon('bs.search')
                ->route('platform.seo.pages'),
            Menu::make('Works')
                ->icon('bs.briefcase')
                ->title('Others')
                ->route('platform.works.list'),
            Menu::make('Materials')
                ->icon('bs.box-seam')
                ->route('platform.materials.list')
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }

    public function stylesheets(): array
    {
        return [
            asset('css/admin-custom.css'),
        ];
    }
}
