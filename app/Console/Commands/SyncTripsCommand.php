<?php

namespace App\Console\Commands;

use App\Services\GenerateTripsService;
use Illuminate\Console\Command;

class SyncTripsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync trips table with puv details table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        (new GenerateTripsService())->execute();
    }
}
