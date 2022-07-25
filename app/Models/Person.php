<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $appends = ['birthmonth_text'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function threads()
    {
      return $this->morphToMany(Thread::class, 'threadable');
    }

    public function getBirthmonthTextAttribute()
    {
      $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

      return $this->birthmonth ? $months[$this->birthmonth - 1] : null;
    }
}
