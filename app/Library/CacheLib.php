<?php

namespace App\Library;

class CacheLib
{

    static function cacheClear(): bool
    {
        return cache()->clear();
    }

}
