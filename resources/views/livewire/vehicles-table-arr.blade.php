<div>
    {{--
    Offline Message
    --}}
    @include('app.offline-message')

    <x-live.livewire-loading-spinner/>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <div class="border-b pb-1 text-sm">{!! count($vehicles) !!} {!! __('Vehicles') !!}</div>
                    @if(count($vehicles))
                        {{--
                        Small Screens
                        --}}
                        <div class="block qs:hidden">
                            <div x-data class="flex-column gap-1">
                                @foreach($vehicles as $vehicle)
                                    <div class="border-b pb-2 pt-2 {!! (!$vehicle['active']?'text-gray-500':'') !!}"
                                         onclick="openOffCanvas('VehicleEditor'); Livewire.dispatch('ClickedEditVehicle', [{!! $vehicle['id'] !!}]);"
                                    >
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="flex-grow {!! ($vehicle['active'] ?'font-semibold':'') !!}">{{ $vehicle['vehicle_make'] }} &middot; {{ $vehicle['vehicle_model'] }}</span>
                                            <div class="flex-shrink">
                                                <div class="flex items-center gap-1">
                                                    <div
                                                        {{--Prevent Up on first--}}
                                                        @if($loop->first)
                                                            class="disabled text-gray-300 cursor-not-allowed"
                                                        @else
                                                            class="ptr text-gray-700"
                                                        onclick="log('! call orderingUp ' + {!! $vehicle['id'] !!});"
                                                        wire:click.stop="orderingUp({!! $vehicle['id'] !!})"
                                                        @endif
                                                    >
                                                        <x-heroicon-o-chevron-up class="w-5 h-5"/>
                                                    </div>
                                                    <div
                                                        @if($loop->last)
                                                            class="disabled text-gray-300 cursor-not-allowed"
                                                        @else
                                                            class="ptr text-gray-700"
                                                        onclick="log('! call orderingDown ' + {!! $vehicle['id'] !!});"
                                                        wire:click.stop="orderingDown({!! $vehicle['id'] !!})"
                                                        @endif
                                                    >
                                                        <x-heroicon-o-chevron-down class="w-5 h-5"/>
                                                    </div>
                                                    <span><x-heroicon-o-check-circle
                                                            class="h-5 w-5 {!! ($vehicle['active'] ?'text-green-700':'text-red-500') !!}"/></span>
                                                </div>
                                            </div>
                                            {{--<span class="">{{ $vehicle['vehicle_make'] }} &middot; {{ $vehicle['vehicle_model'] }}</span>--}}
                                            {{--<span><x-heroicon-o-x-circle class="h-4 w-4 text-red-500"/></span>--}}
                                        </div>
                                        <div class="flex flex-row justify-between">
                                            <div class="w-20">{{ $vehicle['vehicle_plate'] }}</div>
                                            <div class="flex-grow text-right">{{ nf0($vehicle['vehicle_kms']) }}</div>
                                            <div class="w-20 text-right">${{ nf0($vehicle['vehicle_price']) }}</div>
                                        </div>
                                        @if($vehicle['notes'])
                                            <div class="pl-10 text-gray-500">
                                                Notas: {{ $vehicle['notes'] }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{--
                        Medium Screens +
                        --}}
                        <table class="hidden qs:block min-w-full">
                            <thead>
                            <tr>
                                <th scope="col" class="no-print"></th>
                                <th scope="col" class="">Marca</th>
                                <th scope="col" class="">Modelo</th>
                                <th scope="col" class="text-right">Kms</th>
                                <th scope="col" class="text-center">Matrícula</th>
                                <th scope="col" class="text-right">$p/dia</th>
                                <th scope="col" class="text-center">Notas</th>
                                <th scope="col" class="text-center">Orden</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicle)
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                    <td class="no-print">
                                        <div class="flex gap-2 items-center">
                                            <div
                                                {{--Prevent Up on first--}}
                                                @if($loop->first)
                                                    class="disabled text-gray-300 cursor-not-allowed"
                                                @else
                                                    class="ptr text-gray-700"
                                                onclick="log('! call orderingUp ' + {!! $vehicle['id'] !!});"
                                                wire:click="orderingUp({!! $vehicle['id'] !!})"
                                                @endif
                                            >
                                                <x-heroicon-o-chevron-up class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            </div>
                                            <div
                                                @if($loop->last)
                                                    class="disabled text-gray-300 cursor-not-allowed"
                                                @else
                                                    class="ptr text-gray-700"
                                                onclick="log('! call orderingDown ' + {!! $vehicle['id'] !!});"
                                                wire:click="orderingDown({!! $vehicle['id'] !!})"
                                                @endif
                                            >
                                                <x-heroicon-o-chevron-down class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            </div>
                                            <div class="ptr"
                                                 onclick="log('! dispatch ClickedEditVehicle'); Livewire.dispatch('ClickedEditVehicle', [{!! $vehicle['id'] !!}]); openOffCanvas('VehicleEditor');">
                                                <x-heroicon-o-pencil-square class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700"/>
                                            </div>
                                            <div>
                                                {{--Active--}}
                                                <x-preline.tooltip :tip="__($vehicle['active']?'Active':'Inactive')">
                                                    @if($vehicle['active'])
                                                        <x-heroicon-o-check-circle class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 relative top-0.5"/>
                                                    @else
                                                        <x-heroicon-o-x-circle class="w-5 h-5 sm:w-6 sm:h-6 text-red-500 relative top-[1px]"/>
                                                    @endif
                                                </x-preline.tooltip>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!}">{{ $vehicle['vehicle_make'] }}</td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!}">{{ $vehicle['vehicle_model'] }}</td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!} text-right">{{ nf0($vehicle['vehicle_kms']) }}</td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!}">
                                        <x-vehicle.vehicle-plate>{{ $vehicle['vehicle_plate'] }}</x-vehicle.vehicle-plate>
                                    </td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!} text-right">{{ nf0($vehicle['vehicle_price']) }}</td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!}"
                                        style="white-space: normal !important;">{{ $vehicle['notes'] }}</td>
                                    <td class="{!! (!$vehicle['active']?'text-gray-500':'') !!} text-center">{{ $vehicle['ordering'] }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
