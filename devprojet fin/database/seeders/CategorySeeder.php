<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->truncate();

        DB::table('categories')->insert([
            [
                'name' => 'Serveurs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Machines virtuelles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stockage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Équipements réseau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}