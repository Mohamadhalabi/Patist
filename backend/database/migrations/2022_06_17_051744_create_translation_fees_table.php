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
        Schema::create('translation_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id');
            $table->integer('total_word_count');
            $table->double('fee');
            $table->double('amount');
            $table->double('tax')->default(0);
            $table->enum('currency', ['EUR', 'USD', 'TRY'])->default('EUR');
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
        Schema::dropIfExists('translation_fees');
    }
};
