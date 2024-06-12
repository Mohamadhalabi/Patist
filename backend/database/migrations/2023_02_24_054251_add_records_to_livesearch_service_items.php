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
//        $livesearch_service_items = array(
//            array('id' => '1', 'title' => 'Ep validation', 'slug' => 'european-patent-validation-in-turkey', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services'),
//            array('id' => '2', 'title' => 'Pct entry', 'slug' => 'pct-national-phase-entry-in-turkey', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services'),
//            array('id' => '3', 'title' => 'Patent anuuity', 'slug' => 'patent-renewals-in-turkey', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services'),
//            array('id' => '4', 'title' => 'European Patent Validation in Türkiye', 'slug' => 'european-patent-validation-in-turkiye', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services'),
//            array('id' => '5', 'title' => 'PCT Entry', 'slug' => 'pct-national-phase-entry-in-turkiye', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services'),
//            array('id' => '6', 'title' => 'Patent Renewal', 'slug' => 'patent-renewals-in-turkiye', 'created_at' => NULL, 'updated_at' => NULL, 'type' => 'Services')
//        );
//
//        DB::table('livesearch_service_items')->insert($livesearch_service_items);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('livesearch_service_items', function (Blueprint $table) {
            //
        });
    }
};
