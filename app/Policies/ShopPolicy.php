<?php

namespace App\Policies;

use App\Models\User;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

public function checkRole(User $user, $model = null)
{
    return $user->is_admin || $user->is_agentia;
}



}
