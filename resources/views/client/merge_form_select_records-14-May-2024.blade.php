<x-app-layout>

    {{-- Slot for app layout --}}
    <x-slot name="pageTitle">
        {!! __t('Merge', 'Clients') !!}
    </x-slot>

    <div class="relative overflow-x-auto">

        <div class="mx-auto">
            @include('client.merge_breadcrumbs', ['step'=>1])
        </div>

        <h4 class="prose text-center mx-auto">Seleccione los registros que desea combinar.</h4>

        <form action="{!! route('merge-clients-edit') !!}" method="post" class="border-t p-8 pb-40">
            @csrf
            <div id="sticky-banner" tabindex="-1"
                 class="fixed top-6 right-40 z-50 flex justify-between w-0 p-0 m-0 border-none bg-transparent">
                <div class="flex-col items-center">
                    <x-button-submit type="submit"
                                     wire:loading.attr="disabled"
                                     onclick="let c = document.querySelectorAll('.merge-check:checked').length;
                                     log('merge-check:checked length='+c);
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
            </div>

            <table x-data="{}">
                <thead>
                <tr>
                    <th>
                        <x-heroicon-m-check-circle class="w-4 h-4"/>
                    </th>
                    <th class="text-left"><a href="?orderBy=name" x-tooltip.raw="{!! __('Orden') !!}">{!! __('Name') !!}</a></th>
                    <th class="text-left"><a href="?orderBy=address" x-tooltip.raw="{!! __('Orden') !!}">{!! __('Address') !!}</a></th>
                    <th class="text-left"><a href="?orderBy=phone_number" x-tooltip.raw="{!! __('Orden') !!}">{!! __('Phone Number') !!}</a></th>
                    <th class="text-left"><a href="?orderBy=rating" x-tooltip.raw="{!! __('Orden') !!}">{!! __('Rating') !!}</a></th>
                    <th class="text-left">{!! __('Rentals') !!}</th>
                </tr>
                </thead>
                <tbody>
                {{-- To make things much easier, let's just display all clients, regardless of the quantity. // Chris D. 24-Dec-2023 --}}
                @foreach($clients as $i => $client)
                    <tr x-on:click="log('row click client_{!! $client->id !!}'); $refs.client_{!! $client->id !!}.checked = (!$refs.client_{!! $client->id !!}.checked)"
                        class="cursor-pointer hover:bg-gray-200">
                        <td class="text-center">
                            <x-checkbox name="clients[]" :value="$client->id" class="merge-check"
                                        x-ref="client_{!! $client->id !!}"
                                        {{--need to stop propagation coz of click effect on row behind this checkbox--}}
                                        x-on:click.stop="return true"
                                        {{--Auto check some boxes for testing--}}
                                        :checked="!isProduction() && ($i < 3)"/>
                        </td>
                        <th class="text-left">{{ $client->name }}</th>
                        <td class="text-left">{{ $client->address }}</td>
                        <td class="text-left">{{ $client->phone_number }}</td>
                        <td class="text-center">{{ $client->rating }}</td>
                        <td class="text-center">
                            {{-- Button - Client Rentals History         --}}
                            <button type="button" x-tooltip.raw="{!! __tCsv('View,Client,Rentals') !!}"
                                    class="btn-icon-only !w-10 mx-auto px-2 flex items-center gap-1 text-center justify-around"
                                    x-on:click.stop="log('! dispatch ClickedClientRentals {{ $client->id }}'); Livewire.dispatch('ClickedClientRentals', [{{ $client->id }}]); openOffCanvas('ClientRentals')">
                                <span>{!! $client->count_rentals !!}</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    </div>

    {{-- Client > Rentals --}}
    <x-my-offcanvas-left id="ClientRentals" size="2">
        <livewire:client-rentals client_id="0"/>
    </x-my-offcanvas-left>

</x-app-layout>
