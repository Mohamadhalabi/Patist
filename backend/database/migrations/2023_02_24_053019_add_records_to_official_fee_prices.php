<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Official Fee Prices
//        $official_fee_prices = array(
//            array('id' => '1','official_fee_id' => '1','amount' => '8910.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '2','official_fee_id' => '2','amount' => '5800.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '3','official_fee_id' => '3','amount' => '1030.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '4','official_fee_id' => '4','amount' => '1050.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '5','official_fee_id' => '5','amount' => '1210.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '6','official_fee_id' => '6','amount' => '1710.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '7','official_fee_id' => '7','amount' => '2160.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '8','official_fee_id' => '8','amount' => '2400.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '9','official_fee_id' => '9','amount' => '2680.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '10','official_fee_id' => '10','amount' => '2910.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '11','official_fee_id' => '11','amount' => '3180.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '12','official_fee_id' => '12','amount' => '3600.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '13','official_fee_id' => '13','amount' => '4180.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '14','official_fee_id' => '14','amount' => '4810.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '15','official_fee_id' => '15','amount' => '5440.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '16','official_fee_id' => '16','amount' => '6250.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '17','official_fee_id' => '17','amount' => '6830.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '18','official_fee_id' => '18','amount' => '7580.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '19','official_fee_id' => '19','amount' => '8100.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '20','official_fee_id' => '20','amount' => '8540.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '21','official_fee_id' => '21','amount' => '8900.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 12:39:58','updated_at' => '2022-10-25 12:39:58'),
//            array('id' => '22','official_fee_id' => '23','amount' => '1780.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 20:53:31','updated_at' => '2022-10-25 20:53:31'),
//            array('id' => '23','official_fee_id' => '22','amount' => '290.00','currency' => 'TRY','year' => '2023','created_at' => '2022-10-25 20:53:31','updated_at' => '2022-10-25 20:53:31'),
//            array('id' => '24','official_fee_id' => '24','amount' => '2940.00','currency' => 'TRY','year' => '2023','created_at' => NULL,'updated_at' => NULL),
//            array('id' => '25','official_fee_id' => '25','amount' => '5040.00','currency' => 'TRY','year' => '2023','created_at' => NULL,'updated_at' => NULL),
//            array('id' => '26','official_fee_id' => '26','amount' => '2820.00','currency' => 'TRY','year' => '2023','created_at' => NULL,'updated_at' => NULL)
//          );
//
//        DB::table('official_fee_prices')->insert($official_fee_prices);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('official_fee_prices', function (Blueprint $table) {
            //
        });
    }
};
