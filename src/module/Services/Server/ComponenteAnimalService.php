<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 30/03/2016
 * Time: 16:36
 */
namespace Girolando\Componentes\Animal\Services\Server;


use Girolando\BaseComponent\Engines\DatasetEngine;
use Girolando\Componentes\Animal\Entities\Views\VAnimal;
use Girolando\Componentes\Animal\Extensions\DataTableQuery;
use Girolando\Componentes\Animal\Repositories\Views\AnimalConsultaRepository;
use Andersonef\Repositories\Abstracts\ServiceAbstract;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;

class ComponenteAnimalService extends ServiceAbstract
{
    protected $datasetEngine;
    /**
     * This constructor will receive by dependency injection a instance of AnimalConsultaRepository and DatabaseManager.
     *
     * @param AnimalConsultaRepository $repository
     * @param DatabaseManager $db
     */
    public function __construct(AnimalConsultaRepository $repository, DatabaseManager $db)
    {
        parent::__construct($repository, $db);
        $this->datasetEngine = new DatasetEngine($this);

    }

    public function getAnimalDataset($dataTableQueryName = 'animalConsulta')
    {
        $dataset = $this
            ->datasetEngine
            ->usingDataTableQuery($dataTableQueryName)
            ->createDataset((new VAnimal())->getFillable());
        //como não tem nenhuma validação, nenhum tipo de filtro especial pra fazer nesse querybuilder... então já retorno o dataset.(que é um querybuilder... só add nele outros wheres q eu possa precisar)

        return $dataset;
    }

    public function getAnimalDatatableJson($datasetName = 'animalConsulta')
    {
        $dataset = $this->getAnimalDataset($datasetName);
        //esse componente tem 2 colunas diferenciadas... crio elas aqui.

        return Datatables::of($dataset)
            ->addColumn('idadeAnimal', function($row){
                $row = (new VAnimal())->fill(['dataNascimentoAnimal' => $row->dataNascimentoAnimal]);
                return $row->idadeAnimal;
            })
            ->addColumn('idadeAnimalAbreviada', function($row){
                $row = (new VAnimal())->fill(['dataNascimentoAnimal' => $row->dataNascimentoAnimal]);
                return $row->idadeAnimalAbreviada;
            })
            ->make(true);
    }


}