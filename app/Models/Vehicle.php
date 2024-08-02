<?php

namespace App\Models;

use App\Library\StringLib;
use App\Models\Traits\ActiveFieldModelTrait;
use App\Traits\KeyTagsTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Vehicle
 *
 * @property int                                                                    $id
 * @property \Illuminate\Support\Carbon|null                                        $created_at
 * @property \Illuminate\Support\Carbon|null                                        $updated_at
 * @property \Illuminate\Support\Carbon|null                                        $deleted_at
 * @property string                                                                 $vehicle_make
 * @property string                                                                 $vehicle_model
 * @property int                                                                    $vehicle_kms
 * @property int                                                                    $vehicle_price
 * @property string                                                                 $vehicle_plate
 * @property string                                                                 $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rental> $rentals
 * @property-read int|null                                                          $rentals_count
 * @method static \Database\Factories\VehicleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehicleKms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehicleMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehicleModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehiclePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereVehiclePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle withoutTrashed()
 * @property string                                                                 $active
 * @property int                                                                    $ordering
 * @property mixed                                                                  $client_id
 * @mixin \Eloquent
 */
class Vehicle extends ObjectiveModel
{
    use HasFactory, SoftDeletes, KeyTagsTrait, ActiveFieldModelTrait;

    protected $fillable = [
        'vehicle_make',
        'vehicle_model',
        'vehicle_kms',
        'vehicle_plate',
        'vehicle_price',
        'notes',
        'active',
        'ordering',
    ];

    protected array $defaultValues = [
        'vehicle_make'  => '',
        'vehicle_model' => '',
        'vehicle_kms'   => '0',
        'vehicle_plate' => '',
        'vehicle_price' => '0',
        'notes'         => '',
        'active'        => '1',
        'ordering'      => '1',
    ];

    protected array $rulesCreate = [
        'vehicle_make'  => 'required|string|min:0|max:30',
        'vehicle_model' => 'string|min:0|max:30',
        'vehicle_kms'   => 'numeric|digits_between:1,10',
        'vehicle_plate' => 'string|min:0|max:10',
        'vehicle_price' => 'required|int|min:0',
        'notes'         => 'string|min:0|max:300',
        'active'        => 'required|int|in:0,1',
        'ordering'      => 'int|min:0',
    ];

    protected array $rulesEdit = [
        'vehicle_make'  => 'string|min:0|max:30',
        'vehicle_model' => 'string|min:0|max:30',
        'vehicle_kms'   => 'numeric|digits_between:1,10',
        'vehicle_plate' => 'string|min:0|max:10',
        'vehicle_price' => 'required|integer|min:0',
        'notes'         => 'string|min:0|max:300',
        'active'        => 'required|int|in:0,1',
        'ordering'      => 'required|int|min:0',
    ];

    public function __construct(array $attributes = [])
    {
        $this->keyTagsPrefix = __('vehículo').'.';

        parent::__construct($attributes ?: $this->defaultValues);
    }

    /**
     * Mutator: transform field on get/set
     */
    protected function ordering(): Attribute
    {
        return Attribute::make(
            get: fn($value) => intval($value),
            set: fn($value) => (intval($value)>0?$value:Vehicle::max('ordering')+1),
        );
    }

    function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Mutator - set: remove invalid characters, transform to uppercase.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function vehiclePlate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => \strtoupper($value),
            set: function($value) {
                $value = StringLib::replaceAllExceptAZ09($value);
                return \strtoupper($value);
            }
        );
    }

    /**
     * Mutator: transform `make` to capitalize, and remove quotes
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function vehicleMake(): Attribute
    {
        return Attribute::make(
            get: fn($value) => removeQuotes(\ucwords($value)),
            set: fn($value) => $value
        );
    }

    /**
     * Mutator: transform `make` to capitalize, and remove quotes
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function vehiclePrice(): Attribute
    {
        return Attribute::make(
            get: fn($value) => removeQuotes(\ucwords($value)),
            set: fn($value) => $value
        );
    }

    /**
     * Mutator: transform `model` to remove quotes
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function vehicleModel(): Attribute
    {
        return Attribute::make(
            get: fn($value) => removeQuotes(\ucwords($value)),
            set: fn($value) => $value
        );
    }

    /**
     * Mutator: transform field on get/set
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function clientId(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value),
            set: fn($value) => ($value ?: 0)
        );
    }

    function allowDelete(): bool
    {
        // Auth
        if(!\userCan('delete records')) {
            $this->message = __('You are not authorized to delete');
            return false;
        }

        // Check if attached to records
        if($this->rentals->isNotEmpty()) {
            $this->message = __('This record is attached to:').' '.__('Rental');
            return false;
        }

        return true;
    }

    function getKeyTags(): array
    {
        return $this->wrapOpenCloseTagsArray([
                                                 $this->keyTagsPrefix.'marca',
                                                 $this->keyTagsPrefix.'modelo',
                                                 $this->keyTagsPrefix.'precio_por_dia',
                                                 $this->keyTagsPrefix.'patente',
                                             ]);
    }

    function getKeyTagsData(): array
    {
        return \array_combine($this->getKeyTags(), [
            $this->vehicle_make,
            $this->vehicle_model,
            $this->vehicle_price,
            $this->vehicle_plate,
        ]);
    }

    function detailsString(): string
    {
        return sprintf('%s &middot; %s [%s]',
                       $this->vehicle_make, $this->vehicle_model, $this->vehicle_plate
        );
    }

}
