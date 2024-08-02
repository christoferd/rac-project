<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="form px-4 py-4 flex flex-col gap-4">

            <div><h2>{!! __t('Create', 'Vehicle') !!}</h2></div>

            @if(!$dataSaved)
                {{-- Save Button --}}
                <x-button.save class="mr-6" wire:click="save"/>

                <div>
                    <label for="">Marca</label>
                    <input type="text" placeholder="Marca"
                           wire:model="vehicle_make"
                    >
                    <x-input-error name="vehicle_make"/>
                </div>
                <div>
                    <label for="">Modelo</label>
                    <input type="text" placeholder="Modelo"
                           wire:model="vehicle_model"
                    >
                    <x-input-error name="vehicle_model"/>
                </div>
                <div>
                    <label for="">Matricula</label>
                    <input type="text" placeholder="DAB0000"
                           wire:model="vehicle_plate"
                    >
                    <x-input-error name="vehicle_plate"/>
                </div>
                <div>
                    <label for="">Kms</label>
                    <input type="text" placeholder="Kilómetros"
                           wire:model="vehicle_kms"
                    >
                    <x-input-error name="vehicle_kms"/>
                </div>
                <div>
                    <label for="">Precio diario</label>
                    <input type="text" placeholder="$ p/dia"
                           wire:model="vehicle_price"
                    >
                    <x-input-error name="vehicle_price"/>
                </div>
                <div>
                    <label for="">Notas</label>
                    <textarea rows="4" placeholder="Notas"
                              wire:model="notes"
                    ></textarea>
                    <x-input-error name="notes"/>
                </div>

            @endif
        </div>

    </x-mod-data-status-modified-saved-message>

</div>
