<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
