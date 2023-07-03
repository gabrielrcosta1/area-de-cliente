<?php

namespace App\Http\Middleware;



class Authorization extends Sessions
{

    public static function Auth()
    {
        Sessions::checkSession('user');
    }
}
