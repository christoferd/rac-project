<?php

namespace App\View\Components;

use App\Models\Rental;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RentalTaskList extends Component
{
    public $rentalId = 0;
    public array $tasks = [];

    public function __construct(int $rentalId)
    {
        $this->rentalId = $rentalId;
    }

    public function render(): View|Closure|string
    {
        $rental = Rental::find($this->rentalId);
        $rentalTasks = $rental->getTasks();
        foreach ($rentalTasks as $rentalTask) {
            $this->tasks[$rentalTask->id] = [
                'id' => $rentalTask->id,
                'rental_id' => $rentalTask->pivot->rental_id,
            ];
        }

        return view('components.rental-task-list');
    }

}
