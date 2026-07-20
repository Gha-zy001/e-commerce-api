<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register model bindings if needed
    }

    public function boot(): void
    {
        // Model event listeners can be registered here
        // Example: Product::observe(ProductObserver::class);
    }
}
