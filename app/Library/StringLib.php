<?php

namespace App\Library;

use Illuminate\Support\Str;

class StringLib
{
    /**
     * Replace anything that is NOT A-Z a-z 0-9
     *
     * @param string $text
     * @param string $replaceWith
     * @return false|string|null
     */
    static function replaceAllExceptAZ09(string $text, string $replaceWith = ''): bool|string|null
    {
        return mb_ereg_replace('[^A-Za-z0-9]', $replaceWith, $text);
    }

    /**
     * Allows for grabbing wanted characters from a string.
     * Allows for adding extra characters/strings within at the same time.
     *
     * Example: onlyTheseChars('12 30 00', [0,1,':',3,4] will return: "12:30"
     *
     * @param string $str
     * @param array  $indexes
     * @return string
     */
    static function onlyTheseChars(string $str, array $indexes): string
    {
        $ret = '';
        foreach($indexes as $i) {
            if(\is_int($i)) {
                $ret .= ($str[$i] ?? '');
            }
            else {
                $ret .= $i;
            }
        }
        return $ret;
    }

    /**
     * Capatilize first letter of each word of a string.
     *
     * @param string $value
     * @return string
     */
    public static function mbUCWords($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    /**
     * Humanize a string.
     * Example: use for a field name `quantity_limit`, output: Quantity Limit
     * by christoferd.
     *
     * @param string $str
     * @param bool   $ucWordsOption
     * @return mixed
     */
    public static function humanize(string $str, bool $ucWordsOption = false): string
    {
        $str = static::mbUCFirst($str);
        $str = str_replace('_', ' ', $str);
        if($ucWordsOption) {
            $str = ucwords($str);
        }

        return $str;
    }

    public static function strEllipsis(string $str, int $limit, string $addedSuffix = '&hellip;'): string
    {

        if(strlen($str) > $limit) {
            return substr($str, 0, $limit).$addedSuffix;
        }

        return $str;
    }

    /**
     * Create plain Text table from Array, or Array of Arrays.
     * Great for dumping database table data to text only logs or display.
     *
     * @use          static::textTableFromArray($myArrayOfArrays,'My Multi Row Table',true); // multiple records/array
     *               static::textTableFromArray($simpleKeyValArray,'A Single Record',false); // single row, vertical
     *               view
     * @param        $arr
     * @param string $tableTitle
     * @param bool   $horizontalDisplay
     * @return string
     */
    public static function textTableFromArray($arr, $tableTitle = '', $horizontalDisplay = false)
    {
        if(!is_array($arr)) {
            echo '<p>! Param not Array. Got '.gettype($arr).' instead. Unable to create text-table.</p>';
        }

        $text = '';
        $newLine = "\r\n";
        $longestKey = 0;
        $longestVal = 0;
        $fieldNames = array();
        $fieldLen = array();

        // Hanlde Empty Array
        if(empty($arr)) {
            $arr = array('' => 'Empty');
        }

        if($horizontalDisplay) {
            if(isset($arr[0])) {
                $rows = $arr;
            }
            else {
                $rows = array($arr);
            }
        }
        else {
            $rows = array($arr);
        }

        foreach($rows as $row) {
            // fix
            if(isset($row[0])) {
                $row = $row[0];
            }

            foreach($row as $key => $val) {
                if(is_string($val) && (strlen($val) > $longestVal)) {
                    $longestVal = strlen($val);
                }

                if(strlen($key) > $longestKey) {
                    $longestKey = strlen($key);
                }

                // Horizontal stuff
                if(empty($fieldLen[$key])) {
                    // At least width of KEY
                    $fieldLen[$key] = strlen($key);
                }

                if(is_string($val) && ($fieldLen[$key] < strlen($val))) {
                    $fieldLen[$key] = strlen($val);
                }
                // - horizontal stuff
            }

            $fieldNames = array_keys($row);
        }

        if($horizontalDisplay) {
            // Horizontal
            $sum = array_sum($fieldLen);
            $totalLen = (count($fieldLen) * 3) + $sum;
            $tableLine = sprintf("|%'-{$totalLen}s|", '').$newLine;

            // Table Title
            if($tableTitle != '') {
                $text .= $tableLine;
                $text .= sprintf("|%-{$totalLen}s|", ' '.$tableTitle).$newLine;
            }

            $text .= $tableLine;

            // Titles
            foreach($fieldNames as $fieldName) {
                $len = $fieldLen[$fieldName];
                $text .= sprintf("| %-{$len}s ", static::humanize($fieldName));
            }
            $text .= ' |'.$newLine;

            $text .= $tableLine;

            // Rows
            foreach($rows as $row) {
                // fix
                if(isset($row[0])) {
                    $row = $row[0];
                }

                foreach($row as $key => $val) {
                    $len = $fieldLen[$key];
                    if(is_array($val)) {
                        $text .= sprintf("| %-{$len}s ", json_encode($val));
                    }
                    else {
                        $text .= sprintf("| %-{$len}s ", $val);
                    }
                }
                $text .= ' |'.$newLine;
            }

            $text .= $tableLine;
        }
        else {
            // Vertical
            $tableLine = sprintf("| %'-{$longestKey}s | %'-{$longestVal}s |", '', '').$newLine;

            if($tableTitle != '') {
                $text .= $tableLine;
                $text .= sprintf("| %-{$longestKey}s   %-{$longestVal}s |", $tableTitle, '').$newLine;
            }

            $text .= $tableLine;

            foreach($arr as $key => $val) {
                if(is_array($val)) {
                    // avoid error
                    $val = 'Array('.count($val).')';
                }
                $text .= sprintf("| %-{$longestKey}s | %-{$longestVal}s |", $key, strval($val)).$newLine;
            }

            $text .= $tableLine;
        }

        return $newLine
               .$text.$newLine;
    }

    public static function isDigitsOnly($str)
    {
        return static::validateDigitsOnly($str);
    }

    public static function validateDigitsOnly($str)
    {
        $str = strval($str);
        $res = preg_match('/\d+/', $str);
        if($res) {
            return true;
        }

        return false;
    }

    /**
     * Return 'Yes' or 'No' depending on the value.
     * '0' / '' / 'N' / empty() will result in 'No', otherwise anything else will result in 'Yes'
     *
     * @param $val
     */
    public static function yesNo($val)
    {
        return ($val == '0' || $val == '' || $val == 'N' || empty($val) ? 'No' : 'Yes');
    }

    /**
     * Replace anything that's not a digit with blank.
     *
     * @param $str
     * @return string number, digits only
     */
    public static function digitsOnly($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    /**
     * Make sure the string has a training slash.
     *
     * @param string $str
     * @return string
     */
    static function trailingSlash(string $str)
    {
        $slash = '/';
        if(substr($str, -1) != $slash) {
            return $str.$slash;
        }

        return $str;
    }

    static function forwardSlashes($s)
    {
        return str_replace('\\', '/', $s);
    }

    /**
     * Replace back-slash with forward-slash.
     *
     * @param string $str
     * @return string|string[]
     */
    static function forwardSlashOnly(string $str)
    {
        return str_replace('\\', '/', $str);
    }

    static function camel($str)
    {
        return Str::camel($str);
    }

    /**
     * Pluralise a string if $count == 0 or $count > 1
     *
     * @param string $str
     * @param int    $count Default: 2
     * @return string
     */
    static function plural($str, $count = 2)
    {
        return Str::plural($str, $count);
    }

    /**
     * @param string $str
     * @param string $replaceWith
     * @return string
     */
    static function removeNewLines($str, $replaceWith = '')
    {
        $newLines = [PHP_EOL, "\r\n", "\n"];

        return str_replace($newLines, $replaceWith, $str);
    }

    static function trimSlashes($str)
    {
        return trim($str, '/\\');
    }

    static function rtrimSlashes($str)
    {
        return rtrim($str, '/\\');
    }

    static function replaceSpaces($str, $replaceWith = '_')
    {
        return str_replace(' ', $replaceWith, $str);
    }

    static function replaceNonAlphanumericCharacters(string $str, $replaceWith = '')
    {
        return preg_replace("/[^A-Za-z0-9]/", $replaceWith, $str);
    }

    /**
     * Multi byte char ucfirst
     * https://stackoverflow.com/questions/2517947/ucfirst-function-for-multibyte-character-encodings
     *
     * @param string      $str
     * @param string|null $encoding
     * @return string
     */
    static function mbUCFirst(string $str, string $encoding = null): string
    {
        if($encoding === null) {
            $encoding = mb_internal_encoding();
        }

        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).mb_substr($str, 1, null, $encoding);
    }

    static function validateEmail($email)
    {
        if(empty($email) || !is_string($email)) {
            return false;
        }
        // Chris D. 11-Dec-2020 - allow more stuff that is valid email: dots, plus symbol (in first part), and capital letters
        // Valid Email Example (unusual, but valid): ChrisD+Test123@Mindflow.com.au
        // Google Apps/G-Suite allows adding +[and characters] after first part of email for receiving emails as an alias address.
        // Check Wikipedia for valid email characters/format
        if(preg_match('/[_a-zA-Z0-9-+\\.]+(\\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\\.[a-zA-Z0-9-]+)*(\\.[a-zA-Z]{2,5})/', trim($email))) {
            return true;
        }

        return false;
    }

    static function isNullOrEmptyString($val)
    {
        if(is_null($val) || (is_string($val) && $val === '')) {
            return true;
        }

        return false;
    }

    static function replaceNewLines(string $message, string $replaceWith = '<br>')
    {
        $message = str_replace("\r\n", $replaceWith, $message);
        $message = str_replace("\n", $replaceWith, $message);

        return $message;
    }

    static function beginsWith(string $haystack, string $strToSearchFor)
    {
        return (\strpos($haystack, $strToSearchFor) === 0);
    }

    static function validateRegexOrAbort(string $str, string $pattern, int $abortCode = 404, string $abortMessage = 'Invalid Request')
    {
        // preg_match returns FALSE on error, 0 for no match, or 1 if matched
        return \boolval(preg_match($pattern, $str));

    }

}
