<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class Group extends Model
{
    use HasFactory;

    public function people()
    {
      return $this->belongsToMany(Person::class)->withPivot('role');
    }
}
