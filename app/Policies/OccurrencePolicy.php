<?php

namespace App\Policies;

use App\Models\Occurrence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OccurrencePolicy
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
     * @param  \App\Models\Occurrence  $occurrence
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Occurrence $occurrence)
    {
      $person = $occurrence->people->first();

      return $person->user_id === $user->id;
    }
}
