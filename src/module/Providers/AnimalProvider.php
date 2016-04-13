<?php
namespace Girolando\Componentes\Animal\Providers;

use Girolando\BaseComponent\Providers\BaseComponentProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class AnimalProvider extends BaseComponentProvider{


    public function boot(Router $router)
    {
        Lang::addNamespace('ComponenteAnimal', __DIR__.'/../../resources/lang');
        View::addNamespace('ComponenteAnimal', __DIR__.'/../../resources/views');
        parent::boot($router);
    }


    public function map(Router $router)
    {
        $router->group(['prefix' => 'vendor-girolando', 'namespace' => 'Girolando\Componentes\Animal\Controllers'], function() use($router){
            $router->resource('componentes/animal', 'AnimalServiceController', ['only' => ['index']]);
            $router->resource('server/componentes/animal', 'Server\AnimalServiceController', ['only' => ['index']]);
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