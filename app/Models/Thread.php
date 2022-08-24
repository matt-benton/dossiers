<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Thread extends Model
{
    protected static function booted()
    {
      // clean up the threadables table after deleting thread
      static::deleted(function ($thread) {
        DB::table('threadables')->where('thread_id', $thread->id)->delete();
      });
    }

    public function people()
    {
      return $this->morphedByMany(Person::class, 'threadable');
    }

    public function interests()
    {
      return $this->morphedByMany(Interest::class, 'threadable');
    }

    public function developments()
    {
      return $this->hasMany(Development::class);
    }
}
