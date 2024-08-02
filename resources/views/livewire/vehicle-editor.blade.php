<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="form px-4 py-4 flex flex-col gap-4">
            <div class="flex items-center gap-4">
                <h2>{!! __('Vehicle') !!}</h2>
                @if($vehicle_id)
                    <x-app.info-icon-tooltip>
                        {!! __('Created') !!}: {!! dateLocalized($created_at) !!} {!! substr($created_at, 11, 5) !!}<br>
                        {!! __('Updated') !!}: {!! dateLocalized($updated_at) !!} {!! substr($updated_at, 11, 5) !!}
                    </x-app.info-icon-tooltip>
                @endif
            </div>

            @if(intval($active) === 0)
                <x-alert alertType="error" title="">
                    <div>Inactivo</div>
                </x-alert>
            @endif

            <input type="hidden" wire:model.live="vehicle_id"/>

            @if($vehicle_id)
                <div>
                    <label for="">Marca</label>
                    <input type="text" placeholder="Marca"
                           wire:model.live.debounce.500ms="vehicle_make"
                    />
                    <x-input-error name="vehicle_make"/>
                </div>
                <div>
                    <label for="">Modelo</label>
                    <input type="text" placeholder="Modelo"
                           wire:model.live.debounce.500ms="vehicle_model"
                    />
                    <x-input-error name="vehicle_model"/>
                </div>
                <div>
                    <label for="">Matricula</label>
                    <input type="text" placeholder="DAB0000"
                           wire:model.live.debounce.500ms="vehicle_plate"
                    />
                    <x-input-error name="vehicle_plate"/>
                </div>
                <div>
                    <label for="">Precio diario</label>
                    <input type="text" placeholder="$"
                           wire:model.live.debounce.500ms="vehicle_price"
                    />
                    <x-input-error name="vehicle_price"/>
                </div>
                <div>
                    <label for="">Kilómetros</label>
                    <input type="text" placeholder="0"
                           wire:model.live.debounce.500ms="vehicle_kms"
                    />
                    <x-input-error name="vehicle_kms"/>
                </div>
                <div>
                    <label for="">Notas</label>
                    <textarea rows="4" placeholder="Notas"
                              x-data-orig-length="{{ strlen($vehicle_make) }}"
                              wire:model.live.debounce.1500ms="notes"
                    ></textarea>
                    <x-input-error name="notes"/>
                </div>
                <div>
                    <label for="active">Activo</label>
                    <select wire:model.live="active">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                    <x-input-error name="active"/>
                </div>
                <div>
                    <label for="ordering">Orden</label>
                    <input type="number" placeholder="0"
                           wire:model.live.debounce.500ms="ordering"
                    />
                    <x-input-error name="ordering"/>
                </div>

                {{--------------------------}}
                {{--       Delete         --}}
                {{--------------------------}}
                @if(\userCan('delete records'))
                    <button class="mt-2 py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                            onclick="openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Vehicle') !!}', 'DeleteVehicle_VehicleEditor', {!! $vehicle_id??0 !!})">
                        <x-heroicon-o-trash class="w-5 h-5"/>
                        <span class="text-rose-700">{!! __t('Delete', 'Vehicle') !!}</span>
                    </button>
                @endif
            @endif

        </div>

    </x-mod-data-status-modified-saved-message>

</div>
