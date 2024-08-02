<x-app-layout>

    <x-slot name="pageTitle">{!! __('Client') !!}</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="border-b border-gray-200 px-4 dark:border-gray-700">
            <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
                <button type="button"
                        class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-400 active"
                        id="basic-tabs-item-1" data-hs-tab="#basic-tabs-1" aria-controls="basic-tabs-1" role="tab">
                    <div class="flex items-center gap-2">
                        <div class="min-w-6 min-h-6">
                            <x-heroicon-o-user class="w-6 h-6"/>
                        </div>
                        <span class="flex-shrink invisible qs:visible">
                            {!! __('Details') !!}
                        </span>
                    </div>
                </button>
                <button type="button"
                        class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-400"
                        id="basic-tabs-item-2" data-hs-tab="#basic-tabs-2" aria-controls="basic-tabs-2" role="tab">
                    <div class="flex items-center gap-2">
                        <div class="min-w-6 min-h-6">
                            <x-vaadin-car class="text-gray-700"/>
                        </div>
                        <span class="flex-shrink invisible qs:visible">
                            {!! __('Rentals') !!}
                        </span>
                    </div>
                </button>
                <button type="button"
                        class="hs-tab-active:border-b-blue-600 hs-tab-active:text-gray-900 dark:hs-tab-active:text-white dark:hs-tab-active:border-b-blue-600 relative min-w-0 flex-1 bg-white first:border-s-0 border-s border-b-2 py-4 px-4 text-gray-500 hover:text-gray-700 font-medium text-center overflow-hidden hover:bg-gray-50 focus:z-10 focus:outline-none focus:text-blue-600 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-l-gray-700 dark:border-b-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-400"
                        id="basic-tabs-item-3" data-hs-tab="#basic-tabs-3" aria-controls="basic-tabs-3" role="tab">
                    <div class="flex items-center gap-2">
                        <div class="min-w-6 min-h-6">
                            <x-heroicon-o-photo class="w-6 h-6 text-gray-700"/>
                        </div>
                        <span class="flex-shrink invisible qs:visible">
                            {!! __('Image') !!}
                        </span>
                    </div>
                </button>
            </nav>
        </div>


        <div class="mt-3 p-4">
            {{--Tab 1 contents--}}
            <div id="basic-tabs-1" role="tabpanel" aria-labelledby="basic-tabs-item-1">
                <livewire:client-details :client-id="$clientId" allow-edit="1" allow-view-rentals="0"/>
            </div>
            {{--Tab 2 contents--}}
            <div id="basic-tabs-2" class="hidden" role="tabpanel" aria-labelledby="basic-tabs-item-2">
                <livewire:client-rentals :client-id="$clientId"/>
            </div>
            {{--Tab 3 contents--}}
            <div id="basic-tabs-3" class="hidden" role="tabpanel" aria-labelledby="basic-tabs-item-3">
                {{-- Photos (Files) --}}
                <div class="relative border rounded mt-2">
                    <livewire:model-files-manager :model-class="\App\Models\Client::class" :model-id="$clientId"
                                                  media-collection="files" allow-upload="1"/>
                </div>
            </div>
        </div>
    </div>

    <x-my-offcanvas-left id="ClientEditor" title="Client">
        <livewire:client-editor/>
    </x-my-offcanvas-left>

    {{--    <x-my-offcanvas-left id="ClientRentals" title="Client Rentals" size="2">--}}
    {{--        <livewire:client-rentals client_id="0"/>--}}
    {{--    </x-my-offcanvas-left>--}}

</x-app-layout>
