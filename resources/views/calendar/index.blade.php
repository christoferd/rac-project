<x-app-layout jsRefreshPageDaily="1">

    <x-slot name="pageTitle">{!! __('Calendar') !!}</x-slot>

    {{-- Calendar --}}
    <livewire:rental-calendar/>

    {{-- Vehicle --}}
    <x-my-offcanvas-left id="VehicleEditor" size="1">
        <livewire:vehicle-editor/>
    </x-my-offcanvas-left>

    {{-- Rental --}}
    <x-my-offcanvas-left id="RentalCreateEdit" size="2">
        <livewire:rental-create-edit/>
    </x-my-offcanvas-left>

    {{-- Client > Images (-Manager-) --}}
    {{--    // Chris D. 11-Apr-2024 - Replaced with ModelFilesManager--}}
    {{--    <x-my-modal-top id="ClientImagesManager">--}}
    {{--        <x-client-image-manager/>--}}
    {{--    </x-my-modal-top>--}}

    {{-- Client Creator --}}
    <x-my-offcanvas-left id="ClientCreator" title="Create Client">
        <livewire:client-creator/>
    </x-my-offcanvas-left>

    {{-- Client Editor --}}
    <x-my-offcanvas-left id="ClientEditor" title="Edit Client">
        <livewire:client-editor/>
    </x-my-offcanvas-left>

    {{-- Client > Rentals --}}
    <x-my-offcanvas-left-2 id="ClientRentals" size="2">
        <livewire:client-rentals client_id="0"/>
    </x-my-offcanvas-left-2>

</x-app-layout>
