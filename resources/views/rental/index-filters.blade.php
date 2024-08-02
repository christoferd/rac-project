<div x-data="{w: 'w-8', h: 'h-8', open: 'Does not work properly using open!',
toggleMenu() {
  console.info('toggleMenu');
  if($refs.filtersMenu.classList.contains(this.w)) {
    $refs.filtersMenu.classList.remove(this.w,this.h); } else { $refs.filtersMenu.classList.add(this.w,this.h);
  }
}}"
     {{--// Does not work properly using open because when Livewire refreshes, the state is not remembered--}}
     x-ref="filtersMenu"
     {{--Require overflow-visible due to search select component results.--}}
     class="flex flex-wrap items-center gap-3 overflow-visible w-8 h-8 sm:w-auto sm:h-auto"
>
    {{-- Icon --}}
    <div class="inline-block" x-on:click="toggleMenu">
        <x-heroicon-o-funnel class="w-6 h-6"/>
    </div>

    {{-- Client - Select 14-May-2024 Removed because they can just go to Clients table, find the client and see history of rentals. --}}

    {{-- Vehicle - Select --}}
    <div class="max-w-40">
        <x-vehicle-select target-component="RentalsTable"/>
    </div>

    {{-- Date picker (standard, input[type="date"]) --}}
    <div class="max-w-40">
        <input type="date" placeholder="Fecha" value="{!! $filterDate !!}"
               onchange="console.log('date changed: ' + this.value); Livewire.dispatch('SelectedDate_RentalsTable', [this.value]);"
        />
    </div>

    {{-- Sorting Options --}}
    <div class="flex items-center gap-1">

        {{-- Icon Sorting/Order --}}
        <div>
            <x-preline.tooltip tip="Ordenar por columna">
                <x-heroicon-o-adjustments-vertical class="w-6 h-6"/>
            </x-preline.tooltip>
        </div>

        {{-- Sorting Dropdown --}}
        <div class="hs-dropdown relative inline-flex">

            {{-- Sorting Button --}}
            <button id="hs-dropdown-with-icons" type="button"
                    wire:ignore
                    class="hs-dropdown-toggle py-2 px-3 inline-flex justify-center items-center gap-1 rounded-md border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm">
                <span x-ref="buttonLabel" class="flex items-center gap-1">
                Ordenar <x-heroicon-o-chevron-up-down class="w-5 h-5"/>
                </span>
                <x-heroicon-m-chevron-down class="w-5 h-5"/>
            </button>

            {{-- Links Panel --}}
            <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 divide-y divide-gray-200 dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700"
                aria-labelledby="hs-dropdown-with-icons">
                <div class="py-2 first:pt-0 last:pb-0">
                    @foreach($orderByOptions as $orderByArr)
                        <a class=""
                           x-on:click="$refs.buttonLabel.innerHTML = $el.innerHTML"
                           href="javascript:void(0)" wire:click="setOrdering('{!! $orderByArr['key'] !!}','{!! $orderByArr['direction'] !!}')">
                            <x-vaadin-play
                                class="w-3 h-3 text-gray-700 relative top-[-1px] {!! ($orderByArr['direction'] === 'desc'?'rotate-90':'rotate-270') !!}"/>
                            {!! $orderByArr['label'] !!}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Per Page dropdown --}}
        <div>
            <select id="perPageSelect" wire:model.live="perPage">
                @foreach($perPageOptions as $value => $label)
                    <option value="{!! $value !!}">{!! $label !!}</option>
                @endforeach
            </select>
        </div>

    </div>

</div>
