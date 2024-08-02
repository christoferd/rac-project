<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\RentalTask
 *
 * @property int         $rental_id
 * @property int         $task_id
 * @property int         $completed
 * @property int         $user_completed
 * @property string|null $datetime_completed
 * @method static \Illuminate\Database\Eloquent\Builder|RentalTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RentalTask query()
 * @mixin \Eloquent
 */
class RentalTask extends Pivot
{
    // use HasFactory;

    static function updateRecord(Rental $rental, int $taskId, int $completed, int|null $userCompleted = null): void
    {
        $data = [
            'completed'          => $completed,
            'user_completed'     => $userCompleted,
            'datetime_completed' => \Carbon\Carbon::now()->toDateTimeString()
        ];
        $rental->tasks()
               ->updateExistingPivot($taskId, $data);
    }

    static function toggleCompleted(int $rentalId, int $taskId): void
    {
        // Rental
        $rental = Rental::findOrFail($rentalId);
        // Task
        $task = $rental->getTask($taskId);
        // Invalidate Related Cache
        static::hasCompletedAllTasks_clearCache($rentalId, $task->group_num);

        // Reverse/toggle value
        $completed = ($task->pivot->completed ? 0 : 1);
        $userCompleted = ($completed ? auth()->id() : 0);
        // Set date or reset to null
        $datetimeCompleted = ($completed ? \Carbon\Carbon::now()->toDateTimeString() : null);

        $task->rentals()->updateExistingPivot($rentalId, [
            'completed'          => $completed,
            'user_completed'     => $userCompleted,
            'datetime_completed' => $datetimeCompleted
        ]);
    }

    static function hasCompletedAllTasks(Rental &$rental, int $groupNum)
    {
        return Cache::remember("hasCompletedAllTasks_{$rental->id}_{$groupNum}", 14000,
            function() use ($rental, $groupNum) {
                // Only active tasks
                $groupTasks = $rental->getTaskGroup($groupNum, ['id', 'rental_task.completed', 'active'], 1);
                $count = $groupTasks->count();
                $countCompleted = count($groupTasks->where('completed', 1)->where('active', 1)->all());
                $res = ($count === $countCompleted);
                // Log::debug('function hasCompletedAllTasks: '.$rental->id, [
                //     'rental_id'      => $rental->id,
                //     'group_num'      => $groupNum,
                //     'count'          => $count,
                //     'countCompleted' => $countCompleted,
                //     'res'            => $res
                // ]);
                return $res;
            });
    }

    static function hasCompletedAllTasks_clearCache(int $rentalId, int $groupNum): void
    {
        Cache::forget("hasCompletedAllTasks_{$rentalId}_{$groupNum}");
    }

}
