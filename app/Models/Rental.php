<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Library\DateLib;
use App\Traits\KeyTagsTrait;
use App\Models\Traits\TasksModelTrait;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Rental
 *
 * @property int                                                                  $id
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property \Illuminate\Support\Carbon|null                                      $deleted_at
 * @property string                                                               $date_collect
 * @property string                                                               $time_collect
 * @property string                                                               $date_return
 * @property string                                                               $time_return
 * @property int                                                                  $client_id
 * @property int                                                                  $vehicle_id
 * @property int                                                                  $price_day
 * @property int                                                                  $days_to_charge
 * @property int                                                                  $price_total
 * @property string                                                               $notes
 * @property-read \App\Models\Client|null                                         $client
 * @property-read \App\Models\Vehicle|null                                        $vehicle
 * @method static \Database\Factories\RentalFactory factory($count = null, $state = [])
 * @method static Builder|Rental newModelQuery()
 * @method static Builder|Rental newQuery()
 * @method static Builder|Rental onlyTrashed()
 * @method static Builder|Rental query()
 * @method static Builder|Rental whereClientId($value)
 * @method static Builder|Rental whereCreatedAt($value)
 * @method static Builder|Rental whereDateCollect($value)
 * @method static Builder|Rental whereDateReturn($value)
 * @method static Builder|Rental whereDaysToCharge($value)
 * @method static Builder|Rental whereDeletedAt($value)
 * @method static Builder|Rental whereId($value)
 * @method static Builder|Rental whereNotes($value)
 * @method static Builder|Rental wherePriceDay($value)
 * @method static Builder|Rental wherePriceTotal($value)
 * @method static Builder|Rental whereTimeCollect($value)
 * @method static Builder|Rental whereTimeReturn($value)
 * @method static Builder|Rental whereUpdatedAt($value)
 * @method static Builder|Rental whereVehicleId($value)
 * @method static Builder|Rental withTrashed()
 * @method static Builder|Rental withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @mixin \Eloquent
 */
class Rental extends ObjectiveModel
{
    use HasFactory, SoftDeletes, KeyTagsTrait, TasksModelTrait;

    /**
     * Either $fillable with which fields may be fast filled.
     * OR $guarded with which fields may not be fast filled.
     *
     * @var string[]
     */
    protected $guarded = [
        'id',
        // these fields are calculated
        'days_to_charge',
        'price_total',
    ];

    /**
     * Rules to check against the Create form.
     *
     * @var string[]
     */
    protected array $rulesCreate = [
        'date_collect'   => 'required|date',
        'time_collect'   => 'required|string|date_format:H:i:s',
        'date_return'    => 'required|date|after_or_equal:date_collect',
        'time_return'    => 'required|string|date_format:H:i:s',
        'client_id'      => 'integer|min:1',
        'vehicle_id'     => 'required|min:1',
        'price_day'      => 'integer|min:0',
        'days_to_charge' => 'integer|min:0',
        'notes'          => 'string|max:1000',
    ];

    /**
     * Rules to check against the Create form.
     * ! Every editable field must have an entry here.
     * ! Keys are used to check if a field is live edit-able/update-able.
     *
     * @var string[]
     */
    protected array $rulesEdit = [
        'date_collect'   => 'required|date',
        'time_collect'   => 'required|string|date_format:H:i:s',
        'date_return'    => 'required|date|after_or_equal:date_collect',
        'time_return'    => 'required|string|date_format:H:i:s',
        'client_id'      => 'required|integer|min:1',
        'vehicle_id'     => 'required|integer|min:1',
        'price_day'      => 'integer|min:0',
        'days_to_charge' => 'integer|min:0',
        'notes'          => 'string|max:1000',
    ];

    protected array $messages = [
        'date_collect.*' => 'Fecha de retiro es necesario, y antes/igual de la fecha de retorno.',
        'date_return.*'  => 'Fecha retorno es necesario, y después/igual de la fecha de retiro.',
        'time_return.*'  => 'Hora de retorno es necesario.',
        'time_collect.*' => 'Hora de retiro es necesario.',
        'client_id.*'    => 'Cliente es necesario.',
        'vehicle_id.*'   => 'Vehículo es necesario.',
        'price_day.*'    => 'Precio es necesario. Solo dígitos.',
        'notes.*'        => 'Notas 1000 caracteres máximo',
    ];

    static array $labels = [
        'date_collect'   => 'Fechá Retiro',
        'time_collect'   => 'Hora Retiro',
        'date_return'    => 'Fechá Retorno',
        'time_return'    => 'Hora Retorno',
        'client_id'      => 'Cliente',
        'vehicle_id'     => 'Vehiculo',
        'price_day'      => '$ p/dia',
        'days_to_charge' => 'Días',
        'price_total'    => 'Total',
        'notes'          => 'Notas',
    ];

