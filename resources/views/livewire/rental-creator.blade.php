<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="py-4 px-4 border-b">

            <div class="flex items-center justify-between">
                <h2>{!! __t('Create', 'Rental') !!}</h2>
                @if(empty($rental_id))
                    {{-- Save Button --}}
                    <x-button.save class="mr-8" wire:click="save"/>
                @endif
            </div>

            {{-- Only show form if data not saved --}}
            @if(empty($rental_id))

                {{--------------------------}}
                {{--       Rental         --}}
                {{--------------------------}}
                <div class="px-4 py-4 flex flex-col gap-4">

                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-2 justify-between items-center">
                            <div class="flex-shrink-0 w-14 text-sm">Retira</div>
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental_date_collect !!}</span>
                                @endif
                                {{--

                                    Date picker 1

                                --}}
                                <div class="ptr"
                                     onclick="loadLCDatePickerNoMobile('#datepicker_RE_rental_date_collect', 'rental_date_collect', 'RentalCreator').open();"
                                >
                                    {{-- Formatted date for display only --}}
                                    <input type="text" class="ptr" readonly
                                           value="{!! DateLib::mysqlDateToFormat($rental_date_collect, 'D j F') !!}"/>
                                    <input id="datepicker_RE_rental_date_collect" type="text" class="hidden-datepicker-input"
                                           value="{!! $rental_date_collect !!}"/>
                                </div>
                            </div>

                            {{--       Time        --}}
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental_time_collect !!}</span>
                                @endif
                                <x-select-time-slot name="time_collect"
                                                    wire:model="rental_time_collect"
                                />
                            </div>
                        </div>
                        <div class="flex flex-row gap-2 justify-between items-center">
                            <div class="flex-shrink-0 w-14 text-sm">Retorno</div>
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental_date_return !!}</span>
                                @endif
                                {{--

                                    Date picker 2

                                --}}
                                <div class="ptr"
                                     onclick="loadLCDatePickerNoMobile('#datepicker_RE_rental_date_return', 'rental_date_return', 'RentalCreator').open();"
                                >
                                    {{-- Formatted date for display only --}}
                                    <input type="text" class="ptr" readonly
                                           value="{!! DateLib::mysqlDateToFormat($rental_date_return, 'D j F') !!}"/>
                                    <input id="datepicker_RE_rental_date_return" type="text" class="hidden-datepicker-input"
                                           value="{!! $rental_date_return !!}"/>
                                </div>
                            </div>
                            {{--       Time        --}}
                            <div class="flex-1">
                                @if($componentDebug)
                                    <span class="text-xxs text-blue-800">{!! $rental_time_return !!}</span>
                                @endif
                                <x-select-time-slot name="time_return"
                                                    selected="{!! StringLib::onlyTheseChars($rental_time_return, [0,1,3,4]) !!}"
                                                    wire:model="rental_time_return"
                                />
                            </div>
                        </div>
                    </div>
                    {{--Input Errors--}}
                    @if($errors->isNotEmpty())
                        <div>
                            <x-input-error for="rental_date_collect"/>
                            <x-input-error for="rental_date_return"/>
                            <x-input-error for="rental_time_collect"/>
                            <x-input-error for="rental_time_return"/>
                        </div>
                    @endif
                    {{-- $ Price per Day --}}
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="rental_price_day" class="text-xs">$ p/dia</label>
                            <input id="rental_price_day" type="text"
                                   class="sm:text-base px-2 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                                   wire:model.blur="rental_price_day"
                                   x-on:keyup="dataSaved=false"
                                   pattern="\d*"
                                   required
                            />
                        </div>
                        {{-- Days to Charge --}}
                        <div class="flex-1">
                            <div class="text-xs pt-1.5">Dias</div>
                            <div class="sm:text-base px-2 rounded-lg w-full py-2">
                                {!! nf0($rental_days_to_charge); !!}
                            </div>
                        </div>
                        {{-- Price Total --}}
                        <div class="flex-1">
                            <div class="text-xs pt-1.5">Total</div>
                            <div class="sm:text-base px-2 rounded-lg w-full py-2">
                                {!! nf0($rental_price_total); !!}
                            </div>
                        </div>
                    </div>
                    {{--Input Errors--}}
                    @if($errors->isNotEmpty())
                        <div>
                            <x-input-error for="rental_price_day"/>
                            <x-input-error for="rental_days_to_charge"/>
                        </div>
                    @endif
                    {{--Notes--}}
                    <div>
                            <textarea rows="2" placeholder="{!! __('Notes') !!}"
                                      wire:model="rental_notes"
                                      x-on:keyup="dataSaved=false"></textarea>
                    </div>

                    {{--------------------------}}
                    {{--       Client         --}}
                    {{--------------------------}}

                    <div class="mt-3">
                        <h3>{!! __('Client') !!}</h3>
                        <livewire:client-search-select />
                    </div>

