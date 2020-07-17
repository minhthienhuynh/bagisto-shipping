<?php

namespace DFM\Shipping\Console\Commands;

use DFM\Shipping\Imports\CoupePricesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportCoupePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dfm-shipping:import {path?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import coupe prices via a excel file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = public_path($this->argument('path') ?? '/imports/coupe.xlsx');

        if (File::exists($filePath)) {
            $this->info('Importing...');
            Excel::import(new CoupePricesImport(), $filePath);
            $this->info('Done.');

            return ;
        }

        $this->error("Path {$filePath} doesn't exists!");
    }
}
