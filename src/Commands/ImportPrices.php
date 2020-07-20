<?php

namespace DFM\Shipping\Console\Commands;

use DFM\Shipping\Imports\CoupePricesImport;
use DFM\Shipping\Imports\LeleuPricesImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dfm-shipping:import {carrier} {path?}';

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
        $this->info('Importing...');

        if (! ($carrier = $this->argument('carrier'))) {
            $this->error("Carrier {$carrier} doesn't match!");
            return ;
        }

        switch ($carrier) {
            case 'coupe':
                $filePath = public_path($this->argument('path') ?? '/imports/coupe.xlsx');

                if (! File::exists($filePath)) {
                    $this->error("Path {$filePath} doesn't exists!");
                    return ;
                }

                Excel::import(new CoupePricesImport(), $filePath);
                break;
            case 'leleu':
                $filePath = public_path($this->argument('path') ?? '/imports/leleu.xls');

                if (! File::exists($filePath)) {
                    $this->error("Path {$filePath} doesn't exists!");
                    return ;
                }

                Excel::import(new LeleuPricesImport(), $filePath);
                break;
        }

        $this->info('Done.');
    }
}
