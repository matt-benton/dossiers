<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function people()
    {
        return $this->morphedByMany(Person::class, 'threadable');
    }

    public function interests()
    {
        return $this->morphedByMany(Interest::class, 'threadable');
    }

    public function groups()
    {
        return $this->morphedByMany(Group::class, 'threadable');
    }

    public function developments()
    {
        return $this->hasMany(Development::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
