<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    public function people()
    {
      return $this->morphedByMany(Person::class, 'occurrentable');
    }
}
