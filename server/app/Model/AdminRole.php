<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $table = 'admin_role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;
}
