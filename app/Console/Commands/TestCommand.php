<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test:simple';
    protected $description = 'A simple test command';

    public function handle()
    {
        $this->info('Test command is working!');
        return 0;
    }
}