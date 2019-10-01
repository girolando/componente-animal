<?php
namespace Girolando\Componentes\Animal\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoSangue
 * @package Girolando\Componentes\Entities
 */
class TipoSangue extends Model
{
    /**
     * @var string
     */
    protected $table = "dbo.TipoSangue";

    /**
     * @var string
     */
    protected $primaryKey = 'codigoTipoSangue';
    /**
     * @var bool
     */
    public static $snakeAttributes = false;


}
