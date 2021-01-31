<?php

namespace App\Http\Controllers\Tools;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Logs
{
    protected $userName;
    protected $userRole;
    public function __construct(Request $request)
    {
        $this->userName = $request->session()->get('name');
        $this->userRole = $request->session()->get('role_id') == 1 ? '超级管理员' : '用户';
    }
    public function info($msg)
    {
        Log::info($this->userRole . "->" . $this->userName . "->" . $msg);
    }

    public function error($msg)
    {
        Log::error($this->userRole . "->" . $this->userName . "->" . $msg);
    }
}
