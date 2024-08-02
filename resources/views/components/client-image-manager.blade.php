<div>

    <h1>


        Not Used // Chris D. 11-Apr-2024


    </h1>

    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
            <button type="button"
                    class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600 active"
                    id="tabs-with-icons-item-1" data-hs-tab="#tabs-with-icons-1" aria-controls="tabs-with-icons-1" role="tab">
                <x-heroicon-m-photo class="w-4 h-4"/>
                <span>Imágenes</span>
            </button>
            <button type="button"
                    class="hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
                    id="tabs-with-icons-item-2" data-hs-tab="#tabs-with-icons-2" aria-controls="tabs-with-icons-2" role="tab">
                <x-heroicon-m-arrow-up-tray class="w-4 h-4"/>
                <span>Subir</span>
            </button>
        </nav>
    </div>

    <div class="mt-3 min-h-50">
        <div id="tabs-with-icons-1" role="tabpanel" aria-labelledby="tabs-with-icons-item-1">
{{--            <livewire:client-images-display client-id="{!! $clientId !!}"/>--}}
        </div>
        <div id="tabs-with-icons-2" class="hidden" role="tabpanel" aria-labelledby="tabs-with-icons-item-1">
{{--            <livewire:client-images-upload client-id="{!! $clientId !!}"/>--}}
        </div>
    </div>
</div>
