<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    /**
     * Determine whether the user can update the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function update(User $user, Customer $customer)
    {
        return ($user->id === $customer->id && $user->hasRole('customer'))|| $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function delete(User $user, Customer $customer)
    {
        return ($user->id === $customer->id && $user->hasRole('customer'))|| $user->hasRole('admin');
    }


    /**
     * Determine whether the user can permanently delete the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function forceDelete(User $user, Customer $customer)
    {
        return $user->hasRole('admin');
    }
}
