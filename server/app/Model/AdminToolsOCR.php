<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminToolsOCR extends Model
{
    protected $table = 'tools_ocr';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $dates = ['updated_at', 'created_at'];
}
