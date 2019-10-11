<?php
namespace Girolando\Componentes\Animal\Http\Controllers\Server;

use Girolando\Componentes\Animal\Services\Server\ComponenteAnimalService;
use Girolando\Componentes\Animal\Entities\Views\VAnimal;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AnimalServiceController extends Controller
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
        return $this->animalService->getAnimalDatatableJson('_dataTableQuery'.$request->get('name'));
    }

    public function findby(Request $request)
    {
        
        $fillable = (new VAnimal())->getFillable();
        $requestFields = $request->all();

        return $this->animalService->findBy(
            collect($requestFields)
            ->only($fillable)
            ->toArray()
        )
        ->limit(10)
        ->get();
    }

}
