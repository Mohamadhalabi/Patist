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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('application_number');
            $table->string('applicant')->nullable();
            $table->string('publication_date')->nullable();
            $table->string('invention_title')->nullable();
            $table->string('inventor')->nullable();
            $table->string('reference')->nullable();
            $table->string('service')->nullable();
            $table->string('service_fee')->nullable();
            $table->string('late_service_fee')->nullable();
            $table->string('official_fee')->nullable();
            $table->string('official_fee_total')->nullable();
            $table->string('late_official_fee')->nullable();
            $table->string('priority_fee')->nullable();
            $table->string('priority_length')->nullable();
            $table->string('translation_fee');
            $table->string('translation_quantity');
            $table->string('relationship')->nullable();
            $table->string('chapter')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('user_email');
            $table->string('total');
            $table->string('link');
            $table->string('order_status')->default("Pending payment");
            $table->string('token')->nullable();
            $table->string('accept_quote')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
