<?php

namespace Girolando\Componentes\Animal\Controllers;

use Andersonef\ApiClientLayer\Services\ApiConnector;
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
    public function index(Request $request)
    {
        if($request->has('_DataTableQuery')){
            $response = $this->apiConnector->get('/vendor-girolando/server/componentes/animal', $request->all());
            if($response->status == 'success'){
                return new JsonResponse($response->data, 200);
            }
            dd($response);
        }
        return view('Services.Componentes.Animal.AnimalServiceController.index', $request->all());
    }
}
