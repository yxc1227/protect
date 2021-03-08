<?php

namespace App\Console\Commands;

use App\Services\HupunService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::channel('hupun')->info("start resolving raw_info", ['command' => "Hupun:Resolve",]);
        (new HupunService())->resolveHupunTradesRaw();
        Log::channel('hupun')->info("finish resolving raw_info", ['command' => "Hupun:Resolve",]);
        return 0;
    }
}
