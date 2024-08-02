<div>
    {{--
    Offline Message
    --}}
    @include('app.offline-message')

    <x-live.livewire-loading-spinner/>

    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    <div class="border-b pb-1 text-sm">{!! count($tasks) !!} {!! __('Tasks') !!}</div>
                    @if(count($tasks))
                        {{--
                        Small Screens
                        --}}
                        <div class="block qs:hidden">
                            <div x-data class="flex-column gap-1">
                                @foreach($tasks as $task)
                                    <div class="border-b pb-2 pt-2 {!! (!$task['active']?'text-gray-500':'') !!}"
                                         onclick="openOffCanvas('TaskEditor'); Livewire.dispatch('ClickedEditTask', [{!! $task['id'] !!}]);"
                                    >
                                        <div class="flex justify-between items-center">
                                            <span
                                                class="flex-grow {!! ($task['active']?'font-semibold':'') !!}">{{ $task['title'] }}</span>
                                            <div class="flex-shrink">
                                                <div class="flex items-center gap-1">
                                                    <div
                                                        {{--Prevent Up on first--}}
                                                        @if($loop->first)
                                                            class="disabled text-gray-300 cursor-not-allowed"
                                                        @else
                                                            class="ptr text-gray-700"
                                                        onclick="log('! call orderingUp ' + {!! $task['id'] !!});"
                                                        wire:click.stop="orderingUp({!! $task['id'] !!})"
                                                        @endif
                                                    >
                                                        <x-heroicon-o-chevron-up class="w-5 h-5"/>
                                                    </div>
                                                    <div
                                                        {{--Prevent Down on last--}}
                                                        @if($loop->last)
                                                            class="disabled text-gray-300 cursor-not-allowed"
                                                        @else
                                                            class="ptr text-gray-700"
                                                        onclick="log('! call orderingDown ' + {!! $task['id'] !!});"
                                                        wire:click.stop="orderingDown({!! $task['id'] !!})"
                                                        @endif
                                                    >
                                                        <x-heroicon-o-chevron-down class="w-5 h-5"/>
                                                    </div>
                                                    <span><x-heroicon-o-check-circle
                                                            class="h-5 w-5 {!! ($task['active'] ?'text-green-700':'text-red-500') !!}"/></span>
                                                </div>
                                            </div>
                                            {{--<span class="">{{ $task['vehicle_make'] }} &middot; {{ $task['vehicle_model'] }}</span>--}}
                                            {{--<span><x-heroicon-o-x-circle class="h-4 w-4 text-red-500"/></span>--}}
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
                                <th scope="col" class="no-print"></th>
                                <th scope="col" class="">{!! __('Title') !!}</th>
                                <th scope="col" class="text-center">{!! __('Order') !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                    <td class="no-print">
                                        <div class="flex gap-2 items-center">
                                            <div
                                                {{--Prevent Up on first--}}
                                                @if($loop->first)
                                                    class="disabled text-gray-300 cursor-not-allowed"
                                                @else
                                                    class="ptr text-gray-700"
                                                onclick="log('! call orderingUp ' + {!! $task['id'] !!});"
                                                wire:click="orderingUp({!! $task['id'] !!})"
                                                @endif
                                            >
                                                <x-heroicon-o-chevron-up class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            </div>
                                            <div
                                                {{--Prevent Down on last--}}
                                                @if($loop->last)
                                                    class="disabled text-gray-300 cursor-not-allowed"
                                                @else
                                                    class="ptr text-gray-700"
                                                onclick="log('! call orderingDown ' + {!! $task['id'] !!});"
                                                wire:click="orderingDown({!! $task['id'] !!})"
                                                @endif
                                            >
                                                <x-heroicon-o-chevron-down class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            </div>
                                            <div class="ptr"
                                                 onclick="log('! dispatch ClickedEditTask'); Livewire.dispatch('ClickedEditTask', [{!! $task['id'] !!}]); openOffCanvas('TaskEditor');">
                                                <x-heroicon-o-pencil-square class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700"/>
                                            </div>
                                            <div>
                                                {{--Active--}}
                                                <x-preline.tooltip :tip="__($task['active']?'Active':'Inactive')">
                                                    @if($task['active'])
                                                        <x-heroicon-o-check-circle class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 relative top-0.5"/>
                                                    @else
                                                        <x-heroicon-o-x-circle class="w-5 h-5 sm:w-6 sm:h-6 text-red-500 relative top-[1px]"/>
                                                    @endif
                                                </x-preline.tooltip>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="{!! (!$task['active']?'text-gray-500':'') !!}">{{ $task['title'] }}</td>
                                    <td class="{!! (!$task['active']?'text-gray-500':'') !!} text-center">{{ $task['ordering'] }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
