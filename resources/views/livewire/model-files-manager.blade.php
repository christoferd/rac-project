<div x-data="{
    confirmDelete: () => { console.log('confirm delete'); },
    showUploadForm: false
    }">
    <div class="absolute top-0 left-0">
        <x-live.livewire-loading-spinner/>
    </div>

    {{-- Upload Form--}}
    <div class="p-3" x-cloak x-show="showUploadForm">
        @error('uploadedFile')
            <div class="p-3 border border-red-500"><span class="error">{{ $message }}</span></div>
        @enderror
        <form wire:submit="save">
            @csrf
            <h2 class="mb-3">Subir imágen</h2>
            <div class="">
                @if($uploadedFile)
                    <div class="flex flex-col gap-2 sm:flex-row sm:gap-3 mb-4">
                        <div>
                            <div class="text-xs pb-1 text-gray-500">Vista previa:</div>
                            <div>
                                <img src="{{ $uploadedFile->temporaryUrl() }}" class="w-40 border rounded p-2" alt="imágen previa">
                            </div>
                        </div>
                        <div>
                            <div class="text-xs pb-1 text-gray-500">{!! __('Confirm') !!}:</div>
                            <button type="submit" class="flex flex-row gap-2 items-center"
                                    x-on:click="showUploadForm=false"
                            >
                                <x-heroicon-m-arrow-up-tray class="w-4 h-4 font-bold"/>
                                <span>Guardar Foto</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-2">
                <input type="file" wire:model.live="uploadedFile">
                <div class="ml-4">
                    <ol class="list-decimal">
                        <li>Presiona el botón para elegir un archivo (un archivo por vez)</li>
                        <li>Espere a que se cargue el archivo y, a continuación, vea una vista previa de la imágen</li>
                        <li>Presiona el botón "Subir Foto"</li>
                    </ol>
                </div>
            </div>
        </form>
    </div>

    {{--    <div wire:loading.remove>--}}
    {{--    </div>--}}
    <div class="flex flex-wrap w-full align-top min-h-10">
        @if($allowUpload)
            <button class="btn-icon-only absolute top-1 right-1 w-10 h-10 rounded"
                    wire:ignore
                    x-on:click="showUploadForm=(!showUploadForm)">
                <x-heroicon-o-cloud-arrow-up class="w-4 h-4 text-gray-700"/>
            </button>
        @endif
        @if(!empty($media) && !$media->isEmpty())
            @foreach($media as $m)
                <div style="width: 118px; height: 148px;" class="p-1">
                    <div
                        wire:ignore
                        onclick="if(openMyModal('ImageGalleryHammer')) { ig_loadImage('{!! $m->getUrl() !!}', '{!! $loop->index !!}'); ig_setModelClassId('{!! addslashes($modelClass) !!}', {!! $modelId !!}); }"
                        class="w-full h-full overflow-hidden bg-contain bg-no-repeat bg-center cursor-pointer"
                        style="background-image: url('{!! $m->getUrl('thumb_160') !!}');"
                    ></div>
                </div>
            @endforeach
        @else
            <div class="flex-grow pt-2.5 pl-3 text-gray-500 text-sm">0 {!! __('Files') !!}</div>
        @endif
    </div>
</div>
