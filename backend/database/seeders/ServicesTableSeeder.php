<?php

namespace Database\Seeders;

use App\Models\ServiceName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultipleRecord = [
            [
                'title' => "European Patent Validation in Türkiye",
                'slug' => "european-patent-validation-in-turkiye",
                'type' => 'Services',
            ],
            [
                'title' => "PCT Entry",
                'slug' => "pct-national-phase-entry-in-turkiye",
                'type' => 'Services',
            ],
            [
                'title' => "Patent Renewal",
                'slug' => "patent-renewals-in-turkiye",
                'type' => 'Services',
            ],
        ];
        ServiceName::insert($createMultipleRecord);

    }
}
