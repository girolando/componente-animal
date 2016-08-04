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

class DownCommand extends Command
{
    protected $signature = 'girolando:componenteanimal:down';
    protected $description = 'Remove a view comp.VAnimal';

    public function handle()
    {
        try {
            $this->info('Dropando view');
            $this->migrate();
            $this->info('Migration Executada!');
        } catch (\Exception $e) {
            $this->error('Houve uma falha: '. $e->getMessage());
        }
    }


    private function migrate()
    {
        \DB::statement("DROP VIEW comp.Animal;");
    }
}