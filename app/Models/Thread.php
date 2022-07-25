<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function people()
    {
      return $this->morphedByMany(Person::class, 'threadable');
    }

    public function developments()
    {
      return $this->hasMany(Development::class);
    }
}
