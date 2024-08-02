<div>
    <select
        onchange="console.log('VehicleSelect option changed: ' + this.value); Livewire.dispatch('SelectedVehicle_{!! $targetComponent !!}', {id: this.value});"
        {!! $attributes !!}
    >
        <option value="0">Vehículos...</option>
        @foreach($vehicles as $vehicle)
            <option value="{!! $vehicle->id !!}">
                {{ $vehicle->vehicle_make }} &middot; {{ $vehicle->vehicle_model }} &middot; {{ $vehicle->vehicle_plate }}
            </option>
        @endforeach
    </select>
</div>
