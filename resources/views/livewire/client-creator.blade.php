<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="form px-4 py-4 flex flex-col gap-4">

            <div><h2>{!! __t('Create', 'Client') !!}</h2></div>

            @if(!$dataSaved)
                {{-- Save Button --}}
                <x-button.save class="mr-6" wire:click="save"/>

                <div>
                    <label for="">{!! __('Name') !!}</label>
                    <input type="text"
                           wire:model="name"
                    >
                    <x-input-error name="name"/>
                </div>
                <div>
                    <label for="">{!! __('Address') !!}</label>
                    <input type="text"
                           wire:model="address"
                    >
                    <x-input-error name="address"/>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        {{--Telephone--}}
                        <label for="">{!! ucfirst(__('phone number')) !!}</label>
                        <input type="text" wire:model="phone_number"/>
                    </div>
                    <div class="flex-0">
                        {{--Rating--}}
                        <label>{!! __('Rating') !!}</label>
                        <x-form.select-client-rating wire:model="rating"/>
                    </div>
                </div>
                <x-input-error name="phone_number"/>
                <x-input-error name="rating"/>
                <div>
                    <label for="">Notas</label>
                    <textarea rows="4"
                              wire:model="notes"
                    ></textarea>
                    <x-input-error name="notes"/>
                </div>

            @endif
        </div>

    </x-mod-data-status-modified-saved-message>

</div>
