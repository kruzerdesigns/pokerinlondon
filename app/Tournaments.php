<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    protected $table = 'tournaments';

    protected $fillable = ['casino', 'event', 'stack', 'clock', 'date', 'buyin', 'start'];
}
