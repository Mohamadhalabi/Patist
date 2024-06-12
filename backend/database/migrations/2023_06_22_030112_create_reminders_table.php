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
        Schema::create('renewal_reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('renewal_id');
            $table->integer('user_id');
            $table->integer('workflow_id');
            $table->string('code');
            $table->boolean('send_email')->default(true);
            $table->text('status')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->text('workflow_status')->nullable();
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
        Schema::dropIfExists('renewal_reminders');
    }
};
