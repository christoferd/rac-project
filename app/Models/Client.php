<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\KeyTagsTrait;
use App\Traits\SearchableModelTrait;
use Spatie\Image\Enums\Constraint;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Client
 *
 * @property int                                                                                       $id
 * @property \Illuminate\Support\Carbon|null                                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                                           $updated_at
 * @property \Illuminate\Support\Carbon|null                                                           $deleted_at
 * @property string                                                                                    $name
 * @property string                                                                                    $address
 * @property string                                                                                    $phone_number
 * @property string                                                                                    $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rental>                    $rentals
 * @property-read int|null                                                                             $rentals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vehicle>                   $vehicles
 * @property-read int|null                                                                             $vehicles_count
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client withoutTrashed()
 * @property int                                                                                       $rating
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClientImage>               $images
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @mixin \Eloquent
 */
class Client extends ObjectiveModel implements HasMedia
{
    use HasFactory,
        SoftDeletes,
        SearchableModelTrait,
        KeyTagsTrait,
        InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'notes',
        'rating',
    ];

    protected array $defaultValues = [
        'name'         => '',
        'address'      => '',
        'phone_number' => '',
        'notes'        => '',
        'rating'       => -1,
    ];

    protected array $rulesCreate = [
        'name'         => 'required|string|max:80',
        'address'      => 'string|max:100',
        'phone_number' => 'string|max:20',
        'notes'        => 'string|max:300',
        'rating'       => 'integer|min:-1|max:10',
    ];

    protected array $rulesEdit = [
        'name'         => 'required|string|max:80',
        'address'      => 'string|max:100',
        'phone_number' => 'string|max:20',
        'notes'        => 'string|max:300',
        'rating'       => 'integer|min:-1|max:10',
    ];

    protected static array $labels = [
        'name'         => 'Name',
        'address'      => 'Address',
        'phone_number' => 'Phone',
        'notes'        => 'Notes',
        'rating'       => 'Rating',
    ];

    static array $selectOptions = [
        'rating' => [
            -1 => '?',
            0  => '0',
            1  => '1',
            2  => '2',
            3  => '3',
            4  => '4',
            5  => '5',
            6  => '6',
            7  => '7',
            8  => '8',
            9  => '9',
            10 => '10',
        ]
    ];

    // ---
    // Required by SearchableModelTrait
    static array $searchFields = [
        'name',
        'address',
        'phone_number',
        'notes',
    ];
    static string $searchOrderField = 'name';
    static string $searchOrderDirection = 'asc';

    // ---

    public function __construct(array $attributes = [])
    {
        $this->keyTagsPrefix = 'cliente.';

        parent::__construct($attributes);
    }

    function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    function images(): HasMany
    {
        return $this->hasMany(ClientImage::class);
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

    public function getRulesEdit($specificField = ''): array
    {
        return $this->rulesCreate;
    }

    function getKeyTags(): array
    {
        return $this->wrapOpenCloseTagsArray([
                                                 $this->keyTagsPrefix.'nombre',
                                                 $this->keyTagsPrefix.'teléfono',
                                                 $this->keyTagsPrefix.'dirección',
                                             ]);
    }

    function getKeyTagsData(): array
    {
        return \array_combine($this->getKeyTags(), [
            $this->name,
            $this->phone_number,
            $this->address,
        ]);
    }

    function detailsString(): string
    {
        return sprintf('%s, %s, %s',
                       $this->name, $this->address, $this->phone_number
        );
    }

    // https://spatie.be/docs/laravel-medialibrary/v8/converting-images/defining-conversions
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb_160')
             ->width(160, [Constraint::DoNotUpsize, Constraint::PreserveAspectRatio])
             ->height(160, [Constraint::DoNotUpsize, Constraint::PreserveAspectRatio])
             ->sharpen(10)
             ->format('webp');

        $this->addMediaConversion('large_1600')
             ->width(1600, [Constraint::DoNotUpsize, Constraint::PreserveAspectRatio])
             ->height(1600, [Constraint::DoNotUpsize, Constraint::PreserveAspectRatio])
            // ->sharpen(10)
             ->format('webp');
    }

}
