<?php

namespace App\Library;

use Carbon\Carbon;
use Exception;

class DateLib // extends Carbon
{
    // Localized Format
    // https://www.php.net/manual/en/function.strftime.php
    const AU                 = 'd/m/Y';
    const DMY_LOCALIZED      = '%e %b %Y'; // Output ES: 1 ene. 2021
    const FORMAT_DATE_TIME_A = 'd/m/Y h:ia';
    const DB_DATE_FORMAT     = 'Y-m-d';
    const DB_DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Use getCurrentMonth()
     *
     * @var int Value from intval(date('n'))
     */
    private static int $currentMonth = 0;

    /**
     * Use getCurrentYear()
     *
     * @var int Value from intval(date('Y'))
     */
    private static int $currentYear = 0;

    /**
     * Keep this cached for multiple calls
     *
     * @var string
     */
    private static string $cachedTodayDateString = '';

    /**
     * Convert date to mysql date format. ccyy-mm-dd
     * Returns $date if date "isEmpty"
     *
     * @param string $date
     *
     * @return string
     * @throws Exception
     */
    static function toMysql(string $date): string
    {
        if(static::isEmpty($date)) {
            return $date;
        }

        if(str_contains($date, '/')) {
            $d = explode('/', $date);

            // Only support 4 digit year
            if(count($d) == 3 && strlen($d[2]) == 4) {
                return $d[2].'-'.$d[1].'-'.$d[0];
            }
            else {
                throw new Exception("Date::toMysql Error: Date Year must be 4 digits.");
            }
        }
        elseif(str_contains($date, '-')) {
            // may be a mysql date-time string
            return substr($date, 0, 10);
        }

        throw new Exception("Unexpected date format, $date. Unable to convert to MySQL date.");
    }

    /**
     * Test if value is mysql date format.
     * (does not return true for long DATE-TIME)
     *
     * @param string $val
     *
     * @return bool
     */
    public static function isMysqlDate(string $val): bool
    {
        return (preg_match('/^\d{4}-\d{2}-\d{2}$/', $val) === 1);
    }

    /**
     * Test if value is mysql DATE-TIME format.
     * 22-Feb-2021 - Update to allow milliseconds
     * (does not return true for just DATE)
     *
     * @param string $val
     *
     * @return bool
     */
    public static function isMysqlDateTime(string $val): bool
    {
        // * = 0 or more quantifier
        return boolval(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.*$/', $val));
    }

    /**
     * Create Carbon from MySQL DateTime value.
     *
     * @param string|null $mysqlDateTime
     * @return Carbon|null Carbon instance, or null if $mysqlDateTime is null.
     */
    public static function createFromMysqlDateTime(?string $mysqlDateTime): Carbon|null
    {
        if(is_null($mysqlDateTime)) {
            return null;
        }
        return Carbon::createFromFormat(self::DB_DATETIME_FORMAT, $mysqlDateTime);
    }

    /**
     * Create Carbon from MySQL Date value.
     *
     * @param string|null $mysqlDate
     * @return Carbon|null Carbon instance, or null if $mysqlDate is null.
     */
    public static function createFromMysqlDate(?string $mysqlDate): Carbon|null
    {
        if(is_null($mysqlDate)) {
            return null;
        }
        return Carbon::createFromFormat(self::DB_DATE_FORMAT, $mysqlDate);
    }

    /**
     * Check for match against: null,'','00/00/0000'
     *
     * @param string|null $dateOrDateTime Can be anything, date or date time
     * @return bool
     */
    public static function isEmpty(?string $dateOrDateTime): bool
    {
        if(is_null($dateOrDateTime)
           || $dateOrDateTime === ''
           || $dateOrDateTime === '00/00/0000'
           || $dateOrDateTime === '0000-00-00'
           || $dateOrDateTime === '0000-00-00 00:00:00'
        ) {
            return true;
        }
        return false;
    }

    /**
     * Check if $ob is object of class Carbon\Carbon
     *
     * @param mixed $ob
     * @return bool
     */
    static function isCarbonObject(mixed &$ob): bool
    {
        return (get_class($ob) == 'Carbon\Carbon');
    }

    /**
     * Create date from AU style date... 22/02/1976
     */
    static function createFromAUDate($d): Carbon
    {
        return Carbon::createFromFormat('d/m/Y', $d);
    }

    /**
     * Convert mysql date to human date
     * Very fast method. Rearranges the characters to form a date formatted for most of the world dd/mm/yyyy.
     *
     * @param string $mysqlDate
     * @return string
     */
    static function mysqlDateToHuman(string $mysqlDate): string
    {
        return $mysqlDate[8].$mysqlDate[9].'/'.$mysqlDate[5].$mysqlDate[6].'/'.substr($mysqlDate, 0, 4);
    }

    /**
     * Format date from a mysql date string;
     * Using Carbon class and the current config > app.locale
     *
     * @param string $mysqlDate
     * @param string $format
     * @return string Return '' when $mysqlDate is not valid mysql date format.
     */
    static function mysqlDateToFormat(string $mysqlDate, string $format): string
    {
        if(!static::isMysqlDate($mysqlDate)) {
            return '';
        }
        return Carbon::createFromFormat('Y-m-d', $mysqlDate)->locale(config('app.locale'))->translatedFormat($format);
    }

    /**
     * @param string $mysqlDateFrom
     * @param string $mysqlDateTo
     * @return int|false Returns false if either date is not a valid mysql date string.
     */
    static function diffInDaysMysqlDates(string $mysqlDateFrom, string $mysqlDateTo): int|false
    {
        if(static::isMysqlDate($mysqlDateFrom) && static::isMysqlDate($mysqlDateTo)) {
            // more efficient
            if(substr($mysqlDateFrom, 0, 7) === substr($mysqlDateTo, 0, 7)) {
                $arr1 = explode('-', $mysqlDateFrom);
                $arr2 = explode('-', $mysqlDateTo);
                return intval(end($arr2)) - intval(end($arr1));
            }
            // default use Carbon
            $c = Carbon::createFromFormat('Y-m-d', $mysqlDateFrom);
            $cc = Carbon::createFromFormat('Y-m-d', $mysqlDateTo);
            return DateLib::diffInDaysOfDateOnly($c, $cc);
        }
        return false;
    }

    /**
     * Fix issue where daylight savings diffInDays didn't work due to the start of the day being 01:00:00
     *
     *
     * @param Carbon $c Does not affect this object.
     * @param Carbon $cc Does not affect this object.
     * @return int
     */
    static function diffInDaysOfDateOnly(Carbon &$c, Carbon &$cc): int
    {
        $c01012000 = Carbon::parse('2000-01-01');
        $c01012000->hour($c->format('G')); // 24h without leading zeros
        $c01012000->minutes($c->format('i'));
        $c01012000->seconds($c->format('s'));
        $diff = $c01012000->diffInDays($c);
        $c01012000->hour($cc->format('G'));
        $c01012000->minutes($cc->format('i'));
        $c01012000->seconds($cc->format('s'));
        $diff2 = $c01012000->diffInDays($cc);
        // dump( "diffs: $diff2 - $diff = ".($diff2-$diff));
        return ($diff2-$diff);
    }
}
