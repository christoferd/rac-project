<div>
    {{--
    Offline Message
    --}}
    @include('app.offline-message')

    <x-live.livewire-loading-spinner/>

    {{--
    Actions Bar
    --}}
    <div class="no-print flex gap-2 items-center">
        {{--Search--}}
        <div class="flex-1">
            @if($showSearchBox)
                <div class="relative w-full max-w-lg">
                    <input type="text" placeholder="{!! __('Search') !!}"
                           name="searchString"
                           wire:model.live.debounce.500ms="searchString"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pointer-events-none pr-3">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 text-gray-500"/>
                    </div>
                </div>
            @endif
        </div>
        {{--Buttons--}}
        <div class="flex flex-1 gap-2 items-center justify-end">
            @if($showButtonNew)
                {{--New/Create Button--}}
                <button class="btn"
                        onclick="Livewire.dispatch('ClickedCreateClient'); openOffCanvas('ClientCreator')"
                >
                    <x-heroicon-o-plus class="w-4 h-4"/>
                    {{--fix centering issue!--}}
                    <span style="line-height: 17px">{!! __('New') !!}</span>
                </button>
            @endif

            <div class="hs-dropdown relative inline-flex">
                <button id="hs-dropdown-default" type="button"
                        class="hs-dropdown-toggle btn inline-flex items-center gap-x-2 disabled:opacity-50 disabled:pointer-events-none">
                    <x-vaadin-menu class="w-4 h-4"/>
                </button>

                <div
                    class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[15rem] bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-gray-800 dark:border dark:border-gray-700 dark:divide-gray-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    aria-labelledby="hs-dropdown-default">
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700"
                       href="{!! route('merge-clients') !!}">
                        <x-vaadin-compress-square class="w-4 h-4 text-gray-700"/>
                        <span>{!! __t('Merge', 'Clients') !!}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{--
    Data
    --}}
    <div x-data class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    @if($clients)
                        {{--
                        Small Screens
                        --}}
                        <div class="block qs:hidden">
                            <div class="flex-column gap-1">
                                @foreach($clients as $client)
                                    <div class="border-b pb-2 pt-2"
                                         x-on:click.stop="log('! Clicked row'); Livewire.dispatch('ClickedEditClient', [{!! $client->id !!}]); openOffCanvas('ClientEditor')"
                                    >
                                        <div class="flex-column">
                                            {{--Buttons--}}
                                            <div class="flex items-center justify-between">
                                                <div class="flex-grow">{{ $client->name }}</div>
                                                <div class="flex-0 flex gap-1">
                                                    <x-button.phone-call-link-icon size="5" phone-number="{{ $client->phone_number }}"/>
                                                    <x-button.whatsapp-link-icon size="5" client-id="{!! $client->id !!}"/>
                                                    <div
                                                        x-on:click.stop="log('! Clicked car icon'); Livewire.dispatch('ClickedClientRentals', [{{ $client->id }}]); openOffCanvas('ClientRentals')"
                                                    >
                                                        <x-vaadin-car class="w-5 h-5 text-gray-700"/>
                                                    </div>
                                                    @if($checkboxes !== '')
                                                        <input type="checkbox" name="{!! $checkboxes !!}[]" value="{!! $client->id !!}"
                                                               x-on:click.stop="log('! Clicked checkbox #{!! $client->id !!}');"
                                                               class="{!! $checkboxes !!} mr-1 ml-0.5 relative top-0.5"/>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($client->address)
                                                <div class="pl-10">{{ $client->address }}</div>
                                            @endif

                                            {{--Buttons--}}
                                            <div class="pl-10 flex justify-between">
                                                <div class="flex items-center gap-2">
                                                    @if($client->phone_number)
                                                        <span>{{ $client->phone_number }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center text-right">
                                                    <x-heroicon-o-star class="h-5 w-5"/>
                                                    <span>{!! \App\Models\Client::$selectOptions['rating'][$client->rating]??'#Error#' !!}</span>
                                                </div>
                                            </div>

                                            @if($client->notes)
                                                <div class="pl-10 text-gray-500">
                                                    Notas: {{ $client->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{--
                        Medium Screens +
                        --}}
                        <table class="hidden qs:block min-w-full">
                            <thead>
                            <tr>
                                <th scope="col" class="no-print text-left"></th>
                                <th scope="col" class="text-left">{!! ucfirst(__(Client::label('name'))) !!}</th>
                                <th scope="col" class="text-left w-50">{!! ucfirst(__(Client::label('address'))) !!}</th>
                                <th scope="col" class="text-left">{!! ucfirst(__(Client::label('phone_number'))) !!}</th>
                                <th scope="col" class="text-left max-w-10 overflow-hidden overflow-ellipsis"
                                    title="{!! __(Client::label('rating')) !!}">
                                    {!! ucfirst(__(Client::label('rating'))) !!}
                                </th>
                                <th scope="col" class="text-left">{!! ucfirst(__(Client::label('notes'))) !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200 {!! ($clickRowAction?'cursor-pointer':'') !!}"
                                    @if($clickRowAction)
                                    x-on:click.stop="log('! Clicked row'); window.location='{{ route('clients.show', $client->id) }}'"
                                    @endif
                                >
                                    <td class="no-print">
                                        <div class="flex gap-2 items-center">
                                            <div class="ptr"
                                                 x-on:click.stop="log('! Clicked pencil icon'); Livewire.dispatch('ClickedEditClient', [{!! $client->id !!}]); openOffCanvas('ClientEditor')">
                                                <x-heroicon-o-pencil-square class="w-5 h-5 text-gray-700"/>
                                            </div>
                                            <div class="ptr"
                                                 x-on:click.stop="log('! Clicked car icon'); Livewire.dispatch('ClickedClientRentals', [{{ $client->id }}]); openOffCanvas('ClientRentals')"
                                            >
                                                <x-vaadin-car class="w-5 h-5 text-gray-700"/>
                                            </div>
                                            <x-button.phone-call-link-icon size="5" phone-number="{{ $client->phone_number }}"/>
                                            <x-button.whatsapp-link-icon size="5" client-id="{!! $client->id !!}"/>
                                            @if($checkboxes !== '')
                                                <input type="checkbox" name="{!! $checkboxes !!}[]" value="{!! $client->id !!}"
                                                       x-on:click.stop="log('! Clicked checkbox #{!! $client->id !!}');"
                                                       class="{!! $checkboxes !!} mr-1 ml-0.5"
                                                />
                                            @endif
                                        </div>
                                    </td>
                                    <td class="">{{ $client->name }}</td>
                                    <td class="w-50 !whitespace-normal">{{ $client->address }}</td>
                                    <td class="">{{ $client->phone_number }}</td>
                                    <td class="max-w-10">{{ $client->rating }}</td>
                                    <td class="!whitespace-normal">{{ $client->notes }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-lg text-center my-6">{!! __('No results') !!}</div>
                    @endif
                </div>
            </div>
            <div class="max-w-4xl my-6">
                {!! ($paginationPerPage && $clients ? $clients->links() : '') !!}
            </div>
        </div>
    </div>

    {{-- Client > Images (-Manager-) --}}
    {{--    // Chris D. 11-Apr-2024 - Replaced with ModelFilesManager--}}
    {{--    <x-my-modal-top id="ClientImagesManager">--}}
    {{--        <x-client-image-manager/>--}}
    {{--    </x-my-modal-top>--}}

</div>
