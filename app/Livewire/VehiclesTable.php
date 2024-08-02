<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\OrderingRecordsTrait;
use Illuminate\Support\Facades\Log;

class VehiclesTable extends ObjectiveComponent
{
    use OrderingRecordsTrait;

    public array $vehicles = [];

    protected $listeners = [
        'CreatedVehicle' => 'loadData',
        'UpdatedVehicle' => 'loadData',
        'DeletedVehicle' => 'loadData',
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Active first
        $this->vehicles = Vehicle::orderBy('ordering', 'asc')
                                 ->get()
                                 ->toArray();
    }

    public function render(): View
    {
        if(userCan('access vehicles')) {
            return view('livewire.vehicles-table-arr');
        }
        return view('app.unauthorized');
    }

    /**
     * ! Up the list means the ordering value may be decreased.
     */
    public function orderingUp(int|string $id): void
    {
        try {
            $this->moveOrderingUp(Vehicle::class, $id, 'ordering');
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
            $this->moveOrderingDown(Vehicle::class, $id, 'ordering');
            $this->loadData();
        }
        catch(\Throwable $ex) {
            Log::error($ex);
            $this->alertError('Error: '.$ex->getMessage());
        }
    }

}
