<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InvitationProvider extends ServiceProvider
{
    /**
     * 延迟加载
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/config/config.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('invitation.php')
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('invitation');
        }
        $this->mergeConfigFrom($source, 'invitation');
    }


    protected function setupMigrations()
    {
        $source = realpath(__DIR__.'/database/migrations/');

        $this->publishes([$source => database_path('migrations')], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(['WeiPei\\Invitation' => 'invitation'], function($app){
            return new Invitation($app->make(\App\Models\WeipeiActivities\InvitationLetter::class));
        });
    }


    /**
     * 提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return ['invitation', 'WeiPei\\Invitation'];
    }
}
