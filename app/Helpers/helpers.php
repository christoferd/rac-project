<?php

if(!defined('NEWLINE')) {
    define('NEWLINE', "\n");
}

$numberFormatter = null;

/**
 * Check if current user has Role 'manager'
 *
 * @return bool
 */
function isManager()
{
    return Auth::user()->hasRole(['manager'], 'web');
}

/**
 * Check if current user has Role 'worker'
 *
 * @return bool
 */
function isWorker()
{
    return Auth::user()->hasRole(['worker'], 'web');
}

/**
 * Check if current user has Role 'admin'
 *
 * @return bool
 */
function isAdmin()
{
    return Auth::user()->hasRole(['admin'], 'web');
}

/**
 * Check if current user has Role 'developer'
 *
 * @return bool
 */
function isDeveloper()
{
    return Auth::user()->hasRole(['developer'], 'web');
}

/**
 * Checks if user has role 'admin' or 'manager'
 *
 * @return bool
 */
function isManagerAdmin(): bool
{
    return (isManager() || isAdmin());
}

/**
 * Check \Auth::user()->can(...
 *
 * @param string $permissionName e.g. 'access rentals'
 * @param string $guard          Default: 'web'
 * @return bool
 */
function userCan(string $permissionName, string $guard = 'web'): bool
{
    return \Auth::user()->can($permissionName, $guard);
}

/**
 * Check if user has role>permission, else throw exception.
 *
 * @param string $rolePermission
 * @return void
 * @throws Exception
 */
function securityCheckUserCan(string $rolePermission)
{
    if(!userCan($rolePermission)) {
        throw new \Exception('User does not have permission to: '.$rolePermission);
    }
}

/**
 * Return human legible date string, using $dateThatCanBeParsedByCarbon parsed and formatted by Carbon
 *
 * @param string $dateThatCanBeParsedByCarbon
 * @param int    $format    1 = "l j F" 2 = "l j F h:i"
 * @param string $separator Default: ' '
 * @return string
 * @throws Exception
 */
function dateLocalized(string $dateThatCanBeParsedByCarbon, int $format = 1, string $separator = ' '): string
{
    $c = \Carbon\Carbon::parse($dateThatCanBeParsedByCarbon);
    if($format === 1) {
        return __($c->format('l')).$separator.__($c->format('j')).$separator.__($c->format('F'));
    }
    elseif($format === 2) {
        return __($c->format('l')).$separator.__($c->format('j')).$separator.__($c->format('F'))
               .$separator.$c->format('h:i');
    }
    else {
        throw new \Exception(__FUNCTION__.': format ('.$format.') not accepted.');
    }
}

/**
 * My number formatter.
 * Style = NumberFormatter::DEFAULT_STYLE
 * NumberFormatter is bundled with PHP
 * https://www.php.net/manual/en/class.numberformatter.php
 */
function nf(mixed $number, int $style = NumberFormatter::DEFAULT_STYLE): string
{
    global $numberFormatter;
    if(is_null($numberFormatter)) {
        $numberFormatter = new NumberFormatter(App::currentLocale(), $style);
    }
    $res = $numberFormatter->format($number);
    if($res === false) {
        return '';
    }
    return $res;
}

function nfCurrency(mixed $number)
{
    if(empty($number)) {
        return 0.00;
    }
    return nf($number, NumberFormatter::CURRENCY);
}

function nfDecimal(mixed $number)
{
    if(empty($number)) {
        return 0.00;
    }
    return nf($number, NumberFormatter::DECIMAL);
}

function nf0(mixed $number)
{
    if(empty($number)) {
        $number = 0;
    }
    return nf(round($number, 0));
}

function nf2(mixed $number)
{
    if(empty($number)) {
        $number = 0;
    }
    return nf(round($number, 2));
}

function r0(mixed $number)
{
    if(empty($number)) {
        return 0.00;
    }
    return round($number, 0);
}

function r2(mixed $number)
{
    if(empty($number)) {
        return 0.00;
    }
    return round($number, 2);
}

function numberUnformat($number, $dec_point = '.', $thousands_sep = ',')
{
    $number = trim($number, '$ ');
    $type = (strpos($number, $dec_point) === false) ? 'int' : 'float';
    $number = str_replace(array($dec_point, $thousands_sep), array('.', ''), $number);
    settype($number, $type);

    return $number;
}

/**
 * Remove ' and " from string.
 *
 * @param string $str
 * @return string
 */
function removeQuotes(string $str)
{
    return str_replace(['"', '\''], '', $str);
}

/**
 * Uses expression if(!$mixed)
 *
 * @param mixed        $mixed
 * @param mixed        $whatToReturnOnFalsy
 * @param mixed|string $elseReturnThis Default: ''
 * @return mixed|string
 */
function falsy(mixed $mixed, mixed $whatToReturnOnFalsy, mixed $elseReturnThis = ''): mixed
{
    if(!$mixed) {
        return $whatToReturnOnFalsy;
    }
    return $elseReturnThis;
}

/**
 * Uses expression if($mixed)
 *
 * @param mixed        $mixed
 * @param mixed        $whatToReturnOnTruthy
 * @param mixed|string $elseReturnThis Default: ''
 * @return mixed|string
 */
function truthy(mixed $mixed, mixed $whatToReturnOnTruthy, mixed $elseReturnThis = ''): mixed
{
    if($mixed) {
        return $whatToReturnOnTruthy;
    }
    return $elseReturnThis;
}

