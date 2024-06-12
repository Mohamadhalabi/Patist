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
        Schema::create('custom_reminders', function (Blueprint $table) {
            $table->id();
            $table->text('ref_code');
            $table->text('short_name');
            $table->text('start_date');
            $table->text('deadline');
            $table->integer('repetition');
            $table->json('emails');
            $table->text('status');
            $table->text('type');
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
        Schema::dropIfExists('custom_reminders');
    }
};
