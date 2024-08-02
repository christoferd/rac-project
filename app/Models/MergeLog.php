<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MergeLog
 *
 * @property int $id
 * @property mixed $log_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MergeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MergeLog query()
 * @mixin \Eloquent
 */
class MergeLog extends Model
{
    protected $table = 'merge_logs';
    const TABLE_NAME = 'merge_logs';

    static function add(array $data)
    {
        $data['user_id'] = auth()->id();
        $json = \json_encode($data);
        $record = new MergeLog();
        $record->log_data = $json;
        $record->save();
    }

}
