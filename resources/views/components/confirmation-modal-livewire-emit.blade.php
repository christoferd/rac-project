@props(['id', 'title'=>'', 'cancelText' => 'Cancela', 'confirmText' => 'Confirmo', 'livewireEmitParam1' => '', 'livewireEmitParam2' => ''])
{{--Attributes are applied to the Confirm Button element--}}
<div id="{!! $id !!}" class="hs-overlay hidden w-full h-full fixed top-0 left-0 z-confirm overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl">
            <div class="flex justify-between items-center py-3 px-4">
                <h3 class="text-xl font-bold text-gray-800">
                    @if($title)
                        Confirma
                    @endif
                </h3>
                <button type="button"
                        class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm"
                        data-hs-overlay="#{!! $id !!}">
                    <span class="sr-only">Close</span>
                    <x-heroicon-o-x-mark class="font-bold w-5 h-5"/>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-lg text-gray-800">
                    {{ $slot }}
                </p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4">
                <button type="button"
                        class="hs-dropdown-toggle py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-md text-gray-700 shadow-sm align-middle hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all"
                        data-hs-overlay="#{!! $id !!}"
                >
                    {!! $cancelText !!}
                </button>
                <button type="button"
                        class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-red-700 text-md text-gray-50 shadow-sm align-middle hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all"
                        data-hs-overlay="#{!! $id !!}"
                    {{ $attributes->except(['title','confirmText', 'cancelText']) }}
                >
                    {!! $confirmText !!}
                </button>
            </div>
        </div>
    </div>
</div>
