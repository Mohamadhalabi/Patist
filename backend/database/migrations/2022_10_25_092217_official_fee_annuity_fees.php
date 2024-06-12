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
        Schema::create('official_fee_annuity_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('official_fee_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->enum('currency', ['EUR', 'USD', 'TRY'])->nullable()->default('USD');
            $table->integer('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_fee_annuity_fees');
    }
};