{{--                    <div x-data="{selectClient: false}" class="mt-3">--}}
{{--                        --}}{{--       Client - Title         --}}
{{--                        <div class="flex justify-between items-center gap-2 mb-2">--}}
{{--                            @if(empty($rental_client_id))--}}
{{--                                <h3>Cliente Nuevo</h3>--}}
{{--                            @else--}}
{{--                                <h3>Cliente</h3>--}}
{{--                                @if(app()->isLocal())--}}
{{--                                    <span class="text-gray-200"> {!! $rental_client_id !!}</span>--}}
{{--                                @endif--}}
{{--                            @endif--}}
{{--                            --}}{{--       Buttons         --}}
{{--                            <div class="flex gap-2">--}}
{{--                                --}}{{--       Client - Unselect         --}}
{{--                                @if($rental_client_id)--}}
{{--                                    <button class="btn-icon-only"--}}
{{--                                            onclick="log('! Clicked car icon'); Livewire.dispatch('ClickedClientRentals', [{{ $rental_client_id }}]); openOffCanvasSimple('ClientRentals')">--}}
{{--                                        <x-vaadin-car/>--}}
{{--                                    </button>--}}
{{--                                    <button class="btn-icon-only"--}}
{{--                                            onclick="Livewire.dispatch('SelectedClient_RentalCreator', 0)">--}}
{{--                                        <x-heroicon-o-x-mark/>--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                                --}}{{--       Client - Search Button         --}}
{{--                                --}}{{--        toggle: selectClient          --}}
{{--                                <button class="h-8 w-8 border border-gray-300 rounded hover:border-gray-700 hover:shadow-lg"--}}
{{--                                        x-on:click="selectClient=!selectClient">--}}
{{--                                    <x-heroicon-o-magnifying-glass class="m-auto w-5 h-5 text-gray-800"/>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        --}}{{--       Client - Select         --}}
{{--                        <div x-show="selectClient">--}}
{{--                            <x-client-select x-on:change="selectClient=!selectClient" target-component="RentalCreator"/>--}}
{{--                        </div>--}}
{{--                        --}}{{--       Client - Edit Form         --}}
{{--                        <div class="form flex flex-col gap-2"--}}
{{--                             x-show="!selectClient">--}}
{{--                            --}}{{--Name--}}
{{--                            <x-input.text label="Nombre" placeholder="Nombre" wire:model="client_name"/>--}}
{{--                            <x-input-error name="client_name"/>--}}
{{--                            --}}{{--Address--}}
{{--                            <x-input.text label="Dirección" placeholder="Dirección" wire:model="client_address"/>--}}
{{--                            <x-input-error name="client_address"/>--}}
{{--                            <div class="flex items-center gap-2">--}}
{{--                                <div class="flex-1">--}}
{{--                                    --}}{{--Telephone--}}
{{--                                    <x-input.text label="Teléfono" placeholder="Teléfono"--}}
{{--                                                  x-on:keyup="dataSaved=false"--}}
{{--                                                  wire:model="client_phone_number"/>--}}
{{--                                </div>--}}
{{--                                <div class="flex-0">--}}
{{--                                    --}}{{--Rating--}}
{{--                                    <label>{!! __('Rating') !!}</label>--}}
{{--                                    <x-form.select-client-rating wire:model="client_rating"/>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <x-input-error name="client_phone_number"/>--}}
{{--                            </div>--}}
{{--                            --}}{{--Notes--}}
{{--                            <label for="">Notas</label>--}}
{{--                            <textarea rows="3" placeholder="Notas del alquiler"--}}
{{--                                      wire:model="client_notes"--}}
{{--                            ></textarea>--}}
{{--                            <x-input-error name="client_notes"/>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    {{--------------------------}}
                    {{--       Vehicle        --}}
                    {{--------------------------}}

                    @if($vehicle)
                        <div class="mt-3 mb-2">
                            <div class="mb-2">
                                <h3>{!! __('Vehicle') !!}</h3>
                            </div>
                            <div class="">
                                <div class="flex justify-between">
                                    <div>{{ $vehicle->vehicle_make }} &middot; {{ $vehicle->vehicle_model }}</div>
                                    <div></div>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ $vehicle->vehicle_plate }}</span>
                                    <span>${{ $vehicle->vehicle_price }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            @endif
        </div>

    </x-mod-data-status-modified-saved-message>

</div>
