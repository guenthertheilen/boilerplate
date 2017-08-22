<?php

namespace App\Console;

use App\Console\Commands\Scaffold;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Scaffold::class,
    ];

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
