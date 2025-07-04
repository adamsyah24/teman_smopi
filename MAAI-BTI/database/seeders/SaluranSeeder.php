<?php

namespace Database\Seeders;

use App\Models\Saluran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaluranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Saluran::insert([
            ['name' => 'Saluran 1', 'x1' => 100, 'y1' => 100, 'x2' => 300, 'y2' => 200],
            ['name' => 'Saluran 2', 'x1' => 300, 'y1' => 200, 'x2' => 500, 'y2' => 300],
        ]);
    }
}
