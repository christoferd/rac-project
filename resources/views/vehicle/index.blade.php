<x-app-layout>

    <x-slot name="pageTitle">Vehículos</x-slot>

    <div class="flex justify-between">
        <div>&nbsp;</div>
        <button class="btn"
                onclick="Livewire.dispatch('ClickedCreateVehicle'); openOffCanvas('VehicleCreator')"
        >
            <x-heroicon-o-plus-small class="w-5 h-5"/>
            {{--style to fix centering issue--}}
            <span style="line-height: 17px">{!! __('New') !!}</span>
        </button>
    </div>

    <livewire:vehicles-table/>

    <x-my-offcanvas-left id="VehicleCreator">
        <livewire:vehicle-creator/>
    </x-my-offcanvas-left>

    <x-my-offcanvas-left id="VehicleEditor">
        <livewire:vehicle-editor/>
    </x-my-offcanvas-left>

</x-app-layout>
