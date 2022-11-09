<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function people()
    {
        return $this->belongsToMany(Person::class)->withPivot('role');
    }

    public function threads()
    {
        return $this->morphToMany(Thread::class, 'threadable');
    }
}
