<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <h1>


        Not Used // Chris D. 11-Apr-2024


    </h1>

    <x-mod-data-status-modified-saved-message>

        <div class="py-4 px-4 border-b">

            <div class="flex items-center gap-4">
                <h2>{!! __('Rental') !!}</h2>
                @if(!is_null($rental) && $rental->id)
                    <x-record-info-icon-tooltip :record="$rental"/>
                @endif
            </div>

            @if(!is_null($rental))
                {{--------------------------}}
                {{--       Rental         --}}
                {{--------------------------}}
                <div class="px-4 py-4 flex flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 justify-between items-center">
                            <div class="flex-shrink-0 text-xs w-14">Retira</div>
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental->date_collect !!}</span>
                                @endif
                                {{--

                                    Date picker 1

                                --}}
                                <div class="ptr"
                                     onclick="loadLCDatePickerNoMobile('#datepicker_rental_date_collect', 'rental_date_collect', 'RentalEditor').open();"
                                >
                                    {{-- Formatted date for display only --}}
                                    <input type="text" class="ptr" readonly
                                           value="{!! DateLib::mysqlDateToFormat($rental->date_collect, 'D j F') !!}"/>
                                    <input id="datepicker_rental_date_collect" type="text" class="hidden-datepicker-input"
                                           value="{!! $rental->date_collect !!}"/>
                                </div>
                            </div>

                            {{--       Time        --}}
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental->time_collect !!}</span>
                                @endif
                                <x-select-time-slot name="time_collect"
                                                    wire:model.live="rental.time_collect"
                                />
                            </div>
                        </div>
                        <div class="flex flex-row gap-2 justify-between items-center">
                            <div class="flex-shrink-0 text-xs w-14">Retorno</div>
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental->date_return !!}</span>
                                @endif
                                {{--

                                    Date picker 2

                                --}}
                                <div class="ptr"
                                     onclick="loadLCDatePickerNoMobile('#datepicker_rental_date_return', 'rental_date_return', 'RentalEditor').open();"
                                >
                                    {{-- Formatted date for display only --}}
                                    <input type="text" class="ptr" readonly
                                           value="{!! DateLib::mysqlDateToFormat($rental->date_return, 'D j F') !!}"/>
                                    <input id="datepicker_rental_date_return" type="text" class="hidden-datepicker-input"
                                           value="{!! $rental->date_return !!}"/>
                                </div>
                            </div>
                            {{--       Time        --}}
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental->time_return !!}</span>
                                @endif
                                <x-select-time-slot name="time_return"
                                                    selected="{!! StringLib::onlyTheseChars($rental->time_return, [0,1,3,4]) !!}"
                                                    wire:model.live="rental.time_return"
                                />
                            </div>
                        </div>
                        {{--Input Errors--}}
                        @if($errors->isNotEmpty())
                            <div>
                                <x-input-error for="rental.date_collect"/>
                                <x-input-error for="rental.date_return"/>
                                <x-input-error for="rental.time_collect"/>
                                <x-input-error for="rental.time_return"/>
                            </div>
                        @endif
                        {{-- $ Price per Day --}}
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label for="rental_price_day" class="text-xs">$ p/dia</label>
                                <input id="rental_price_day" type="text"
                                       class="sm:text-base px-2 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                       wire:model.blur="rental.price_day"
                                       x-on:keyup="dataSaved=false"
                                       placeholder=""
                                       pattern="\d*"
                                       required
                                />
                                <x-input-error for="rental.price_day"/>
                            </div>
                            {{-- Days to Charge --}}
                            <div class="flex-1">
                                <div class="text-xs pt-1.5">Dias</div>
                                <div class="sm:text-base px-2 rounded-lg w-full py-2">
                                    {!! nf0($rental->days_to_charge); !!}
                                </div>
                            </div>
                            {{-- Price Total --}}
                            <div class="flex-1">
                                <div class="text-xs pt-1.5">Total</div>
                                <div class="sm:text-base px-2 rounded-lg w-full py-2">
                                    {!! nf0($rental->price_total); !!}
                                </div>
                            </div>
                        </div>
                        {{--Input Errors--}}
                        @if($errors->isNotEmpty())
                            <div>
                                <x-input-error for="rental.price_day"/>
                                <x-input-error for="rental.days_to_charge"/>
                            </div>
                        @endif
                        {{--Notes--}}
                        <div>
                            <textarea rows="2" placeholder="{!! __('Notes') !!}"
                                      wire:model.blur="rental.notes"
                                      x-on:keyup="dataSaved=false"></textarea>
                        </div>

                        {{--------------------------}}
                        {{--       Client         --}}
                        {{--------------------------}}

                        <div x-data="{selectClient: false}" class="mt-3">
                            {{--       Client - Title         --}}
                            <div class="flex justify-between items-center gap-2 mb-2">
                                <h3>{!! __('Client') !!}
                                    @if(app()->isLocal())
                                        <span class="text-gray-200">{!! $client->id??0 !!}</span>
                                    @endif
                                </h3>
                                @if(!is_null($client) && $client->id)
                                    <x-record-info-icon-tooltip :record="$client"/>
                                @endif
                                {{--       Buttons         --}}
                                {{--                                x client action buttons --}}
                            </div>
                            {{--       Client - Select         --}}
                            <div x-show="selectClient">
                                <x-client-select x-on:change="dataSaved=false; selectClient=!selectClient" target-component="RentalEditor"/>
                            </div>
                            {{--       Client - Edit Form         --}}
                            <div class="form flex flex-col gap-2"
                                 x-show="!selectClient">
                                {{--Name--}}
                                <x-input.text label="Nombre" placeholder="Nombre"
                                              x-on:keyup="dataSaved=false"
                                              wire:model.blur="client.name"/>
                                <x-input-error name="client.name"/>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        {{--Telephone--}}
                                        <x-input.text label="Teléfono" placeholder="Teléfono"
                                                      x-on:keyup="dataSaved=false"
                                                      wire:model.blur="client.phone_number"/>
                                    </div>
                                    {{--Rating--}}
                                    <div class="flex-0">
                                        <label>{!! __('Rating') !!}</label>
                                        <x-form.select-client-rating wire:model.blur="client.rating"/>
                                    </div>
                                </div>
                                <div>
                                    <x-input-error name="client.phone_number"/>
                                </div>
                                {{--Address--}}
                                <x-input.text label="Dirección" placeholder="Dirección"
                                              x-on:keyup="dataSaved=false"
                                              wire:model.blur="client.address"/>
                                <x-input-error name="client.address"/>
                                {{--Notes--}}
                                <label for="">Notas</label>
                                <textarea rows="3" placeholder="{!! __('Notes') !!}"
                                          x-on:keyup="dataSaved=false"
                                          wire:model.blur="client.notes"
                                ></textarea>
                                <x-input-error name="client.notes"/>
                                {{--Photos--}}
                                <div class="border rounded p-3">
                                    {{--                                    <x-client-images-display :client-id="$rental->client_id" num-columns="4">--}}
                                    {{--                                        <div x-data class="border-none text-gray-700 w-1/4 flex items-center">--}}
                                    {{--                                            <button class="btn-icon-only"--}}
                                    {{--                                                    value="{!! $rental->client_id !!}"--}}
                                    {{--                                                    x-on:click="$nextTick(() => { Livewire.dispatch('ManageImagesForClient', [ {{ $rental->client_id  }} ]); openMyModal('ClientImagesManager'); });">--}}
                                    {{--                                                <x-heroicon-o-pencil-square class="w-4 h-4 text-gray-700"/>--}}
                                    {{--                                            </button>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </x-client-images-display>--}}
                                </div>
                            </div>
                        </div>

                        {{--------------------------}}
                        {{--       Vehicle        --}}
                        {{--------------------------}}
                        <div x-data="{selectVehicle: false}">
                            <div class="mt-3 flex justify-between items-center gap-2 mb-2">
                                <div class="mb-2">
                                    <h3>{!! __('Vehicle') !!}</h3>
                                </div>
                                {{--       Buttons         --}}
                                <div class="flex gap-2">
                                    {{--       Vehicle - Search Button         --}}
                                    {{--        toggle: selectVehicle          --}}
                                    <button class="h-8 w-8 border border-gray-300 rounded hover:border-gray-700 hover:shadow-lg"
                                            x-on:click="selectVehicle=!selectVehicle">
                                        <x-heroicon-o-magnifying-glass class="m-auto w-5 h-5 text-gray-800"/>
                                    </button>
                                </div>
                            </div>
                            {{--       Vehicle - Select         --}}
                            <div x-show="selectVehicle">
                                <x-vehicle-select x-on:change="selectVehicle=!selectVehicle" target-component="RentalEditor"/>
                            </div>
                            <div x-show="!selectVehicle">
                                <div class="flex justify-between">
                                    <div>{{ $vehicle->vehicle_make }} &middot; {{ $vehicle->vehicle_model }}</div>
                                    <div></div>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ $vehicle->vehicle_plate }}</span>
                                    <span>${{ $vehicle->vehicle_price }}</span>
                                </div>
                            </div>

                            {{--------------------------}}
                            {{--       Delete         --}}
                            {{--------------------------}}
                            @if(\userCan('delete records'))
                                <button class="mt-2 py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                                        onclick="openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Rental') !!}?','DeleteRental', {!! $rental->id??0 !!})">
                                    <x-heroicon-o-trash class="w-5 h-5"/>
                                    <span class="text-rose-700">{!! __t('Delete', 'Rental') !!}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </x-mod-data-status-modified-saved-message>

</div>
