<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Event::all() as $e) {
    echo $e->id . ':' . $e->title . ':' . $e->poster_path . "\n";
}
