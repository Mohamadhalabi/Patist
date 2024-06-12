<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class getCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It receives the exchange rates from the TCMB data on a daily basis and stores it in the cache.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $exchange = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');
        $currency = (string)$exchange->Currency[3]->BanknoteBuying;
        Cache::forget('currency');
        Cache::put('currency', $currency, now()->addDay());
    }
}
