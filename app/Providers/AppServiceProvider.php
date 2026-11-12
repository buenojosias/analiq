<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use TallStackUi\Facades\TallStackUi;

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
        TallStackUi::customize()
            ->button()
            ->block('wrapper.sizes.md', 'text-sm px-3 py-1.5');
        TallStackUi::customize()
            ->tab()
            ->block('base.content', 'p-4');
        TallStackUi::customize()
            ->alert()
            ->block('wrapper', 'mb-4 rounded-lg p-4');
    }
}
