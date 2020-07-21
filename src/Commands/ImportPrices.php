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
        if (! in_array($carrier = $this->argument('carrier'), $carriers = ['coupe', 'leleu'])) {
            $this->error('The carrier must be a ' . implode(' or ', $carriers) . '.');
            return ;
        }

        switch ($carrier) {
            case 'coupe':
                $defaultPath = '/imports/coupe.xlsx';
                $importClass = new CoupePricesImport();
                break;
            case 'leleu':
                $defaultPath = '/imports/leleu.xls';
                $importClass = new LeleuPricesImport();
                break;
            default:
                $defaultPath = '';
                $importClass = null;
        }

        if (! ($filePath = $this->argument('path'))) {
            $filePath = $this->ask('Path', $defaultPath);
        }

        $filePath = public_path($filePath);
        if (! File::exists($filePath)) {
            $this->error("Path '{$filePath}' doesn't exists!");
            return ;
        }

        $this->info("\n Importing...");

        Excel::import($importClass, $filePath);

        $this->info(" Done.\n");
    }

    public function error($string, $verbosity = null)
    {
        echo parent::error("\n\n  {$string}\n", $verbosity);
        echo PHP_EOL;
    }
}
