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
            ['name' => 'Super Admin'],
            ['name' => 'Staff Admin'],
            ['name' => 'Student'],
            ['name' => 'Faculty'],
        ];

        DB::table('user_types')->insert($userTypes);
    }
}
