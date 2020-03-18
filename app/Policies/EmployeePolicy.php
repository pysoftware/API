<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    /**
     * Determine whether the user can create vacancies.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return ($user->id === $employee->id && $user->hasRole('employee')) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->hasRole('admin');
    }


    /**
     * Determine whether the user can permanently delete the vacancy.
     *
     * @param User $user
     * @param Customer $customer
     * @return mixed
     */
    public function forceDelete(User $user, Employee $employee)
    {
        return $user->hasRole('admin');
    }
}