function systemConfig(string $fieldName)
{
    return App\Models\SystemConfig::getValue($fieldName);
}

/**
 * Translate all params and return them concatenated by a space.
 * - Supports Strings & Arrays
 * - Note: when a param is not a supported type, it will not be translated, and just be added back into the final translation.
 *
 * @param ...$params
 * @return string
 */
function __t(...$params): string
{
    $arr = [];
    foreach($params as $t) {
        if(is_string($t)) {
            $arr[] = __($t);
        }
        elseif(is_array($t)) {
            $arr[] = implode(' ', __arr($t));
        }
        else {
            $arr[] = $t;
        }
    }
    return implode(' ', $arr);
}

/**
 * Translate all words within the string, one at a time.
 * Explode string based on separator (default: space).
 *
 * @param string $words
 * @param string $separator
 * @return string
 */
function __tw(string $words, string $separator = ' '): string
{
    $arr = explode($separator, $words);
    $words = [];
    foreach($arr as $word) {
        $words[] = __($word);
    }
    return implode($separator, $words);
}

/**
 * Translate all words within the string, one at a time.
 * Explode string based on separator (default: space).
 *
 * @param string $parts
 * @param string $separator
 * @return string
 */
function __tCsv(string $parts, string $separator = ','): string
{
    $arr = explode($separator, $parts);
    return __t($arr);
}

/**
 * Translate all params and use sprintf( {1st param}, {...other params} )
 *
 * @param ...$params
 * @return string
 */
function __tSprintf(...$params): string
{
    $arr = [];
    foreach($params as $t) {
        $arr[] = __($t);
    }
    $format = array_shift($arr);
    // https://www.php.net/manual/en/function.vsprintf.php
    // Operates as sprintf() but accepts an array of arguments, rather than a variable number of arguments.
    return vsprintf($format, $arr);
}

/**
 * Translates one word singular/plural, depending on number.
 *
 * @param string $singular
 * @param string $plural
 * @param int    $num
 * @return string
 */
function __p(string $singular, string $plural, int $num): string
{
    if($num > 1) {
        return __($plural);
    }
    return __($singular);
}

/**
 * Translate all values of an array.
 * ! Caution: Will skip any values that are not strings.
 *
 * @param array $arr
 * @return array
 */
function __arr(array $arr): array
{
    foreach($arr as $key => $value) {
        if(is_string($value)) {
            $arr[$key] = __($value);
        }
        else {
            $arr[$key] = $value;
        }
    }
    return $arr;
}

/**
 * Replace \r\n and \n in string with <br/>
 *
 * @param string $str
 * @param string $br
 * @return string
 */
function rnl2br(string $str, string $br = '<br/>'): string
{
    $str = str_replace("\r\n", $br, $str);
    return str_replace("\n", $br, $str);
}

/**
 * Wrap a string in html tags and insert any supplied attributes.
 * - if an attribute value is an empty string, the ="" part will be omitted.
 *
 * @param string $tag
 * @param string $slot
 * @param array  $attributes
 * @return string
 */
function htmlTag(string $tag, string $slot, array $attributes = []): string
{
    $attr = '';
    foreach($attributes as $attribute => $value) {
        if($value === '') {
            $attr .= $attribute.' ';
        }
        else {
            $attr .= $attribute.'="'.$value.'" ';
        }
    }
    return sprintf('<%s %s>%s</%s>', $tag, $attr, $slot, $tag);
}

function isProduction()
{
    return App::environment('production');
}

function addSlashesToDoubleQuotes(string $str)
{
    return str_replace('"', '\\"', $str);
}

if(!function_exists('messageInfo')) {
    function messageInfo(string $message, string $actionLabel = '', string $actionUrl = ''): void
    {
        if($actionLabel !== '') {
            $message .= '[br]'.\messageCTA($actionLabel, $actionUrl);
        }
        \App\Library\SessionAlert::info($message);
    }
}

if(!function_exists('messageWarning')) {
    function messageWarning(string $message, string $actionLabel = '', string $actionUrl = ''): void
    {
        if($actionLabel !== '') {
            $message .= '[br]'.\messageCTA($actionLabel, $actionUrl);
        }
        \App\Library\SessionAlert::warning($message);
    }
}

if(!function_exists('messageSuccess')) {
    function messageSuccess(string $message, string $actionLabel = '', string $actionUrl = ''): void
    {
        if($actionLabel !== '') {
            $message .= '[br]'.\messageCTA($actionLabel, $actionUrl);
        }
        \App\Library\SessionAlert::success($message);
    }
}

if(!function_exists('messageError')) {
    function messageError(string $message, string $actionLabel = '', string $actionUrl = ''): void
    {
        if($actionLabel !== '') {
            $message .= '[br]'.\messageCTA($actionLabel, $actionUrl);
        }
        \App\Library\SessionAlert::error($message);
    }
}

if(!function_exists('messageCTA')) {
    function messageCTA(string $actionLabel = '', string $actionUrl = ''): string
    {
        return '<a class="message-cta" href="'.$actionUrl.'">'.$actionLabel.'</a>';
    }
}

if(!function_exists('linkTo')) {
    function linkTo(string $actionUrl = '', string $actionLabel = '', string $class = ''): string
    {
        return '<a class="'.$class.'" href="'.$actionUrl.'">'.($actionLabel ?: $actionUrl).'</a>';
    }
}

if(!function_exists('debugOn')) {
    function debugOn(): bool
    {
        return config('app.debug', false);
    }
}
