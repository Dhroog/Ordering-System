<?php

namespace App\Policies;

use App\Models\Main_category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Main_categoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Main_category  $mainCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Main_category $mainCategory)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Main_category  $mainCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Main_category $mainCategory)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Main_category  $mainCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Main_category $mainCategory)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Main_category  $mainCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Main_category $mainCategory)
    {
        return $user->is_admin == 1;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Main_category  $mainCategory
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Main_category $mainCategory)
    {
        return $user->is_admin == 1;
    }
}
