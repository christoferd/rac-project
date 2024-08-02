<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Traits\OrderingRecordsTrait;
use Illuminate\Support\Facades\Log;

class TasksTable extends ObjectiveComponent
{
    use OrderingRecordsTrait;

    public array $tasks = [];

    protected $listeners = [
        'CreatedTask' => 'loadData',
        'UpdatedTask' => 'loadData',
        'DeletedTask' => 'loadData',
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Active first
        $this->tasks = Task::orderBy('active', 'desc')
                           ->orderBy('ordering', 'asc')
                           ->get()
                           ->toArray();
    }

    public function render()
    {
        if(userCan('access tasks')) {
            return view('livewire.tasks-table');
        }
        return view('app.unauthorized');
    }

    /**
     * ! Up the list means the ordering value may be decreased.
     */
    public function orderingUp(int|string $id): void
    {
        try {
            $this->moveOrderingUp(Task::class, $id, 'ordering');
            $this->loadData();
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error: '.$ex->getMessage());
        }
    }

    /**
     * ! Down the list means the ordering value may be increased.
     */
    public function orderingDown(int $id): void
    {
        try {
            $this->moveOrderingDown(Task::class, $id, 'ordering');
            $this->loadData();
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error: '.$ex->getMessage());
        }
    }

}
