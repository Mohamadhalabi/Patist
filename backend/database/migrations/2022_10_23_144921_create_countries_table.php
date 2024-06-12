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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('iso3');
            $table->string('numeric_code');
            $table->string('iso2');
            $table->text('phonecode');
            $table->text('capital');
            $table->text('currency');
            $table->text('currency_name');
            $table->text('currency_symbol');
            $table->text('tld');
            $table->text('navite');
            $table->text('region');
            $table->text('subregion');
            $table->string('timezones');
            $table->string('translations');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('emoji');
            $table->string('emojiU');
            $table->string('flag');
            $table->string('wikiDataId');
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
        Schema::dropIfExists('countries');
    }
};
