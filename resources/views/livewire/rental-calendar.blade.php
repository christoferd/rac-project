<div style="overflow-x: scroll">
    {{--
    Offline Message
    --}}
    @include('app.offline-message')

    <div wire:poll.30s="refreshDataIfRequired" class="h-0"></div>

    <div class="mx-auto" style="min-width: 1140px; overflow-x: auto !important;">
        <div class="wrapper bg-white rounded shadow w-full">
            <div class="calendar-week text-xs text-gray-900 border-gray-400">
                {{--------------------------}}
                {{--      Top Header      --}}
                {{--------------------------}}
                <div class="grid-row-top flex border border-gray-400">
                    <div class="grid-cell-th grid-cell-col1 w-[14%] qs:w-[14%] p-1 border-r border-gray-400">
                        {{--Nav Buttons--}}
                        <div class="nav-buttons header flex items-center justify-between buttons">
                            {{--Previous--}}
                            <button class="btn"
                                    onclick="Livewire.dispatch('CalendarPreviousPage');"
                            >
                                <x-heroicon-o-chevron-left class="w-3 h-3" style="stroke-width: 3 !important"/>
                            </button>
                            {{--Loading spinner--}}
                            <div>
                                <x-live.livewire-loading-spinner css-display="block relative" css-size="h-6 w-6"
                                                                 css-position="top-[-12px] left-[-8px]"
                                                                 css-spinner="!text-gray-200"/>
                            </div>
                            {{--Next--}}
                            <button class="btn"
                                    onclick="Livewire.dispatch('CalendarNextPage');"
                            >
                                <x-heroicon-o-chevron-right class="w-3 h-3" style="stroke-width: 3 !important"/>
                            </button>
                        </div>
                    </div>
                    {{--Days Dates--}}
                    @foreach($dayTexts as $i => $t)
                        <div class="grid-cell-th flex-1 pl-2 pt-1 border-r border-inherit {!! ($dayTextsTodayIndex===$i?'bg-gray-200':'') !!}">
                            {!! $t !!}<br>
                            {!! $dateTexts[$i] !!}
                        </div>
                    @endforeach
                </div>
                {{--------------------------}}
                {{--       Vehicles       --}}
                {{--------------------------}}
                @foreach($vehicleRentals as $vehicleId => $vehicleRentalRows)
                    <div class="grid-row flex border-b border-l border-r border-gray-400 hover:border-l-gray-700 hover:bg-gray-200 hover:font-medium">
                        <div class="w-[14%] qs:w-[14%] max-h-8 px-1 border-r border-gray-400 ptr"
                             wire:click.stop="$dispatch('ClickedEditVehicle', [{!! $vehicleId !!}])"
                             onclick="openOffCanvas('VehicleEditor')"
                        >
                            <div class="whitespace-nowrap overflow-hidden overflow-ellipsis">
                                {{ $vehicles[$vehicleId]['vehicle_make'] }} &middot; {{ $vehicles[$vehicleId]['vehicle_model'] }}
                            </div>
                            <div class="flex items-center justify-between">
                                <div>{!! nf0($vehicles[$vehicleId]['vehicle_price']) !!}</div>
                                <div>{{ $vehicles[$vehicleId]['vehicle_plate'] }}</div>
                            </div>
                        </div>
                        <div class="flex-1 relative overflow-hidden">
                            {{--------------------------}}
                            {{--       Rentals        --}}
                            {{--------------------------}}
                            @foreach($vehicleRentalRows as $vr)
                                {{-- class pl-2 pr-0: Reason is to show that the text is too long --}}
                                <button
                                    class="absolute h-8 rounded pl-2 pr-0 pt-0.5 bg-{!! $vr['colour'] !!}-200 text-gray-900 font-medium opacity-90 hover:opacity-100 ptr"
                                    style="left: {!! $vr['percent_start'] !!}%; width: {!! $vr['percent_total'] !!}%;"
                                    wire:click.stop="$dispatch('ClickedEditRental', [{!! $vr['rental_id'] !!}]);"
                                    onclick="openOffCanvas('RentalCreateEdit')"
                                >
                                    @if($vr['start_diff_days']<0)
                                        {{--
                                            Starts before the calendar view
                                        --}}
                                        <div class="absolute right-1 top-[17px]" style="pointer-events: none;">
                                            <div class="time text-gray-700 text-xxs" style="pointer-events: none;">
                                                <span style="pointer-events: none;">{!! $vr['end_time'] !!}</span>
                                            </div>
                                        </div>
                                    @else
                                        {{--
                                            Starts within calendar view
                                        --}}
                                        <div class="flex items-center overflow-hidden whitespace-nowrap"
                                             {{-- Click through div to underlying elements
                                              https://stackoverflow.com/questions/3680429/click-through-div-to-underlying-elements --}}
                                             style="pointer-events: none;"
                                        >
                                            <span style="pointer-events: none;">{!! $vr['info'] !!}</span>
                                        </div>
                                        <div class="flex justify-between text-gray-700 text-xxs leading-none"
                                             style="pointer-events: none;"
                                        >
                                            <div class="time" style="pointer-events: none;">{!! $vr['start_time'] !!}</div>
                                            <div class="time pr-2" style="pointer-events: none;">{!! $vr['end_time'] !!}</div>
                                        </div>
                                    @endif
                                </button>

                                @if($vr['start_diff_days']<0)
                                    {{--
                                        Starts before the calendar view
                                    --}}
                                    <div class="absolute left-1 top-1" style="pointer-events: none;">
                                        <div class="flex items-center overflow-hidden whitespace-nowrap" style="pointer-events: none;">
                                            <span style="pointer-events: none;"><x-heroicon-o-chevron-double-left class="h-3 w-3 font-bold stroke-2"/></span>
                                            <span style="pointer-events: none;">{!! $vr['info'] !!}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            {{--------------------------}}
                            {{--       Background     --}}
                            {{--       Grid Cells     --}}
                            {{--------------------------}}
                            <div class="flex" style="width: 100%">
                                {{--cell structure, shows grid lines below events--}}
                                @for($i=0; $i<$qtyDays; $i++)
                                    <div class="grid-cell h-8 flex-1 border-r border-gray-400 hover:bg-gray-300"
                                         wire:click="clickedCalendarSpace({{$vehicleId}}, {!! $i !!})"
                                         onclick="openOffCanvas('RentalCreateEdit')"
                                    ></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
