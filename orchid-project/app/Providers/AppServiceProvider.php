<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('contact', Contact::first());
        });
        View::composer('*', function ($view) {
            $locale = app()->getLocale();

            if (!request()->is('works/filter') && !request()->ajax()) {
                $view->with(
                    'footerCategories',
                    Category::query()
                        ->whereHas('translations', fn ($query) => $query
                            ->where('locale', $locale)
                            ->whereNotNull('slug'))
                        ->whereHas('works', fn ($query) => $query
                            ->whereHas('translations', fn ($translationQuery) => $translationQuery
                                ->whereNotNull('slug')))
                        ->get()
                );
            }
        });
    }
}
