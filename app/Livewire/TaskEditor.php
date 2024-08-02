<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Models\Task;
use App\Livewire\Traits\LivewireAlerts;

class TaskEditor extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved, LivewireAlerts;

    protected string $objectName = 'Task';

    // Model Data
    public int $task_id = 0;
    // default group num 12-Apr-2024 @todo Update to be dynamic
    public int $group_num = 1;
    public string $title = '';
    // public string $description = '';
    public int $active = 1;
    public string $created_at = '';
    public string $updated_at = '';

    protected $listeners = [
        'ClickedCreateTask'     => 'handleEvent_ClickedCreateTask',
        'ClickedEditTask'       => 'handleEvent_ClickedEditTask',
        'DeleteTask_TaskEditor' => 'handleEvent_DeleteTask',
        'DeletedTask'           => 'reset',
        // --
        'Closed_EditTask'       => 'reset',
    ];

    public function render()
    {
        if(userCan('edit tasks')) {
            return view('livewire.task-editor');
        }
        return view('app.unauthorized');
    }

    protected function loadModelData(int $task_id = 0): void
    {
        $this->resetDataModifiedSaved();
        $this->reset();

        // Model
        if($task_id) {
            $task = Task::findOrFail($task_id);
            $this->task_id = $task_id;
            $this->title = $task->title;
            $this->active = $task->active;
            // info for tooltip
            $this->created_at = $task->created_at;
            $this->updated_at = $task->updated_at;
        }
    }

    public function handleEvent_ClickedCreateTask(): void
    {
        $task = new Task();
        $task->group_num = $this->group_num;
        $task->save();
        $this->loadModelData($task->id);
        $this->dispatch('CreatedTask', $task->id);
    }

    public function handleEvent_ClickedEditTask(int $id): void
    {
        // $this->alertDebug(__CLASS__." handleEvent_ClickedEditTask(int $id)");
        $this->loadModelData($id);
    }

    /**
     * Called automatically by Livewire
     * Runs after any update to the Livewire component's data (Using wire:model, not directly inside PHP)
     * https://laravel-livewire.com/docs/2.x/lifecycle-hooks
     */
    public function updated(): void
    {
        $this->validateAndSave();
    }

    public function validateAndSave(): void
    {
        // Status
        $this->setDataModified();

        // Validate & Save & dispatch
        $task = Task::findOrFail($this->task_id);
        $this->submitEditFormObjective($task);

        // Status
        $this->setDataSaved();
    }

    public function handleEvent_DeleteTask(int $id): void
    {
        // $this->alertDebug("function handleEvent_DeleteTask(int $id )");
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        try {
            $task = Task::findOrFail($id);
            if(!$task->allowDelete()) {
                $this->alertError(__('Unable to delete the record.').' '.$task->message);
                return;
            }
            $task->delete();
            $this->alertSuccess(__('Record Deleted'));
            $this->dispatch('DeletedTask');
        }
        catch(\Throwable $ex) {
            $this->handleException($ex, 'Unable to delete the record.');
        }
    }

}
