<?php

namespace App\Observers;

use App\Models\ObjectiveModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Traits\LivewireAlerts;

class ObjectiveObserver
{
    use LivewireAlerts;

    static string $table = '#?Ref:ObjectiveObserver?#';

    /*
     * Private Methods
     */

    private static function genCacheKeyTableChanged(): string
    {
        return 'ts_observe_table_changed_'.static::$table;
    }

    /*
     * Accessible Methods
     */

    static function recordTimestampTableChanged(): int
    {
        $timestamp = time();
        Cache::forever(self::genCacheKeyTableChanged(), $timestamp);
        return $timestamp;
    }

    static function checkTimestampTableChangedSince(int $timestamp): bool
    {
        $tsChanged = static::getTimestampTableChanged();
        if($tsChanged === 0) {
            return false;
        }
        return ($tsChanged > $timestamp);
    }

    static function getTimestampTableChanged(): int
    {
        return Cache::get(self::genCacheKeyTableChanged(), 0);
    }

    /*
     * Methods used for Observation
     */

    public function created(Model|ObjectiveModel $model): void
    {
        static::recordTimestampTableChanged();
    }

    public function updated(Model|ObjectiveModel $model): void
    {
        $this->alertDebug('updated(Model: '.\class_basename($model));
        static::recordTimestampTableChanged();
    }

    public function deleted(Model|ObjectiveModel $model): void
    {
        static::recordTimestampTableChanged();
    }

    public function restored(Model|ObjectiveModel $model): void
    {
        static::recordTimestampTableChanged();
    }

    public function forceDeleted(Model|ObjectiveModel $model): void
    {
        static::recordTimestampTableChanged();
    }

    public function dispatch()
    {
        //
    }
}
