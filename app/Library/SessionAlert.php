<?php

namespace App\Library;

use Illuminate\Support\Facades\Session;

class SessionAlert
{
    // static $alertTypes = ['success', 'warning', 'error', 'info'];

    static function error(string $message)
    {
        Session::push('session_alerts', ['type' => 'error', 'message' => $message]);
    }

    static function success(string $message)
    {
        Session::push('session_alerts', ['type' => 'success', 'message' => $message]);
    }

    static function warning(string $message)
    {
        Session::push('session_alerts', ['type' => 'warning', 'message' => $message]);
    }

    static function info(string $message)
    {
        Session::push('session_alerts', ['type' => 'info', 'message' => $message]);
    }

    static function getSessionAlerts(): array
    {
        return Session::remove('session_alerts') ?? [];
    }
}
