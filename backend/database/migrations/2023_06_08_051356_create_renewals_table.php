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
        Schema::create('renewal_renewals', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('cc');
            $table->text('reference_no');
            $table->integer('annuity_status');
            $table->string('region');
            $table->text('filing_date');
            $table->text('renewal_date');
            $table->double('official_fee_eur');
            $table->double('official_fee_original');
            $table->text('official_fee_currency');
            $table->double('agent_fee_eur');
            $table->double('agent_fee_original');
            $table->text('agent_fee_currency');
            $table->double('service_fee_eur');
            $table->double('service_fee_exchange_rate');
            $table->double('agent_fee_exchange_rate');
            $table->double('total_amount_eur');
            $table->text('status');
            $table->boolean('is_instruction')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_payment')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->integer('workflow_id')->nullable();
            $table->text('workflow_status')->nullable();
            $table->integer('reminder_id')->nullable();
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
        Schema::dropIfExists('renewals');
    }
};
