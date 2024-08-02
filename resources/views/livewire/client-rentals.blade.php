<div class="w-full h-auto">

    <div class="form px-4 py-4 flex flex-col gap-4">

        <div class="flex items-center justify-between">
            <h2>{!! __t('Client','Rentals') !!}</h2>
        </div>

        @if($client)
            <div class="flex items-center justify-between text-xl">
                <div>{{ $client->name }}</div>
                <div>${!! nf0($rentals->sum('price_total')) !!}</div>
            </div>
        @endif

        <div class="flex flex-col" wire:loading.remove>
            @if(!$rentals || $rentals->isEmpty())
                <div>{!! __('No Results Found.') !!}</div>
            @else
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="overflow-hidden">
                            {{--
                            All Screens
                            --}}
                            <div x-data class="flex-column gap-1">
                                @foreach($rentals as $rental)
                                    <div class="border-b pb-2 pt-2">
                                        <div class="flex-column">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>{{ $rental->vehicle->vehicle_make }} &middot; {{ $rental->vehicle->vehicle_model }}</div>
                                                <div class="flex-shrink">
                                                    <x-vehicle.vehicle-plate>{{ $rental->vehicle->vehicle_plate }}</x-vehicle.vehicle-plate>
                                                </div>
                                            </div>
                                            <div class="flex gap-2 justify-between items-center">
                                                <div class="flex-shrink-0">
                                                    <x-heroicon-o-chevron-right class="w-4"/>
                                                </div>
                                                <div class="flex-1">{!! \App\Library\DateLib::mysqlDateToHuman($rental->date_collect) !!}</div>
                                                <div class="flex-1">{!! $rental->time_collect !!}</div>
                                            </div>
                                            <div class="flex gap-2 justify-between items-center">
                                                <div class="flex-shrink-0">
                                                    <x-heroicon-o-chevron-left class="w-4"/>
                                                </div>
                                                <div class="flex-1">{!! \App\Library\DateLib::mysqlDateToHuman($rental->date_collect) !!}</div>
                                                <div class="flex-1">{!! $rental->time_collect !!}</div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <div>{!! __('Days') !!}: {{ $rental->days_to_charge }}</div>
                                                <div>${!! nf0($rental->price_total) !!}</div>
                                            </div>

                                            @if($rental->notes)
                                                <div class="pl-10 text-gray-500">
                                                    Notas: {{ $rental->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-live.livewire-loading-spinner/>

</div>
