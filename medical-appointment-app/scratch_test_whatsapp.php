<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

$whatsapp = app(WhatsAppService::class);
$testPhone = '5219991234567'; // Cambiar por uno real para probar
$message = "Test desde Healthify - Evolution API";

echo "Intentando enviar mensaje a $testPhone...\n";
$result = $whatsapp->sendMessage($testPhone, $message);

if ($result) {
    echo "¡Éxito! Revisa los logs de Evolution API.\n";
} else {
    echo "Error. Revisa storage/logs/laravel.log\n";
}
