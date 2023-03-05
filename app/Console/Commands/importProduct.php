<?php

namespace App\Console\Commands;

use App\Imports\ProductImports;
use Illuminate\Console\Command;

class importProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import product to product table via excel';

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
     * @return int
     */
    public function handle()
    {
        $this->output->title('Starting import');
        (new ProductImports)->withOutput($this->output)->import('excel/products.csv');
        $this->output->success('Import successful');
    }
}
