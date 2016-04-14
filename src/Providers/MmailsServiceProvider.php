<?php

namespace Mixdinternet\Mmails\Providers;

use Illuminate\Support\ServiceProvider;
use Mixdinternet\Mmails\Mmail;
use Menu;

class MmailsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setMenu();

        $this->setRoutes();

        $this->setRouterBind();

        $this->loadViews();

        $this->publish();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mmail', function ($app) {
            return new \Mixdinternet\Mmails\Services\Mmail();
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Mmail', \Mixdinternet\Mmails\Facade\MmailFacade::class);
    }

    protected function setMenu()
    {
        Menu::modify('adminlte-sidebar', function ($menu) {
            $menu->route('admin.mmails.index', 'Envios', [], 210
                , ['icon' => 'fa fa-paper-plane', 'active' => function () {
                    return checkActive(route('admin.mmails.index'));
                }])->hideWhen(function () {
                return checkRule('admin.mmails.index');
            });
        });

        Menu::modify('adminlte-permissions', function ($menu) {
            $menu->url('admin.mmails.index', 'Envios', 210);
        });

    }

    protected function setRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $this->app->router->group(['namespace' => 'Mixdinternet\Mmails\Http\Controllers'],
                function () {
                    require __DIR__ . '/../Http/routes.php';
                });
        }
    }

    protected function setRouterBind()
    {
        $this->app->router->bind('mmails', function ($id) {
            $mmail = Mmail::find($id);
            if (!$mmail) {
                abort(404);
            }

            return $mmail;
        });
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mixdinternet/mmails');
    }

    protected function publish()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/mixdinternet/mmails'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../database/migrations' => base_path('database/migrations'),
        ], 'migrations');
    }
}
