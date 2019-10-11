<?php
namespace Girolando\Componentes\Animal\Providers;

use Girolando\BaseComponent\Providers\BaseComponentProvider;
use Girolando\Componentes\Animal\Commands\DownCommand;
use Girolando\Componentes\Animal\Commands\RefreshCommand;
use Girolando\Componentes\Animal\Commands\UpCommand;
use Girolando\Componentes\Animal\Facades\ComponenteAnimal;
use Girolando\Componentes\Animal\Services\AnimalService;
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
        $router->group(['prefix' => 'vendor-girolando', 'namespace' => 'Girolando\Componentes\Animal\Http\Controllers'], function() use($router){
            $router->resource('componentes/animal', 'AnimalServiceController', ['only' => ['index']]);
            $router->get('componentes/animal/findby', 'AnimalServiceController@findby');
            
            $router->resource('server/componentes/animal', 'Server\AnimalServiceController', ['only' => ['index']]);
            $router->resource('server/componentes/animal/tiposangue', 'Server\TipoSangueController', ['only' => ['index']]);
            $router->get('server/componentes/animal/findby', 'Server\AnimalServiceController@findby');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Girolando.Componente.Animal', AnimalService::class);
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('ComponenteAnimal', ComponenteAnimal::class);
        $this->commands([UpCommand::class, DownCommand::class]);

    }
}