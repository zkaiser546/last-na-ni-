<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coverTypes = [
            ['key' => 'purchase', 'name' => 'Purchase'],
            ['key' => 'donation', 'name' => 'Donation'],
        ];

        foreach ($coverTypes as $type) {
            Source::create($type);
        }
    }
}
