<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cloud extends Model
{
    protected $table = 'cloud';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $dates = ['updated_at', 'created_at'];
}
