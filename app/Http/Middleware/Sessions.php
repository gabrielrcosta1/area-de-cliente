<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Sessions
{
    public static function createSession($sessionData)
    {
        session_start();

        foreach ($sessionData as $sessionName => $sessionValue) {
            $_SESSION[$sessionName] = $sessionValue;
        }
    }

    public static function checkSession($session_name)
    {
        if (!isset($_SESSION[$session_name])) {
            $response = new RedirectResponse('/login');
            $response->send();
            return;
        } else {
            return true;
        }
    }
}
