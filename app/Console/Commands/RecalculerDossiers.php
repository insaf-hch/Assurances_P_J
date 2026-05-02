<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:recalculer-dossiers')]
#[Description('Command description')]
class RecalculerDossiers extends Command
{
    /**
     * Execute the console command.
     */public function handle()
{
    $service = new \App\Services\CalculService();
    $dossiers = \App\Models\Dossier::whereNotNull('type_cas')->get();
    foreach ($dossiers as $d) {
        $service->createOrUpdateCalcul($d);
        $this->info('Dossier #' . $d->id . ' recalculé');
    }
    $this->info('Terminé !');
}
}
