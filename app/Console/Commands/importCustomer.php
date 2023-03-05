<?php

namespace App\Console\Commands;

use App\Imports\CustomerImports;
use Illuminate\Console\Command;

class importCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customer to customers table via excel';

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
        (new CustomerImports)->withOutput($this->output)->import('excel/customers.csv');
        $this->output->success('Import successful');
    }
}
