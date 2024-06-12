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
        Schema::table('renewal_reminders', function (Blueprint $table) {
            $table->boolean('send_reminder_email')->default(true);
            $table->boolean('use_reminder_alarm')->default(true);
            $table->boolean('send_notification_on_holiday')->default(true);
            $table->string('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renewal_reminders', function (Blueprint $table) {
            $table->dropColumn('send_reminder_email');
            $table->dropColumn('use_reminder_alarm');
            $table->dropColumn('send_notification_on_holiday');
            $table->dropColumn('note');
        });
    }
};
