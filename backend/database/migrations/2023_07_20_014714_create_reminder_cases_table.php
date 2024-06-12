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
        Schema::create('reminder_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_code');
            $table->string('title');
            $table->string('slug');
            $table->string('start_date')->nullable();
            $table->string('deadline')->nullable();
            $table->json('properties')->nullable();
            $table->enum('status', ['active','completed','cancelled'])->default('active');
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
        Schema::dropIfExists('reminder_cases');
    }
};
