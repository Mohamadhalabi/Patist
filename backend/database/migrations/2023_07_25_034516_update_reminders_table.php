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
            // add type column
            $table->string('ref_code')->after('workflow_id')->nullable();
            $table->string('short_name')->after('workflow_id')->nullable();
            $table->string('type')->after('workflow_id')->nullable();
            $table->json('emails')->after('workflow_id')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('deadline')->nullable();
            $table->string('frequency')->nullable();
            $table->string('repetition')->nullable();

            //update workflow_id to be nullable
            $table->integer('renewal')->nullable()->change();
            $table->integer('workflow_id')->nullable()->change();
            $table->string('code')->nullable()->change();
            $table->string('end')->nullable()->change();
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
            $table->dropColumn('ref_code');
            $table->dropColumn('short_name');
            $table->dropColumn('type');
            $table->dropColumn('emails');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('deadline');
            $table->dropColumn('frequency');
            $table->dropColumn('repetition');
        });
    }
};
