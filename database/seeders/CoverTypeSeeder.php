<?php

namespace Database\Seeders;

use App\Models\CoverType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverTypes = [
            ['key' => 'hardcover', 'name' => 'Hardcover'],
            ['key' => 'paperback', 'name' => 'Paperback'],
            ['key' => 'dust_jacket', 'name' => 'Dust Jacket'],
            ['key' => 'spiral_bound', 'name' => 'Spiral Bound'],
        ];

        foreach ($coverTypes as $type) {
            CoverType::create($type);
        }
    }
}
