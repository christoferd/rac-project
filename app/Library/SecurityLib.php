<?php

namespace App\Library;

class SecurityLib
{
    static private $pepper = 'OIUERKJ(*&#$(*&#()(*';

    /**
     * Create token from value.
     * Uses app.key
     *
     * @param $value
     *
     * @return string
     */
    public static function createToken($value)
    {
        return md5(config('custom.key').$value.static::$pepper);
    }

    /**
     * Check value against received token (encrypted token value).
     *
     * @param $value
     * @param $token
     *
     * @return bool
     */
    public static function checkToken($value, $token)
    {
        $valueToken = static::createToken($value);
        return (strcmp($valueToken, $token) == 0);
    }

    /**
     * Use Security class to check a Token. App Abort if unsuccessful.
     *
     * @param $value
     * @param $token
     * @param $abortCode
     * @return bool
     * @throws \Exception
     */
    public static function checkTokenOrAbort($value, $token, $abortCode = 404)
    {
        $result = static::checkToken($value, $token);
        if(!$result) {
            if(\auth()->guest()) {
                abort($abortCode, 'Invalid Request');
            }
            throw new \Exception('Invalid Request - Token check failed.');
        }
        return $result;
    }

}
