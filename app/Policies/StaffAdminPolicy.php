<?php

namespace App\Policies;

use App\Models\User;

class StaffAdminPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user)
    {
        // Only allow viewing if user is admin OR owns the book
        return $user->user_type->key === 'super_admin';
    }
}
