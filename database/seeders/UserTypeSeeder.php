<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            ['name' => 'super-admin'],
            ['name' => 'staff-admin'],
            ['name' => 'student'],
            ['name' => 'faculty'],
            ['name' => 'grad-school-student'],
        ];

        DB::table('user_types')->insert($userTypes);
    }
}
