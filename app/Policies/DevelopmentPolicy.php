<?php

namespace App\Policies;

use App\Models\Development;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevelopmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Development  $development
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Development $development)
    {
      return $development->thread->user_id === $user->id;
    }
}
