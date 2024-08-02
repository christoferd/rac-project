<div class="w-full h-screen">

    <x-live.livewire-loading-spinner/>

    @if($task_id)

        <x-mod-data-status-modified-saved-message>

            <div class="form px-4 py-4 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <h2>{!! __('Task') !!}</h2>
                    <x-app.info-icon-tooltip>
                        {!! __('Created') !!}: {!! dateLocalized($created_at) !!} {!! substr($created_at, 11, 5) !!}<br>
                        {!! __('Updated') !!}: {!! dateLocalized($updated_at) !!} {!! substr($updated_at, 11, 5) !!}
                    </x-app.info-icon-tooltip>
                </div>

                @if(intval($active) === 0)
                    <x-alert alertType="error" title="">
                        <div>Inactivo</div>
                    </x-alert>
                @endif

                <div>
                    <label for="">{!! __('Title') !!}</label>
                    <input type="text" placeholder="{!! __('Title') !!}"
                           wire:model.live.debounce.500ms="title"
                    />
                    <x-input-error name="title"/>
                </div>
                <div>
                    <label for="active">{!! __('Active') !!}</label>
                    <select wire:model.live="active">
                        <option value="1">{!! __('Yes') !!}</option>
                        <option value="0">{!! __('No') !!}</option>
                    </select>
                    <x-input-error name="active"/>
                </div>

                {{--------------------------}}
                {{--       Delete         --}}
                {{--------------------------}}
                @if(\userCan('delete records'))
                    <div class="mt-4">
                        <button class="py-2 px-4 flex items-center gap-2 rounded hover:bg-rose-200 hover:shadow"
                                onclick="openLiveConfirmModal('{!! __t('Are you sure you want to delete', 'the Task') !!}', 'DeleteTask_TaskEditor', {!! $task_id??0 !!})">
                            <x-heroicon-o-trash class="w-5 h-5"/>
                            <span class="text-rose-700">{!! __t('Delete', 'Task') !!}</span>
                        </button>
                    </div>
                @endif
            </div>

        </x-mod-data-status-modified-saved-message>

    @endif

</div>
