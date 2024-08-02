<div>
    @if(!empty($vehicleArray))
        <div class="flex justify-between">
            <div>{{ $vehicleArray['vehicle_make'] }} &middot; {{ $vehicleArray['vehicle_model'] }}</div>
            <div></div>
        </div>
        <div class="flex justify-between">
            <x-vehicle.vehicle-plate>{{ $vehicleArray['vehicle_plate'] }}</x-vehicle.vehicle-plate>
            <span>${{ $vehicleArray['vehicle_price'] }}</span>
        </div>
    @else
        <h3>$vehicleArray empty!</h3>
    @endif
</div>
