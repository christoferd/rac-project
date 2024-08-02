<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    <x-mod-data-status-modified-saved-message>

        <div class="form px-4 py-4 flex flex-col gap-4">

            <div class="flex items-center gap-4">
                <h2>{!! __('Client') !!}</h2>
                <x-app.info-icon-tooltip>
                    {!! __('Created') !!}: {!! dateLocalized($created_at) !!} {!! substr($created_at, 11, 5) !!}<br>
                    {!! __('Updated') !!}: {!! dateLocalized($updated_at) !!} {!! substr($updated_at, 11, 5) !!}
                    @role('developer')
                    <br><span class="text-orange-500">ID: {!! $client_id !!}</span>
                    @endrole
                </x-app.info-icon-tooltip>
            </div>

            <input type="hidden" wire:model.live="client_id"/>

            <div>
                {{--Name--}}
                <label for="">{!! __('Name') !!}</label>
                <input type="text"
                       wire:model.live.debounce.500ms="name"
                />
                <x-input-error name="name"/>
            </div>
            <div>
                {{--Address--}}
                <label for="">{!! __('Address') !!}</label>
                <input type="text"
                       wire:model.live.debounce.500ms="address"
                />
                <x-input-error name="address"/>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    {{--Telephone--}}
                    <label for="">{!! ucfirst(__('phone number')) !!}</label>
                    <input type="text"
                           wire:model.live.debounce.500ms="phone_number"
                    />
                </div>
                {{--Rating--}}
                <div class="flex-0">
                    <label>{!! __('Rating') !!}</label>
                    <x-form.select-client-rating wire:model.live="rating"/>
                </div>
            </div>
            <x-input-error name="phone_number"/>
            <x-input-error name="rating"/>
            <div>
                {{--Notes--}}
                <label for="">Notas</label>
                <textarea rows="4" wire:model.live.debounce.500ms="notes"></textarea>
                <x-input-error name="notes"/>
            </div>

            {{---------------------------------}}
            {{--       Images/Photos         --}}
            {{---------------------------------}}
            <div class="border rounded p-3">
                <div class="relative border rounded mt-2">
                    @if(!empty($client_id))
                        <livewire:model-files-manager :model-class="\App\Models\Client::class" :model-id="$client_id"
                                                      media-collection="files" allow-upload="1"/>
                    @endif
                </div>
            </div>

            <div>
                {!! __('Created') !!}: {!! dateLocalized($created_at) !!} {!! substr($created_at, 11, 5) !!}<br>
                {!! __('Updated') !!}: {!! dateLocalized($updated_at) !!} {!! substr($updated_at, 11, 5) !!}
            </div>

            {{--------------------------}}
            {{--       Delete         --}}
            {{--------------------------}}
            @if(\userCan('delete records'))
                <button class="mt-2 py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                        onclick="openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Client') !!}', 'DeleteClient', {!! $client_id??0 !!})">
                    <x-heroicon-o-trash class="w-5 h-5"/>
                    <span class="text-rose-700">{!! __t('Delete', 'Client') !!}</span>
                </button>
            @endif
        </div>

    </x-mod-data-status-modified-saved-message>

</div>
