<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ClientImage
 *
 * @property int                             $id
 * @property int                             $client_id
 * @property string                          $filename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientImage whereUpdatedAt($value)
 * @property-read \App\Models\Client|null $client
 * @mixin \Eloquent
 */
class ClientImage extends Model
{
    // use HasFactory;
    protected $guarded = ['id'];

    function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    static function getFilenamesArray(int $client_id): array
    {
        if($client_id >= 1) {
            return ClientImage::select(['id', 'filename'])
                              ->where('client_id', $client_id)
                              ->pluck('filename')
                              ->toArray();
        }
        return [];
    }

    function detailsString(): string
    {
        return sprintf('%s ID#%d (%s)', __('Image'), $this->id, $this->filename);
    }

}
