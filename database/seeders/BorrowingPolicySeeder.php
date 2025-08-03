<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowingPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentType = UserType::where('name', 'student')->firstOrFail();
        $facultyType = UserType::where('name', 'faculty')->firstOrFail();
        $gradSchoolStudentType = UserType::where('name', 'grad-school-student')->firstOrFail();
        $staffAdminType = UserType::where('name', 'staff-admin')->firstOrFail();

        $policies = [
            [
                'name' => 'Student Policy',
                'user_type_id' => $studentType->id,
                'max_items' => 5,
                'loan_period_days' => 14,
                'renewal_limit' => 2,
                'renewal_period_days' => 7,
                'hold_period_days' => 7,
                'overdue_fine_per_day' => 5.00,
                'max_fine_amount' => 500.00,
                'grace_period_days' => 1,
                'can_place_holds' => false, // Disabled since holds are excluded
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Faculty Policy',
                'user_type_id' => $facultyType->id,
                'max_items' => 15,
                'loan_period_days' => 30,
                'renewal_limit' => 5,
                'renewal_period_days' => 14,
                'hold_period_days' => 14,
                'overdue_fine_per_day' => 10.00,
                'max_fine_amount' => 1000.00,
                'grace_period_days' => 3,
                'can_place_holds' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Graduate Student Policy',
                'user_type_id' => $gradSchoolStudentType->id,
                'max_items' => 10,
                'loan_period_days' => 21,
                'renewal_limit' => 3,
                'renewal_period_days' => 10,
                'hold_period_days' => 10,
                'overdue_fine_per_day' => 7.50,
                'max_fine_amount' => 750.00,
                'grace_period_days' => 2,
                'can_place_holds' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Policy',
                'user_type_id' => $staffAdminType->id,
                'max_items' => 10,
                'loan_period_days' => 21,
                'renewal_limit' => 3,
                'renewal_period_days' => 10,
                'hold_period_days' => 10,
                'overdue_fine_per_day' => 7.50,
                'max_fine_amount' => 750.00,
                'grace_period_days' => 2,
                'can_place_holds' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Borrow Inside Policy',
                'user_type_id' => $studentType->id,
                'max_items' => 5,
                'loan_period_days' => 3,
                'renewal_limit' => 3,
                'renewal_period_days' => 10,
                'hold_period_days' => 10,
                'overdue_fine_per_day' => 7.50,
                'max_fine_amount' => 750.00,
                'grace_period_days' => 2,
                'can_place_holds' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        DB::table('borrowing_policies')->insert($policies);
    }
}
