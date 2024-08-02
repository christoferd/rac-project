<div class="flex flex-col gap-4">
    <div class="relative top-0 left-0">
        <x-live.livewire-loading-spinner/>
    </div>
    <div wire:loading.remove>

        <form wire:submit="save">
            @csrf
            <h2 class="mb-3">Subir imágen</h2>
            <div class="">
                @if($uploadedFile)
                    <div class="flex flex-col gap-2 sm:flex-row sm:gap-3 mb-4">
                        <div>Vista previa:</div>
                        <div>
                            <img src="{{ $uploadedFile->temporaryUrl() }}" class="w-40 border rounded p-2" alt="imágen previa">
                        </div>
                        <div>
                            <button type="submit" class="flex flex-row gap-2 items-center">
                                <x-heroicon-m-arrow-up-tray class="w-4 h-4 font-bold"/>
                                <span>Subir Foto</span>
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

            @error('uploadedFile') <span class="error">{{ $message }}</span> @enderror

        </form>
    </div>
</div>
