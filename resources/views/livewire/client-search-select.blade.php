<div class="w-full relative group" x-data="{searchText: ''}">
    @if(empty($client_id))
        <div class="relative flex items-center gap-3"
             x-on:keydown.down.prevent="log('keydown.down focus.next'); $focus.next()"
             x-on:keydown.up.prevent="log('keydown.down focus.next'); $focus.previous()"
            {{-- This is problematic!!! x-on:click.away="searchText = ''; $wire.clearSearch()"--}}
        >
            {{--Search Bar--}}
            <div class="flex items-center w-full">
                @if($showIconLeft)
                    <div class="flex-0 w-8">
                        <x-heroicon-m-identification class="w-4 h-4"/>
                    </div>
                @endif
                <div class="flex-1 relative w-full">
                    <input type="search"
                           class="clientSearch leading-6 w-full py-2 px-3 z-20 text-sm text-gray-900 bg-gray-50 rounded-lg  border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           wire:model.live.debounce.500ms="search"
                           placeholder="{!! __('Search') !!} {!! __('Client') !!}"
                           autocomplete="off"
                           {{--autofocus="autofocus"--}}
                           x-model="searchText"
                           z-index="1"
                    >
                    <div class="absolute top-3 right-0 px-3 text-sm font-medium text-gray-400 rounded-r-lg">
                        {{-- SVG magnifine glass--}}
                        <div wire:loading.remove wire:target="search">
                            @if($search)
                                <div class="cursor-pointer" wire:click="clearSearch()" x-on:click="searchText = ''">
                                    <x-heroicon-m-x-mark class="w-4 h-4"/>
                                </div>
                            @else
                                <x-heroicon-m-magnifying-glass class="w-4 h-4"/>
                            @endif
                        </div>

                        {{-- SVG search load spinner--}}
                        <svg wire:loading.delay wire:target="search"
                             aria-hidden="true" class="animate-spin w-5 h-5 z-1000 bg-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd"
                                  d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                  fill="currentColor"></path>
                            <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
                        </svg>
                    </div>
                </div>
            </div>
            {{--Search Results--}}
            @if (strlen($search) >= $minCharsToSearch)
                <ul wire:loading.remove
                    class="absolute top-10 z-50 bg-white border border-gray-300 shadow-xl rounded-md text-gray-700 text-sm divide-y divide-gray-200 {!! $resultsPanelClass !!}">
                    @forelse ($searchResults as $i => $result)
                        <li class="odd:bg-gray-100 border-0 ring-0">
                            <button id="focusable-element-{!! $i !!}"
                                    {{-- Event - Click calls select() function--}}
                                    wire:click="select({!! $result['id'] !!}); searchText='';"
                                    wire:keydown.enter="select({!! $result['id'] !!}); searchText='';"
                                    class="w-full px-4 py-2 outline-2 border-0 border-transparent focus:border-0 focus:outline-blue-500 hover:bg-gray-200 cursor-pointer rounded-0">
                                <div class="flex items-center justify-between ml-4 leading-tight">
                                    <div class="font-semibold">
                                        {{ $result['name'] }}
                                    </div>
                                    <div class="text-gray-600">
                                        {{ $result['phone_number'] }}
                                    </div>
                                </div>
                                <div class="flex items-center ml-4 leading-tight justify-between gap-2">
                                    <div class="text-left">
                                        {{ $result['address'] }}
                                    </div>
                                    <div class="text-gray-600 flex items-center gap-1 whitespace-no-wrap">
                                        <x-heroicon-m-star class="w-4 h-4 text-gray-300"/>
                                        <span>{{ $result['rating'] }}</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                    @empty
                        <li class="px-4 py-4 font-bold">{!! __('Nothing found for') !!} "{{ $search }}"</li>
                    @endforelse

                    @if(count($searchResults) == $searchResultsLimit)
                        <li class="text-center py-1 pb-8 text-blue-700">{!! __('Limited list to :qty results.', ['qty' => $searchResultsLimit]) !!}</li>
                    @endif
                </ul>
            @endif
        </div>
    @endif
</div>
