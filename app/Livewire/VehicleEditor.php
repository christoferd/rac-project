<?php

namespace App\Livewire;

use App\Models\Vehicle;

use App\Livewire\Traits\DataModifiedSaved;
use Illuminate\Contracts\View\View;

/**
 * Emits: UpdatedVehicle
 */
class VehicleEditor extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved;

    protected string $objectName = 'Vehicle';

    public int $vehicle_id = 0;
    public string $vehicle_make = '';
    public string $vehicle_model = '';
    public string $vehicle_kms = '0';
    public int $vehicle_price = 0;
    public string $vehicle_plate = '';
    public string $notes = '';
    public int $active = 1;
    public int $ordering = 1;
    public string $created_at = '';
    public string $updated_at = '';

    protected $listeners = [
        'ClickedEditVehicle'          => 'handleEvent_ClickedEditVehicle',
        'VehicleEditorSave'           => 'validateAndSave',
        'DeleteVehicle_VehicleEditor' => 'handleEvent_DeleteVehicle',
        'DeletedVehicle'              => 'reset',
        // --
        'ClosedVehicleEditor'         => 'reset',
    ];

    public function mount(int $vehicleId = 0): void
    {
        $this->loadModelData($vehicleId);
    }

    protected function loadModelData(int $vehicle_id = 0): void
    {
        $this->resetDataModifiedSaved();
        // Model
        if($vehicle_id) {
            $vehicle = Vehicle::findOrFail($vehicle_id);
            $this->vehicle_id = $vehicle_id;
            $this->vehicle_make = $vehicle->vehicle_make;
            $this->vehicle_model = $vehicle->vehicle_model;
            $this->vehicle_kms = $vehicle->vehicle_kms;
            $this->vehicle_plate = $vehicle->vehicle_plate;
            $this->vehicle_price = $vehicle->vehicle_price;
            $this->notes = $vehicle->notes;
            $this->active = $vehicle->active;
            $this->ordering = $vehicle->ordering;

            // info for tooltip
            $this->created_at = $vehicle->created_at;
            $this->updated_at = $vehicle->updated_at;
        }
    }

    public function render(): View
    {
        if(userCan('edit vehicles')) {
            return view('livewire.vehicle-editor');
        }
        return view('app.unauthorized');
    }

    public function handleEvent_ClickedEditVehicle(int $id): void
    {
        // $this->alertDebug(__CLASS__." handleEvent_ClickedEditVehicle(int $id)");
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
        $vehicle = Vehicle::findOrFail($this->vehicle_id);
        $this->submitEditFormObjective($vehicle);

        // Status
        $this->setDataSaved();
    }

    public function handleEvent_DeleteVehicle(int $id = (-1)): void
    {
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        try {
            $vehicle = Vehicle::findOrFail($id);
            if(!$vehicle->allowDelete()) {
                $this->alertError(__('Unable to delete the record.').' '.$vehicle->message);
                return;
            }
            $vehicle->delete();
            $this->alertSuccess(__('Record Deleted'));
            $this->dispatch('DeletedVehicle');
        }
        catch(\Throwable $ex) {
            $this->handleException($ex, 'Unable to delete the record.');
        }
    }

}
