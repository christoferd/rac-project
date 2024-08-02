<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait OrderingRecordsTrait
{
    /**
     * ! Up the list means the ordering value may be, or is decreased.
     */
    public function moveOrderingUp(string $modelClassName, int $id, string $orderingField): void
    {
        try {
            /**
             * @var Model $modelClassName
             */
            $recordMoving = $modelClassName::findOrFail($id);

            // find nearest
            $recordNearest = $modelClassName::where($orderingField, '<=', $recordMoving->ordering)
                                                  ->whereNot('id', $recordMoving->id)
                                                  ->limit(1)
                                                  ->orderBy($orderingField, 'desc')->get()->first();
            // No vehicle found?!
            if(is_null($recordNearest)) {
                throw new \Exception('Code Error? Vehicle not found.');
            }

            // swap ordering with nearest
            // if different values
            if($recordNearest->ordering !== $recordMoving->ordering) {
                $newVal = $recordNearest->ordering;
                $recordNearest->ordering = $recordMoving->ordering;
                $recordNearest->save();
                $recordMoving->ordering = $newVal;
                $recordMoving->save();
            }
            else {
                // Found another record with same ordering value
                $origValue = $recordMoving->ordering;
                // Upate all records equal or above this one
                $table = $recordMoving->getTable();
                DB::statement("UPDATE `$table` SET `ordering` = (`ordering` + 1) WHERE `id` != {$recordMoving->id} AND `ordering` >= $origValue");
            }
        }
        catch(\Throwable $ex) {
            Log::error("Failed to reorder record. function moveOrderingUp(int $id, string $orderingField): void");
            Log::error($ex);
            throw $ex;
        }
    }

    public function moveOrderingDown(string $modelClassName, int $id, string $orderingField): void
    {
        try {
            $recordMoving = $modelClassName::findOrFail($id);

            // find nearest
            $recordNearest = $modelClassName::where($orderingField, '>=', $recordMoving->ordering)
                                                  ->whereNot('id', $recordMoving->id)
                                                  ->limit(1)
                                                  ->orderBy($orderingField, 'asc')->get()->first();
            // No vehicle found?!
            if(is_null($recordNearest)) {
                throw new \Exception('Code Error? Vehicle not found.');
            }

            $origValue = $recordMoving->ordering;

            if($recordNearest->ordering === $recordMoving->ordering) {
                // Found another record with same ordering value
                // Upate all records equal or above this one
                $table = $recordMoving->getTable();
                DB::statement("UPDATE `$table` SET `ordering` = (`ordering` + 1) WHERE `id` != {$recordMoving->id} AND `ordering` >= $origValue");

                // Now the nearest will definitely be different
                $recordNearest = $modelClassName::where($orderingField, '>=', $recordMoving->ordering)
                                                      ->whereNot('id', $recordMoving->id)
                                                      ->limit(1)
                                                      ->orderBy($orderingField, 'asc')->get()->first();
            }

            // if different values
            // swap ordering with nearest
            $newVal = $recordNearest->ordering;
            $recordNearest->ordering = $origValue;
            $recordNearest->save();
            $recordMoving->ordering = $newVal;
            $recordMoving->save();
        }
        catch(\Throwable $ex) {
            Log::error("Failed to reorder record. function moveOrderingDown(int $id, string $orderingField): void");
            Log::error($ex);
            throw $ex;
        }

    }

}
