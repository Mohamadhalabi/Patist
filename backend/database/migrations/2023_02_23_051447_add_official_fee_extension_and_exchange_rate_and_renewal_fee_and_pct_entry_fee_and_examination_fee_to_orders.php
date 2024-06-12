<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('orders', function (Blueprint $table) {
//            $table->string('official_fee_extension')->nullable();
//            $table->string('exchange_rate')->nullable();
//            $table->string('renewal_fee')->nullable();
//            $table->string('pct_entry_fee')->nullable();
//            $table->string('examination_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('official_fee_extension');
            $table->dropColumn('exchange_rate');
            $table->dropColumn('renewal_fee');
            $table->dropColumn('pct_entry_fee');
            $table->dropColumn('examination_fee');

        });
    }
};
