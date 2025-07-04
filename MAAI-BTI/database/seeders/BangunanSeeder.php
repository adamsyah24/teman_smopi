<?php

namespace Database\Seeders;

use App\Models\Bangunan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BangunanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Bangunan 1', 'x' => 100, 'y' => 100],
            ['name' => 'Bangunan 2', 'x' => 300, 'y' => 200],
            ['name' => 'Bangunan 3', 'x' => 500, 'y' => 300],
        ];

        foreach ($data as $b) {
            Bangunan::create($b);
        }
    }
}
