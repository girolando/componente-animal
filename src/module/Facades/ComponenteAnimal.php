<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 14/07/2016
 * Time: 15:26
 */

namespace Girolando\Componentes\Animal\Facades;

use Illuminate\Support\Facades\Facade;

class ComponenteAnimal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Girolando.Componente.Animal';
    }

}