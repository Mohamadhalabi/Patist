<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class Currency
{

    public static $percent = 99;

    public static function getCurrency($type = 'EUR')
    {
        /**
         * * Cache the TCMB currency for 1 day
         */
        if (Cache::has('exchange')) {
            $currency = Cache::get('exchange');
        } else {
            // $exchange = simplexml_load_file('http://www.tcmb.gov.tr/kurlar/today.xml');
            // $currency = [
            //     'USD' => (string)$exchange->Currency[0]->BanknoteBuying,
            //     'EUR' => (string)$exchange->Currency[3]->BanknoteBuying,
            // ];
            $currency = [
                'USD' => 18,
                'EUR' => 19,
            ];
            Cache::put('currency', $currency, now()->addDay());
        }

        $exchangeRate = $currency[$type] ?? 1;
        return $exchangeRate;
    }

    public static function eur2try($amount)
    {
        $eur = self::getCurrency();
        $try = $eur * $amount;
        $try = $try * self::$percent / 100;
        return number_format($try, 2);
    }

    public static function try2eur($amount)
    {
        $eur = self::getCurrency();
        $try = $amount / $eur;
        $eur = $try * self::$percent / 100;
        return number_format($eur, 2);
    }
}
