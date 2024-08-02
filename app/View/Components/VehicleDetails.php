<?php

namespace App\View\Components;

use App\Models\Vehicle;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VehicleDetails extends Component
{
    public int $vehicleId = 0;

    /**
     * Create a new component instance.
     */
    public function __construct(string $vehicleId = '0')
    {
        $this->vehicleId = intval($vehicleId);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if($this->vehicleId) {
            $record = Vehicle::findOrFail($this->vehicleId);
        }
        else {
            $record = new Vehicle();
        }
        $vehicleArray = $record->toArray();
        return view('components.vehicle-details', \compact('vehicleArray'));
    }
}
