<x-app-layout>

    <x-slot name="pageTitle">{!! __('Clients') !!}</x-slot>

    <livewire:clients-table/>

    <x-my-offcanvas-left id="ClientCreator" title="Create Client">
        <livewire:client-creator/>
    </x-my-offcanvas-left>

    <x-my-offcanvas-left id="ClientEditor" title="Client">
        <livewire:client-editor/>
    </x-my-offcanvas-left>

    <x-my-offcanvas-left id="ClientRentals" title="Client Rentals" size="2">
        <livewire:client-rentals client_id="0"/>
    </x-my-offcanvas-left>

</x-app-layout>
