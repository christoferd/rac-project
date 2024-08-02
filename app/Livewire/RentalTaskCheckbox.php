<?php

namespace App\Livewire;

use App\Models\Rental;
use App\Models\RentalTask;
use App\Models\User;

class RentalTaskCheckbox extends ObjectiveComponent
{
    public int $rentalId = 0;
    public int $taskId = 0;
    public array $rentalTask = [];
    public bool $completed = false;
    public string $title = '';
    public string $userCompletedName = '';
    public string $dateTimeCompleted = '';

    public function mount(int $rentalId, int $taskId): void
    {
        $this->rentalId = $rentalId;
        $this->taskId = $taskId;
    }

    public function render()
    {
        $rental = Rental::findOrFail($this->rentalId);
        $rentalTask = $rental->getTask($this->taskId);

        $this->title = $rentalTask->title;
        $this->completed = boolval($rentalTask->pivot->completed);

        if($this->completed) {
            $this->userCompletedName = User::getUserName($rentalTask->pivot->user_completed);
            $this->dateTimeCompleted = dateLocalized($rentalTask->pivot->datetime_completed, 2);
        }

        return view('livewire.rental-task-checkbox');
    }

    public function checkboxClicked(int $taskId): void
    {
        try {
            RentalTask::toggleCompleted($this->rentalId, $taskId);
        }
        catch(\Throwable $ex) {
            $this->alertError($ex->getMessage());
        }
    }
}
