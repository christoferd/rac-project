<x-app-layout>

    <x-slot name="pageTitle">{!! __('Rentals') !!}</x-slot>

    <livewire:rentals-table/>

    {{-- Rental Editor --}}
    <x-my-offcanvas-left id="RentalEditor" class="max-w-full">
        <livewire:rental-create-edit/>
    </x-my-offcanvas-left>

    {{-- Client Editor --}}
    <x-my-offcanvas-left id="ClientEditor">
        <livewire:client-editor/>
    </x-my-offcanvas-left>

    {{-- Client > Images (-Manager-) --}}
    {{--    // Chris D. 11-Apr-2024 - Replaced with ModelFilesManager--}}
    {{--    <x-my-modal-top id="ClientImagesManager">--}}
    {{--        <x-client-image-manager/>--}}
    {{--    </x-my-modal-top>--}}

</x-app-layout>
