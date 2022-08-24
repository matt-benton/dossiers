<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function people()
    {
      return $this->belongsToMany(Person::class);
    }

    public function threads()
    {
      return $this->morphToMany(Thread::class, 'threadable');
    }
}
