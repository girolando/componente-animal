<?php
namespace Girolando\Componentes\Animal\Http\Controllers\Server;

use Girolando\Componentes\Animal\Services\Server\ComponenteAnimalService;
use Girolando\Componentes\Animal\Entities\TipoSangue;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TipoSangueController extends Controller
{
    private $animalService;

    /**
     * AnimalServiceController constructor.
     * @param $animalService
     */
    public function __construct(ComponenteAnimalService $animalService)
    {
        $this->animalService = $animalService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TipoSangue::all();
    }

}
