<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;

class VehicleCreator extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved;

    protected string $objectName = 'Vehicle';

    public string $vehicle_make = '';
    public string $vehicle_model = '';
    public string $vehicle_kms = '0';
    public int $vehicle_price = 0;
    public string $vehicle_plate = '';
    public string $notes = '';
    // If left as 0, Vehicle class will automatically add it to the end when setting attribute
    public int $ordering = 0;
    public int $active = 1;

    protected $listeners = [
        'ClickedCreateVehicle' => 'handleEvent_ClickedCreateVehicle',
        // --
        'Closed_CreateVehicle' => 'reset',
    ];

    function getRulesCreate()
    {
        return [
            'vehicle_make'  => 'required|string|min:0|max:30',
            'vehicle_model' => 'string|min:0|max:30',
            'vehicle_kms'   => 'numeric|digits_between:1,10',
            'vehicle_plate' => 'string|min:0|max:10',
            'vehicle_price' => 'required|integer|min:0',
            'notes'         => 'string|min:0|max:300',
            'active'        => 'required|int|in:0,1',
        ];
    }

    public function handleEvent_ClickedCreateVehicle(): void
    {
        $this->resetDataModifiedSaved();
        $this->reset();
    }

    public function render(): View
    {
        if(userCan('edit vehicles')) {
            return view('livewire.vehicle-creator');
        }
        return view('app.unauthorized');
    }

    public function save(): void
    {
        // Status
        $this->setDataModified();

        // Save
        $vehicle = new Vehicle();
        $this->submitCreateFormObjective($vehicle);

        // Status
        $this->setDataSaved();
    }

}
