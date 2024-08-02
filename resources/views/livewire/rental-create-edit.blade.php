<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="py-4 px-4 border-b">

            <div class="flex items-center justify-between">

                @if(empty($rental_id))
                    <h2>{!! __t('Create', 'Rental') !!}</h2>
                    {{-- Save Button --}}
                    <x-button.save class="mr-8" wire:click="save"/>
                @else
                    <h2>{!! __t('Edit', 'Rental') !!}</h2>
                    <x-button.whatsapp-link-icon size="4" class="btn-icon-only !mr-8" client-id="{!! ($client_id??0) !!}"
                                                 vehicle-id="{!! ($vehicle_id??0) !!}" rental-id="{!! ($rental_id??0) !!}"/>
                @endif

            </div>

            {{--------------------------}}
            {{--       Rental         --}}
            {{--------------------------}}
            <div class="px-4 py-4 flex flex-col gap-4">

                {{--// Chris D. 9-Feb-2024 - added this to prevent cloning records when saving updates. !it worked!--}}
                <input type="hidden" name="rental_id" wire:model="rental_id"/>

                <div class="flex flex-col gap-2">
                    <div class="flex flex-row gap-2 justify-between items-center">
                        <div class="flex-shrink-0 w-14 text-sm">Retira</div>
                        <div class="flex-1">
                            @if($componentDebug)
                                <span class="text-xxs text-blue-800">{!! $date_collect !!}</span>
                            @endif
                            {{--

                                Date picker 1

                            --}}
                            <div class="ptr"
                                 onclick="loadLCDatePickerNoMobile('#datepicker_RE_date_collect', 'date_collect', 'RentalCreateEdit').open();"
                            >
                                {{-- Formatted date for display only --}}
                                <input type="text" class="ptr" readonly
                                       value="{!! DateLib::mysqlDateToFormat($date_collect, 'D j F') !!}"/>
                                <input id="datepicker_RE_date_collect" type="text" class="hidden-datepicker-input"
                                       value="{!! $date_collect !!}"/>
                            </div>
                        </div>

                        {{--       Time        --}}
                        <div class="flex-1">
                            @if($componentDebug)
                                <span class="text-xxs text-blue-800">{!! $time_collect !!}</span>
                            @endif
                            <x-select-time-slot name="time_collect"
                                                wire:model.live="time_collect"
                            />
                        </div>
                    </div>
                    <div class="flex flex-row gap-2 justify-between items-center">
                        <div class="flex-shrink-0 w-14 text-sm">Retorno</div>
                        <div class="flex-1">
                            @if($componentDebug)
                                <span class="text-xxs text-blue-800">{!! $date_return !!}</span>
                            @endif
                            {{--

                                Date picker 2

                            --}}
                            <div class="ptr"
                                 onclick="loadLCDatePickerNoMobile('#datepicker_RE_date_return', 'date_return', 'RentalCreateEdit').open();"
                            >
                                {{-- Formatted date for display only --}}
                                <input type="text" class="ptr" readonly
                                       value="{!! DateLib::mysqlDateToFormat($date_return, 'D j F') !!}"/>
                                <input id="datepicker_RE_date_return" type="text" class="hidden-datepicker-input"
                                       value="{!! $date_return !!}"/>
                            </div>
                        </div>
                        {{--       Time        --}}
                        <div class="flex-1">
                            @if($componentDebug)
                                <span class="text-xxs text-blue-800">{!! $time_return !!}</span>
                            @endif
                            <x-select-time-slot name="time_return"
                                                wire:model.live="time_return"
                            />
                        </div>
                    </div>
                </div>
                {{--Input Errors--}}
                @if($errors->isNotEmpty())
                    <div>
                        <x-input-error for="date_collect"/>
                        <x-input-error for="date_return"/>
                        <x-input-error for="time_collect"/>
                        <x-input-error for="time_return"/>
                    </div>
                @endif
                {{-- $ Price per Day --}}
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label for="price_day" class="text-xs">$ p/dia</label>
                        <input id="price_day" type="text"
                               class="sm:text-base px-2 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400"
                               wire:model.live.debounce.1000ms="price_day"
                               x-on:keyup="dataSaved=false"
                               pattern="\d*"
                               required
                        />
                    </div>
                    {{-- Days to Charge --}}
                    <div class="flex-1">
                        <div class="text-xs pt-1.5">Dias</div>
                        <div class="sm:text-base px-2 rounded-lg w-full py-2">
                            {!! nf0($days_to_charge); !!}
                        </div>
                    </div>
                    {{-- Price Total --}}
                    <div class="flex-1">
                        <div class="text-xs pt-1.5">Total</div>
                        <div class="sm:text-base px-2 rounded-lg w-full py-2">
                            {!! nf0($price_total); !!}
                        </div>
                    </div>
                </div>
                {{--Input Errors--}}
                @if($errors->isNotEmpty())
                    <div>
                        <x-input-error for="price_day"/>
                        <x-input-error for="days_to_charge"/>
                    </div>
                @endif
                {{--Notes--}}
                <div>
                            <textarea rows="2" placeholder="{!! __('Notes') !!}"
                                      wire:model.live.debounce.1000ms="notes"
                                      x-on:keyup="dataSaved=false"></textarea>
                </div>

                {{--------------------------}}
                {{--       Client         --}}
                {{--------------------------}}

                <div class="mt-3">
                    <h3>{!! __('Client') !!} <span class="text-gray-200">{!! !isProduction()?$client_id:'' !!}</span></h3>
                    <x-input-error for="client_id"/>
                    {{--Client Search--}}
                    @if(empty($client_id))
                        <livewire:client-search-select/>
                    @endif
                    {{--Client Details--}}
                    <div class="mt-2">
                        @if($client_id)
                            <x-client.client-details :client-array="$clientDetails??[]"/>
                            {{-- Photos (Files) --}}
                            <div class="relative border rounded mt-2">
                                <livewire:model-files-manager :model-class="\App\Models\Client::class" :model-id="$client_id"
                                                              media-collection="files" allow-upload="1"/>
                            </div>
                        @endif
                    </div>
                    {{--Button + Add Client--}}
                    @if(empty($client_id))
                        <div class="mt-2">
                            <x-secondary-button class="gap-2 !py-1"
                                                onclick="log('! dispatch ClickedCreateClient'); Livewire.dispatch('ClickedCreateClient'); openOffCanvas('ClientCreator');">
                                <x-heroicon-m-plus class="h-3 w-3"/>
                                <span>{!! __t('New', 'Client') !!}</span>
                            </x-secondary-button>
                        </div>
                    @endif
                </div>

                {{--------------------------}}
                {{--       Vehicle        --}}
                {{--------------------------}}
                <div class="mt-3 mb-2 flex flex-col">
                    <div class="mb-2 flex items-center justify-between">
                        <h3>{!! __('Vehicle') !!}</h3>
                        <x-input-error for="vehicle_id"/>
                        <div>
                            @if($vehicle_id)
                                {{-- Button - Remove Vehicle --}}
                                <button class="btn-icon-only !m-0"
                                        wire:click="removeVehicle()">
                                    <x-heroicon-o-x-mark/>
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($vehicle_id)
                        <x-vehicle-details :vehicle-id="$vehicle_id"/>
                    @else
                        <x-vehicle-select target-component="RentalCreateEdit"/>
                    @endif
                </div>

                {{--------------------------}}
                {{--         Tasks        --}}
                {{--------------------------}}
                <div class="mt-3 mb-2 flex flex-col">
                    <h3>{!! __('Tasks') !!}</h3>
                    @if(empty($rental_id))
                        <div class="p-4 text-center border rounded text-gray-500">
                            {!! __t('First save', 'the Rental') !!}.
                        </div>
                    @else
                        <div>
                            <x-rental-task-list :rental-id="$rental_id"/>
                        </div>
                    @endif
                </div>

                {{--------------------------}}
                {{--       Delete         --}}
                {{--------------------------}}
                @if($rental_id && \userCan('delete records'))
                    <button class="mt-2 py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                            onclick="log('openLiveConfirmModal DeleteRental {!! $rental_id??0 !!}'); openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Rental') !!}?','DeleteRental', {!! $rental_id??0 !!})">
                        <x-heroicon-o-trash class="w-5 h-5"/>
                        <span class="text-rose-700">{!! __t('Delete', 'Rental') !!}</span>
                    </button>
                @endif

            </div>

        </div>

        @if(debugOn())
            <div class="text-gray-500 text-right p-3">#{!! $rental_id??'0' !!}</div>
        @endif

    </x-mod-data-status-modified-saved-message>

</div>