    public function __construct(array $attributes = [])
    {
        $todayDateString = Carbon::today()->toDateString();

        $this->defaultValues = [
            'date_collect'   => $todayDateString,
            'time_collect'   => config('rental.default.time_collect'),
            'date_return'    => $todayDateString,
            'time_return'    => config('rental.default.time_return'),
            // Default client created in ClientSeeder
            'client_id'      => 0,
            'vehicle_id'     => 0,
            'price_day'      => 0,
            'days_to_charge' => 0,
            'notes'          => '',
        ];

        // When Vehicle ID is supplied, we can get the price per day
        if(!empty($attributes['vehicle_id'])) {
            $vehicle = Vehicle::findOrFail($attributes['vehicle_id']);
            $this->defaultValues['price_day'] = $vehicle->vehicle_price;
        }

        $this->keyTagsPrefix = 'alquiler.';

        parent::__construct($attributes ?: $this->defaultValues);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)
                    ->using(RentalTask::class)
                    ->withPivot(['completed', 'user_completed', 'datetime_completed']);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::created(function(Rental &$rental) {
            // Tasks
            // - Group 1 tasks
            $tasks = Task::where('group_num', 1)
                         ->where('active', 1)
                         ->orderBy('ordering')
                         ->get();
            $rental->tasks()->saveMany($tasks);
        });

        static::saving(function(Rental &$rental) {
            // Minimum charge is one day
            $diffInDays = $rental->getDatesDiffDays();
            $rental->days_to_charge = $diffInDays;
            $rental->price_total = ($diffInDays * ($rental->price_day ?? 0));
        });

        static::deleted(function(Rental &$rental) {
            // Tasks
            Log::debug('Deleted Rental #'.$rental->id);
            Log::debug('... try delete/detach all associated tasks');
            $rental->tasks()->detach();
        });
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function priceTotal(): Attribute
    {
        return Attribute::make(
        // get a calculated value
            get: fn($value) => ($this->getAttribute('price_day') * $this->getAttribute('days_to_charge')),
            // store calculated value
            set: fn($value) => ($this->getAttribute('price_day') * $this->getAttribute('days_to_charge'))
        );
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function clientId(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value),
            set: fn($value) => ($value ?: 0)
        );
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function vehicleId(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value),
            set: fn($value) => ($value ?: 0)
        );
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function daysToCharge(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($this->getDatesDiffDays()),
            set: fn($value) => ($value > 0 ? $value : 1) // 1 minimum
        );
    }

    function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * @return string HTML
     */
    public function tooltipSummary(): string
    {
        // this should use a view
        return 'function tooltipSummary()';
    }

    function getKeyTags(): array
    {
        return $this->wrapOpenCloseTagsArray([
                                                 $this->keyTagsPrefix.'fecha_retiro',
                                                 $this->keyTagsPrefix.'hora_retiro',
                                                 $this->keyTagsPrefix.'fecha_retorno',
                                                 $this->keyTagsPrefix.'hora_retorno',
                                                 $this->keyTagsPrefix.'precio_por_dia',
                                                 $this->keyTagsPrefix.'dias_total',
                                                 $this->keyTagsPrefix.'precio_total',
                                             ]);
    }

    function getKeyTagsData(): array
    {
        return \array_combine($this->getKeyTags(), [
            DateLib::mysqlDateToHuman($this->date_collect),
            substr($this->time_collect, 0, 5),
            DateLib::mysqlDateToHuman($this->date_return),
            substr($this->time_return, 0, 5),
            $this->price_day,
            $this->days_to_charge,
            $this->price_total,
        ]);
    }

    function allowDelete(): bool
    {
        return true;
    }

    function getDatesDiffDays(): int
    {
        if(DateLib::isMysqlDate($this->date_collect) && DateLib::isMysqlDate($this->date_return)) {
            return (DateLib::diffInDaysMysqlDates($this->date_collect, $this->date_return) ?: 1); // minimum 1
        }
        return 1; // minimum 1
    }

    function detailsString(): string
    {
        return sprintf('%s | %s &rarr; %s',
                       $this->vehicle->detailsString(),
                       dateLocalized($this->date_collect),
                       dateLocalized($this->date_return)
        );
    }

    /**
     * Called from a booted static method when the record is created.
     * - Add this task to all rentals being collected from today onwards...
     *
     * @param Task $task
     * @return void
     */
    static function handleEventTaskCreated(Task &$task): void
    {
        try {
            $rentals = Rental::where('date_collect', '>=', Carbon::today()->toDateString())->get();
            $task->rentals()->saveMany($rentals);
        }
        catch(\Throwable $ex) {
            // avoid exceptions
            Log::critical('! Failed to add tasks to rentals.', $task->toArray());
            Log::critical($ex);
        }
    }

    /**
     * Called from a booted static method when the record is created.
     * - Add this task to all rentals being collected from today onwards...
     *
     * @param Task $task
     * @return void
     */
    static function handleEventTaskDeleted(Task &$task): void
    {
        try {
            $groupNum = $task->group_num;
            $rentals = Rental::select(['id'])->get();
            foreach($rentals as $rental) {
                RentalTask::hasCompletedAllTasks_clearCache($rental->id, $groupNum);
            }
        }
        catch(\Throwable $ex) {
            // avoid exceptions
            Log::critical('! Failed to RentalTask::hasCompletedAllTasks_clearCache on all rentals for task.', $task->toArray());
            Log::critical($ex);
        }
    }

    /**
     * Tasks attached to this record, for a specific Group
     * - Where: Active
     * - Sorted by: Ordering
     *
     * @param int   $groupNum
     * @param array $select e.g. ['id', 'rental_task.completed']
     * @param int   $active
     * @return Collection
     */
    public function getTaskGroup(int $groupNum, array $select = ['*'], int $active = 1): \Illuminate\Database\Eloquent\Collection
    {
        return $this->tasks()->select($select)
                    ->where('group_num', $groupNum)
                    ->where('active', $active)
                    ->orderBy('ordering')
                    ->get();
    }

}
