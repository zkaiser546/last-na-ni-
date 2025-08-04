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
            ['key' => 'super_admin', 'name' => 'Super Admin'],
            ['key' => 'staff_admin', 'name' => 'Staff Admin'],
            ['key' => 'student', 'name' => 'Student'],
            ['key' => 'faculty', 'name' => 'Faculty'],
        ];
        DB::table('user_types')->insert($userTypes);
    }
}
