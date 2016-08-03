<?php
namespace Girolando\Componentes\Animal\Entities\Views;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AnimalConsulta
 * @package Girolando\Componentes\Entities\Views
 */
class VAnimal extends Model
{
    /**
     * @var string
     */
    protected $table = "VAnimal";


    protected $appends = ['idadeAnimal', 'idadeAnimalAbreviada'];

    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var bool
     */
    public static $snakeAttributes = false;




    public function getIdadeAnimalAttribute()
    {
        if($this->dataNascimentoAnimal){
            $nasc = new \DateTime($this->dataNascimentoAnimal);
            $agora = new \DateTime(date("Y-m-d"));
            $diasAnimal = $agora->diff($nasc)->format("%a");

            $numeros = explode(',', $agora->diff($nasc)->format("%y,%m,%d"));
            if($diasAnimal > 365){
                return trans('Entities/Views/AnimalConsulta.idadeAnimalAnos', ['anos' => $numeros[0], 'meses' => $numeros[1], 'dias' => $numeros[2]]);
            }
            return trans('Entities/Views/AnimalConsulta.idadeAnimalMeses', ['meses' => $numeros[1], 'dias' => $numeros[2]]);
        }
        return 'N/D ';
    }



    public function getIdadeAnimalAbreviadaAttribute()
    {
        if($this->dataNascimentoAnimal){
            $nasc = new \DateTime($this->dataNascimentoAnimal);
            $agora = new \DateTime(date("Y-m-d"));
            $diasAnimal = $agora->diff($nasc)->format("%a");

            $numeros = explode(',', $agora->diff($nasc)->format("%y,%m,%d"));
            return trans('Entities/Views/AnimalConsulta.idadeAnimalAbreviada', ['anos' => $numeros[0], 'meses' => $numeros[1], 'dias' => $numeros[2]]);

        }
        return 'N/D ';
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Mae()
    {
        return $this->belongsTo(VAnimal::class, 'idMae', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Pai()
    {
        return $this->belongsTo(VAnimal::class, 'idPai', 'id');
    }



    protected $fillable = [
        'id',
        'idTipoSangue',
        'idTipoPelagem',
        'idCategoriaTouro',
        'idPai',
        'idMae',
        'idAnimalOriginal',
        'nomeAnimal',
        'sexoAnimal',
        'sisBovAnimal',
        'dataNascimentoAnimal',
        'pathFotoAnimal',
        'nomeOriginalFotoAnimal',
        'codigoRegBaseAnimal',
        'mascaraCodigoRegBaseAnimal',
        'contemporaneaAnimal',
        'ptaAnimal',
        'preCadastroAnimal',
        'statusAnimal',
        'sclAnimal',
        'bitFotoMiniaturaAnimal',
        'idUltimaTransferencia',
        'nomePai',
        'nomeMae',
        'TipoRegistroPai',
        'registroPai',
        'TipoRegistroMae',
        'registroMae',
        'idTipoSanguePai',
        'idTipoSangueMae',
        'nomeProprietario',
        'idProprietario',
        'idFazendaProprietario',
        'nomeFazendaProprietario',
        'nomeCriador',
        'idCriador',
        'idFazendaCriador',
        'nomeFazendaCriador',
        'descTipoPelagem',
        'descTipoSangue',
        'numeroParticular',
        'botton',
        'tipoRegistro',
        'registro',
    ];
}
