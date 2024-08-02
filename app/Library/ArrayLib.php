<?php

namespace App\Library;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Log;

class ArrayLib
{
    /**
     * Check all elements in array if "empty()"
     *
     * @param array $arr
     * @return bool Returns true any elements are not "empty".
     */
    static function hasDataNotEmpty(array $arr)
    {
        foreach($arr as $val) {
            if(!empty($val)) {
                return true;
            }
        }
        return false;
    }

    static function escape(array $arr)
    {
        $ret = [];
        foreach($arr as $key => $val) {
            if(is_string($val)) {
                $val = e($val);
            }
            $ret[$key] = ($val);
        }
        return $ret;
    }

    /**
     * Checks for changes from $arr to $arr2
     * Checks Key/Values in $arr2 where the Key is present in $arr.
     * Will return true if differences are found, that is, where a value in $arr2 was not == to values in $arr.
     * Will return true if any keys in $arr are not present in $arr2.
     *
     * @param array $arr
     * @param array $arr2
     * @param bool  $treatEmptyValuesAsEqual
     * @return bool False returned if they are same/similar. False = No differences found.
     */
    static function hasDifferences(array $arr, array $arr2, bool $treatEmptyValuesAsEqual = false): bool
    {
        Log::debug('-- function hasDifferences...');

        foreach($arr as $key => $value) {
            // Check Key
            if(!isset($arr2[$key])) {
                Log::debug('Key ('.$key.') not found in $arr2.');
                return true;
            }

            // Log
            try {
                Log::debug(sprintf('Compare [%s] = (%s) with (%s)', $key, $value, $arr2[$key]));
            }
            catch(Exception $ex) {
                Log::debug('!ERROR! with last op: '.$ex->getMessage());
            }

            // Check Value
            if($treatEmptyValuesAsEqual) {
                // Check both empty
                if(empty($value) && empty($arr2[$key])) {
                    continue;
                }
            }

            if($value != $arr2[$key]) {
                // found difference
                Log::debug('-- END found difference (return true)');
                return true;
            }
        }
        // no differences found
        Log::debug('-- END no differences found (return false)');
        return false;
    }

    /**
     * Build custom array, simple key-value pairs,
     * from collection, using one of the fieldnames as the key ('id')
     * and another/or-same as the value.
     *
     * @param Collection $collection
     * @param string     $keyFieldname
     * @param string     $valueFieldname
     * @param bool       $sort
     *
     * @return array
     */
    public static function buildArrayFromCollection(Collection &$collection, $keyFieldname, $valueFieldname, $sort = false)
    {
        $rowsArray = array();
        foreach($collection as $row) {
            if(is_array($row)) {
                $rowsArray[$row[$keyFieldname]] = $row[$valueFieldname];
            }
            else { // Object
                $rowsArray[$row->{$keyFieldname}] = $row->{$valueFieldname};
            }
        }

        if($sort) {
            asort($rowsArray);
        }

        return $rowsArray;
    }

}
