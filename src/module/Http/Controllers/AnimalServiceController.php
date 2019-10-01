<?php

namespace Girolando\Componentes\Animal\Http\Controllers;

use Andersonef\ApiClientLayer\Services\ApiConnector;
use App\Entities\UsuarioLogado;
use Girolando\Componentes\Animal\Entities\Views\VAnimal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class AnimalServiceController extends Controller
{
    protected $apiConnector;

    /**
     * AnimalServiceController constructor.
     * @param $apiConnector
     */
    public function __construct(ApiConnector $apiConnector)
    {
        $this->apiConnector = $apiConnector;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UsuarioLogado $usuarioLogado)
    {
        
        if($request->has('_DataTableQuery')){
            $response = $this->apiConnector->get('/vendor-girolando/server/componentes/animal', $request->all());

            if($response->status == 'success'){
                return new JsonResponse($response->data, 200);
            }
            dd($response);
        }
        $all = $request->all();
        $filters = [];
        foreach($all as $attr => $val){
            if(substr($attr, 0, 7) != 'filter-') continue;
            $filters[substr($attr, 7)] = $val;
        }

        $request->merge(['_attrFilters' => $filters]);
        $request->merge(['tableName' => (new VAnimal())->getTable()]);
        $request->merge(['usuario' => $usuarioLogado]);

        
        $sangues = $this->apiConnector->get('/vendor-girolando/server/componentes/animal/tiposangue');
        $sangues = collect($sangues->data)
            ->map(function($sangue) {
                if (is_null($sangue->ordemMapaTipoSangue)) {
                    $sangue->ordemMapaTipoSangue = 999;
                }
                return $sangue;
            })
            ->filter(function($element) use ($filters) {
                $sangues = null;
                if (empty($filters['codigotiposangue'])) {
                    return true;
                }
                $sangues = $filters['codigotiposangue'];
                if (strpos($sangues, '|') !== false) {
                    $sangues = explode('|', $sangues);
                }
                if (!is_array($sangues)) {
                    $sangues = [$sangues];
                }
                if (in_array($element->codigoTipoSangue, $sangues)) {
                    return true;
                }
                return false;
            })
            ->sortBy('ordemMapaTipoSangue');
            
        $request->merge(['sangues' => $sangues]);

        return view('ComponenteAnimal::AnimalServiceController.index', $request->all());
    }
}
