<?php

namespace Database\Seeders;

use App\Models\Petak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petak::insert([
            ['name' => 'Petak A', 'x' => 120, 'y' => 250, 'width' => 100, 'height' => 60, 'golongan' => 1],
            ['name' => 'Petak B', 'x' => 400, 'y' => 320, 'width' => 100, 'height' => 60, 'golongan' => 2],
        ]);
    }
}
