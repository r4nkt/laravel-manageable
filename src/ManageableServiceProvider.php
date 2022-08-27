<?php

namespace R4nkt\Manageable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class ManageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('manageable', function ($bigIntegers = true, $foreignTable = 'users', $foreignKey = 'id') {
            $bigIntegers
                ? $this->unsignedBigInteger('created_by')->nullable()->index()
                : $this->unsignedInteger('created_by')->nullable()->index();
            $bigIntegers
                ? $this->unsignedBigInteger('updated_by')->nullable()->index()
                : $this->unsignedInteger('updated_by')->nullable()->index();

            $this->foreign('created_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('set null');

            $this->foreign('updated_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('set null');
        });

        Blueprint::macro('unmanageable', function ($bigIntegers = true, $foreignTable = 'users', $foreignKey = 'id') {
            $this->dropForeign(['created_by']);
            $this->dropForeign(['updated_by']);
            $this->dropColumn(['created_by', 'updated_by']);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
