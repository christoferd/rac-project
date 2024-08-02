<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\ActiveFieldModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\WithTrashedScope;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Task
 *
 * @property int                                                                    $id
 * @property int                                                                    $group_num
 * @property string                                                                 $title
 * @property string                                                                 $description
 * @property int                                                                    $ordering
 * @property int                                                                    $enabled
 * @property \Illuminate\Support\Carbon|null                                        $created_at
 * @property \Illuminate\Support\Carbon|null                                        $updated_at
 * @property string|null                                                            $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rental> $rentals
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @property int                                                                    $active
 * @property mixed                                                                  $notes
 * @method static \Illuminate\Database\Eloquent\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTrashed()
 * @mixin \Eloquent
 */
class Task extends ObjectiveModel
{
    // use HasFactory;
    use SoftDeletes, ActiveFieldModelTrait;

    protected $fillable = [
        'title',
        'active',
        'ordering',
    ];

    protected array $defaultValues = [
        'title'    => '',
        'active'   => '1',
        // Leave ordering 0 so that it triggers automatic set ordering when saving
        'ordering' => '0',
    ];

    protected array $rulesCreate = [
        'title'    => 'string|max:250',
        'active'   => 'required|int|in:0,1',
        'ordering' => 'required|int|min:0',
    ];

    protected array $rulesEdit = [
        'title'  => 'string|max:250',
        'active' => 'required|int|in:0,1',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new WithTrashedScope());

        static::created(function(Task &$task) {
            Log::debug('Created Task #'.$task->id);
            Rental::handleEventTaskCreated($task);
        });

        static::saving(function(Task &$task) {
            if(intval($task->ordering) === 0) {
                // Ordering - set last on list
                $task->ordering = (Task::where('group_num', $task->group_num)->max('ordering') + 1);
                // DO NOT SAVE here, otherwise endless loop;
                // This directly edits the task before saving.
            }
        });

        static::deleted(function(Task &$task) {
            Log::debug('Deleted Task #'.$task->id);
            Rental::handleEventTaskDeleted($task);
        });
    }

    public function rentals(): BelongsToMany
    {
        return $this->belongsToMany(Rental::class)
                    ->withPivot(['completed', 'user_completed', 'datetime_completed']);
    }

    function allowDelete(): bool
    {
        // Auth
        if(!\userCan('delete records')) {
            $this->message = __('You are not authorized to delete');
            return false;
        }

        // Check if attached to records
        // Chris D. 9-Apr-2024 - allow Soft Deleting, later will have a History/Activity of actions logged, so a history will be stored.
        // if($this->rentals->isNotEmpty()) {
        //     $this->message = __('This record is attached to:').' '.__('Rental');
        //     return false;
        // }

        return true;
    }

}
