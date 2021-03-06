<?php

namespace App\Console\Commands;

use App\Services\HupunService;
use Illuminate\Console\Command;

class HupunResolve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hupun:Resolve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '解析万里牛原始数据';

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
        (new HupunService())->resolveHupunTradesRaw();
        return 0;
    }
}
