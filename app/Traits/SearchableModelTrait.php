<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchableModelTrait
{
    // Required
    // static array $searchFields = [];
    // static string $searchOrderField = '';
    // static string $searchOrderDirection = '';
    public bool $isSearchable = true;

    /**
     * Required by implementing LaravelViews\Data\Contracts\Searchable
     *
     * @param Builder     $query
     * @param array  $fields
     * @param string $value
     * @return Builder
     */
    static function searchItems(Builder $query, array $fields = [], string $value = ''): Builder
    {
        // @todo Clean searchString
        $words = \preg_split('/[\s,\\\*]+/', trim($value));

        foreach($words as $word) {
            if(strlen($word) < 2) {
                // skip
                continue;
            }
            $word = trim($word);
            $s = "%{$word}%";
            $query->where(
                function($query) use ($s, $fields) {
                    foreach($fields as $field) {
                        $query->orWhere($field, 'LIKE', $s);
                    }
                }
            );
        }

        return $query;
    }

    /**
     * Custom search
     *
     * @param string $searchString   Supports multiple words separated by spaces.
     * @return Builder
     */
    static function search(string $searchString): Builder
    {
        $searchString = trim($searchString);

        // @todo Figure out which is best to use here query()/newQuery()/newModelQuery()/newQueryWithoutRelationships()

        $query = static::newModelInstance()->newQueryWithoutRelationships();
        if($searchString === '') {
            return $query;
        }
        $query = static::searchItems($query, static::$searchFields, $searchString);

        if(static::$searchOrderField) {
            $query->orderBy(static::$searchOrderField, static::$searchOrderDirection);
        }

        return $query;
    }

}
