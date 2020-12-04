
<?php

namespace Rollswan\CentralizedAttachment;

use Illuminate\Support\ServiceProvider;

class CentralizedAttachmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'./database/migrations');
    }
}
