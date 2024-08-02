<?php

namespace App\View\Components;

use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class VehicleSelect extends Component
{
    public Collection $vehicles;
    public string $targetComponent;

    /**
     * Create a new component instance.
     */
    public function __construct(string $targetComponent)
    {
        $this->targetComponent = $targetComponent;
        $this->vehicles = collect(
            Vehicle::orderBy('ordering')
                   ->get(['id', 'vehicle_make', 'vehicle_model', 'vehicle_plate']));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.vehicle-select');
    }
}
