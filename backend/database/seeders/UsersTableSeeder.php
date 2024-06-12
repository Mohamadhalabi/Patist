<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@patent.istanbul',
            'password' => '$2y$10$ljaLxgzO6qjeyPN6htQS5uXlHTQb11jYwobOivsiCy5Tg8ijoFPXq', //SrPiUR7hhEXXl7B
            'remember_token' => null,
        ]);
    }
}
