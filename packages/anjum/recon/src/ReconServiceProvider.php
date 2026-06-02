<?php

declare(strict_types=1);

namespace Anjum\Recon;

use Anjum\Recon\Services\RequestLogger;
use Illuminate\Support\ServiceProvider;

class ReconServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/recon.php', 'recon');

        $this->app->singleton(RequestLogger::class, static fn (): RequestLogger => new RequestLogger());
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/recon.php' => config_path('recon.php'),
        ], 'recon-config');
    }
}
