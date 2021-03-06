<?php

namespace App\Console\Commands;

use App\Services\HupunService;
use Illuminate\Console\Command;

class HupunGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Hupun:Get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '向万里牛请求并获取订单原始数据';

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
        (new HupunService())->getHupunTrades();
        return 0;
    }
}
