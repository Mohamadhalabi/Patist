<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Official Fee Items
//        $official_fee_items = array(
//            array('id' => '1', 'code' => '1.0.1.45', 'item' => 'Avrupa Patenti Fasikül Yayım Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '2', 'code' => '1.0.1.41', 'item' => 'Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Ücreti', 'item_en' => 'PCT EntryFee', 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '3', 'code' => '1.0.1.22', 'item' => '2.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '4', 'code' => '1.0.1.23', 'item' => '3.Yıl Sicil Kayıt Ücreti', 'item_en' => 'Renewal Fee', 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '5', 'code' => '1.0.1.24', 'item' => '4.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '6', 'code' => '1.0.1.25', 'item' => '5.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '7', 'code' => '1.0.1.26', 'item' => '6.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '8', 'code' => '1.0.1.27', 'item' => '7.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '9', 'code' => '1.0.1.28', 'item' => '8.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '10', 'code' => '1.0.1.29', 'item' => '9.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '11', 'code' => '1.0.1.30', 'item' => '10.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '12', 'code' => '1.0.1.31', 'item' => '11.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '13', 'code' => '1.0.1.32', 'item' => '12.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '14', 'code' => '1.0.1.33', 'item' => '13.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '15', 'code' => '1.0.1.34', 'item' => '14.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '16', 'code' => '1.0.1.35', 'item' => '15.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '17', 'code' => '1.0.1.36', 'item' => '16.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '18', 'code' => '1.0.1.37', 'item' => '17.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '19', 'code' => '1.0.1.38', 'item' => '18.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '20', 'code' => '1.0.1.39', 'item' => '19.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '21', 'code' => '1.0.1.40', 'item' => '20.Yıl Sicil Kayıt Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 12:38:31', 'updated_at' => '2022-10-25 12:38:50'),
//            array('id' => '22', 'code' => '1.0.1.02', 'item' => 'Rüçhan Hakkı Talebi Ücreti (Her Bir Rüçhan İçin)', 'item_en' => 'Priority Fee', 'created_at' => '2022-10-25 20:52:31', 'updated_at' => '2022-10-25 20:52:31'),
//            array('id' => '23', 'code' => '1.0.1.51', 'item' => 'İnceleme Raporu Düzenlenmesi Ücreti (1.01.50 Sayılı Satır Haricindeki Başvurular İçin)', 'item_en' => 'Examination Fee', 'created_at' => '2022-10-25 20:53:11', 'updated_at' => '2022-10-25 20:53:11'),
//            array('id' => '24', 'code' => '1.0.1.42', 'item' => 'Patent İşbirliği Antlaşması Kapsamında Ulusal Aşamaya Giren Başvurunun Yapılması İçin Ek Süre Ücreti', 'item_en' => 'Additional Time Fee', 'created_at' => '2022-10-25 20:53:11', 'updated_at' => '2022-10-25 20:53:11'),
//            array('id' => '25', 'code' => '1.0.1.46', 'item' => 'Avrupa Patenti Fasikülü Türkçe Çevirisi İçin Ek Süre Ücreti', 'item_en' => NULL, 'created_at' => '2022-10-25 20:53:11', 'updated_at' => '2022-10-25 20:53:11'),
//            array('id' => '26', 'code' => '1.0.1.04', 'item' => 'Avrupa Patent Başvurusunun veya Avrupa Patentinin Düzeltilmiş Türkçe Çevirisinin Yayım Ücreti', 'item_en' => NULL, 'created_at' => NULL, 'updated_at' => NULL)
//        );
//
//        DB::table('official_fee_items')->insert($official_fee_items);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('official_fee_items', function (Blueprint $table) {
            //
        });
    }
};
