<?php

namespace App\Console\Commands;

use App\Jobs\SendDailyReports;
use Illuminate\Console\Command;

class SendDailyReportsCommand extends Command
{
    protected $signature = 'appointments:send-daily-reports';
    protected $description = 'Envía el reporte diario de citas al administrador y a cada doctor';

    public function handle(): void
    {
        $this->info('Enviando reportes diarios...');
        SendDailyReports::dispatch();
        $this->info('Reportes diarios encolados correctamente.');
    }
}
