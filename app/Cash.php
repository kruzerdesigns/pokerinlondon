<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cashGames';

    protected $fillable = ['casino', 'tables', 'stakes', 'update', 'game'];
}
