<?php

namespace App\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait TasksModelTrait
{
    /**
     * ! withTrashed
     * ! findOrFail
     *
     * Example: $rental->getTask(1);
     * array [
     *       "id" => 1
     *       "group_num" => 1
     *       "title" => "Video del vehículo."
     *       "description" => ""
     *       "ordering" => 1
     *       "enabled" => 1
     *       "created_at" => "2024-04-08T01:40:34.000000Z"
     *       "updated_at" => "2024-04-08T01:40:34.000000Z"
     *       "deleted_at" => null
     *       "pivot" => array [
     *           "rental_id" => 28
     *           "task_id" => 1
     *           "completed" => 0
     *           "user_completed" => 0
     *           "datetime_completed" => null
     *  ]]
     *
     * @param int $taskId Must be an id from the `tasks` table
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null Including pivot data.
     * @throws Exception findOrFail $taskId
     */
    function getTask(int $taskId): Collection|Model|null
    {
        $res = $this->tasks()->where('id', $taskId)->get();
        if($res) {
            return $res->first();
        }
        return null;
    }

    function getTasks(): Collection|null
    {
        return $this->tasks()->where('active', 1)->orderBy('ordering')->get();
    }

}
