<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
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
     * @param Car $car
     * @return mixed
     */
    public function update(User $user, Car $car)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param User $user
     * @param Car $car
     * @return mixed
     */
    public function delete(User $user, Car $car)
    {
        return $user->hasRole('admin');
    }


    /**
     * Determine whether the user can permanently delete the vacancy.
     *
     * @param User $user
     * @param Car $car
     * @return mixed
     */
    public function forceDelete(User $user, Car $car)
    {
        return $user->hasRole('admin');
    }
}
