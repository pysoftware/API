<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    /**
     * Determine whether the user can create vacancies.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'employee']);
    }

    /**
     * Determine whether the user can update the vacancy.
     *
     * @param User $user
     * @param Sale $sale
     * @return mixed
     */
    public function update(User $user, Sale $sale)
    {
        return $user->hasAnyRole(['admin', 'employee']);
    }

    /**
     * Determine whether the user can delete the vacancy.
     *
     * @param User $user
     * @param Sale $sale
     * @return mixed
     */
    public function delete(User $user, Sale $sale)
    {
        return $user->hasAnyRole(['admin', 'employee']);
    }


    /**
     * Determine whether the user can permanently delete the vacancy.
     *
     * @param User $user
     * @param Sale $sale
     * @return mixed
     */
    public function forceDelete(User $user, Sale $sale)
    {
        return $user->hasRole('admin');
    }
}
