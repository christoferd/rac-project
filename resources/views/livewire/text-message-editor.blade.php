<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    @if($model_id)

        <x-mod-data-status-modified-saved-message>

            <div class="form px-4 py-4 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <h2>{!! __('Message') !!}</h2>
                </div>

                <div>
                    <label for="">{!! __('Title') !!}</label>
                    <input type="text" placeholder="{!! __('Title') !!}"
                           wire:model.live.debounce.1500ms="message_title"
                    />
                    <x-input-error name="message_title"/>
                </div>

                <div>
                    <label for="">{!! __('Notes') !!}</label>
                    <input type="text" placeholder="{!! __('Notes') !!}"
                           wire:model.live.debounce.1500ms="message_notes"
                    />
                    <x-input-error name="message_notes"/>
                </div>

                <div>
                    <label for="">{!! __('Message') !!}</label>
                    <textarea id="ta_message_content" rows="8"
                        class="w-full bg-white rounded-md border hover:border-gray-500 border-gray-200 placeholder:text-gray-400 px-4 py-2"
                        onkeyup="this.autoResizeHeight()"
                        onclick="this.autoResizeHeight()"
                        wire:model.live.debounce.1500ms="message_content"
                    ></textarea>
                    <x-input-error name="message_content"/>
                </div>

                <div class="border rounded px-4 py-2 block text-sm">
                    <h4 class="font-bold text-lg">{!! __('Special Phrases') !!}</h4>
                    <div>{cliente.nombre}</div>
                    <div>{cliente.teléfono}</div>
                    <div>{cliente.dirección}</div>
                    <div>{vehículo.marca}</div>
                    <div>{vehículo.modelo}</div>
                    <div>{vehículo.precio_por_dia}</div>
                    <div>{vehículo.patente}</div>
                    <div>{alquiler.fecha_retiro}</div>
                    <div>{alquiler.hora_retiro}</div>
                    <div>{alquiler.fecha_retorno}</div>
                    <div>{alquiler.hora_retorno}</div>
                    <div>{alquiler.precio_por_dia}</div>
                    <div>{alquiler.dias_total}</div>
                    <div>{alquiler.precio_total}</div>
                </div>

                {{--------------------------}}
                {{--       Delete         --}}
                {{--------------------------}}
                @if(\userCan('delete records'))
                    <div class="mt-4">
                        <button class="py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                                onclick="openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Message') !!}', 'DeleteTextMessage_TextMessageEditor', {!! $model_id??0 !!})">
                            <x-heroicon-o-trash class="w-5 h-5"/>
                            <span class="text-rose-700">{!! __t('Delete', 'Message') !!}</span>
                        </button>
                    </div>
                @endif
            </div>

        </x-mod-data-status-modified-saved-message>

    @endif

</div>
