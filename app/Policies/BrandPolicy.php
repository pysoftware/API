<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
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
     * @param Brand $brand
     * @return mixed
     */
    public function update(User $user, Brand $brand)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param User $user
     * @param Brand $brand
     * @return mixed
     */
    public function delete(User $user, Brand $brand)
    {
        return $user->hasRole('admin');
    }


    /**
     * Determine whether the user can permanently delete the vacancy.
     *
     * @param User $user
     * @param Brand $brand
     * @return mixed
     */
    public function forceDelete(User $user, Brand $brand)
    {
        return $user->hasRole('admin');
    }
}
