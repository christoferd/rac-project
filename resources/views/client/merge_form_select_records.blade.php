<x-app-layout>

    {{-- Slot for app layout --}}
    <x-slot name="pageTitle">
        {!! __t('Merge', 'Clients') !!}
    </x-slot>

    <div class="relative overflow-x-auto">

        <div class="mx-auto">
            @include('client.merge_breadcrumbs', ['step'=>1])
        </div>


        <form action="{!! route('merge-clients-edit') !!}" method="post" class="border-t p-8 pb-40">
            @csrf
            <div class="flex items-center">
                <div>&nbsp;</div>
                <h4 class="prose text-center mx-auto mb-3">Seleccione los registros que desea combinar.</h4>
                <x-button-submit type="submit"
                                 wire:loading.attr="disabled"
                                 onclick="let c = document.querySelectorAll('.clients:checked').length;
                                     log('checked count = ' + c);
                                     if(c!==undefined && c<2) {
                                         Livewire.dispatch('AddAlert', ['error', 'Seleccione al menos 2 registros.']);
                                         return false;
                                     }"
                >
                    <div class="flex items-center gap-2">
                        <span>{!! __('Next') !!}</span>
                        <x-heroicon-m-chevron-right class="w-4 h-4"/>
                    </div>
                </x-button-submit>
            </div>

            <livewire:clients-table :show-button-new="false" checkboxes="clients" :click-row-action="false"/>

        </form>
    </div>

    {{-- Client > Rentals --}}
    <x-my-offcanvas-left id="ClientRentals" size="2">
        <livewire:client-rentals client_id="0"/>
    </x-my-offcanvas-left>

</x-app-layout>
