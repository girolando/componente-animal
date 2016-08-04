<?php
/**
 * Created by PhpStorm.
 * User: ansilva
 * Date: 04/08/2016
 * Time: 10:28
 */

namespace Girolando\Componentes\Animal\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpCommand extends Command
{
    protected $signature = 'girolando:componenteanimal:up';
    protected $description = 'Instala a view necessária para o componente funcionar. Nesse caso a view é comp.VAnimal';

    public function handle()
    {
        try {
            $this->info('Rodando migration da view');
            $this->migrate();
            $this->info('Migration Executada!');
        } catch (\Exception $e) {
            $this->error('Houve uma falha: '. $e->getMessage());
        }
    }


    private function migrate()
    {
        \DB::statement("
CREATE VIEW comp.Animal as 
        SELECT
    ani.id,
    ani.idTipoSangue,
    ani.idTipoPelagem,
    ani.idCategoriaTouro,
    ani.idPai,
    ani.idMae,
    ani.idAnimalOriginal,
    ani.nomeAnimal,
    ani.sexoAnimal,
    ani.sisBovAnimal,
    ani.dataNascimentoAnimal,
    ani.pathFotoAnimal,
    ani.nomeOriginalFotoAnimal,
    ani.codigoRegBaseAnimal,
    ani.mascaraCodigoRegBaseAnimal,
    ani.contemporaneaAnimal,
    ani.ptaAnimal,
    ani.preCadastroAnimal,
    ani.statusAnimal,
    ani.sclAnimal,
    ani.bitFotoMiniaturaAnimal,
    ult.id as idUltimaTransferencia,
    pai.nomeAnimal as nomePai,
    mae.nomeAnimal as nomeMae,
    rep.idTipo as TipoRegistroPai,
	idp.sequenciaIdentidade as registroPai,
    rem.idTipo as TipoRegistroMae,
	idm.sequenciaIdentidade as registroMae,
    pai.idTipoSangue as idTipoSanguePai,
    mae.idTipoSangue as idTipoSangueMae,
    
    'in progress...' as nomeProprietario,
    ult.idCriador as idProprietario,
    ult.idFazenda as idFazendaProprietario,
    'in progress...' as nomeFazendaProprietario,
    
    
    'in progress...' as nomeCriador,
    0 as idCriador,
    0 as idFazendaCriador,
    'in progress...' as nomeFazendaCriador,
    
    
    'in progress...' as descTipoPelagem,
    'in progress...' as descTipoSangue,
    'in progress' as numeroParticular,
    'in progress...' as botton,
	rea.idTipo as tipoRegistro,
    ida.sequenciaIdentidade as registro,
    
    ab.idTipoBaixa,
    tb.descTipoBaixa
from
	dbo.Animal ani
	left join dbo.Animal pai on pai.id = ani.idPai
	left join dbo.Animal mae on mae.id = ani.idMae
	left join dbo.Transferencia ult on ult.idAnimal = ani.id and ult.id = (select top 1 id from Transferencia where idAnimal = ani.id order by dataTransferencia desc)
	left join reg.Registro rea on rea.idAnimal = ani.id and rea.id = (select top 1 id from reg.Registro where idAnimal = ani.id order by dataInspecaoRegistro desc)
	left join reg.Identidade ida on ida.idAnimal = ani.id and ida.id = rea.idIdentidade
	left join reg.Registro rep on rep.idAnimal = ani.idPai and rep.id = (select top 1 id from reg.Registro where idAnimal = ani.idPai order by dataInspecaoRegistro desc)
	left join reg.Identidade idp on idp.idAnimal = ani.idPai and idp.id = rep.idIdentidade
	left join reg.Registro rem on rem.idAnimal = ani.idMae and rem.id = (select top 1 id from reg.Registro where idAnimal = ani.idMae order by dataInspecaoRegistro desc)
	left join reg.Identidade idm on idm.idAnimal = ani.idMae and idm.id = rem.idIdentidade
	left join dbo.AnimalBaixa ab on ab.idAnimal = ani.id
	left join params.TipoBaixa tb on tb.id = ab.idTipoBaixa
        ");
    }
}