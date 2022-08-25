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

    public function getPossibleNames()
    {
      return [
        $this->attributes['name'],
        $this->possessiveName(),
        $this->endOfSentenceName(),
        $this->nameWithComma(),
        $this->nameWithBeginningDoubleQuote(),
        $this->nameWithEndingDoubleQuote(),
        $this->nameWithBeginningSingleQuote(),
        $this->nameWithEndingSingleQuote(),
      ];
    }

    public function possessiveName()
    {
      return $this->attributes['name'] . "'s";
    }

    public function endOfSentenceName()
    {
      return $this->attributes['name'] . ".";
    }

    public function nameWithComma()
    {
      return $this->attributes['name'] . ",";
    }

    public function nameWithSemicolon()
    {
      return $this->attributes['name'] . ";";
    }

    public function nameWithBeginningDoubleQuote()
    {
      return '"' . $this->attributes['name'];
    }

    public function nameWithEndingDoubleQuote()
    {
      return $this->attributes['name'] . '"';
    }

    public function nameWithBeginningSingleQuote()
    {
      return "'" . $this->attributes['name'];
    }

    public function nameWithEndingSingleQuote()
    {
      return $this->attributes['name'] . "'";
    }
}
