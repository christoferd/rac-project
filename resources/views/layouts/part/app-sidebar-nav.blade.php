<!-- Sidebar -->
<div id="application-sidebar"
     class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden h-screen
            fixed top-0 left-0 z-side-nav w-64 bg-white border-r border-gray-200 py-4 overflow-y-auto scrollbar-y"
>
    <div class="flex flex-col w-full h-full py-2 px-4 justify-between">

        <div class="w-full">

            <!-- Logo -->
            <div class="shrink-0 flex items-center pl-3">
                <div class="w-6 h-6 sm:w-8 sm:h-8">
                    <a class="flex-none text-gray-600" href="{{ route('calendar') }}" aria-label="">
                        <x-vaadin-car class="w-6 h-6 sm:w-8 sm:h-8"/>
                    </a>
                </div>
                <span class="relative top-1 left-2 text-xl font-semibold">{!! config('app.name') !!}</span>
            </div>

            <!-- Links -->
            <nav class="w-full pt-4 flex flex-col justify-between">

                <ul class="space-y-1.5">
                    @can('access calendar')
                        <li>
                            <a class="{{ (request()->routeIs('calendar')?'active':'') }}"
                               href="{{ route('calendar') }}">
                                <div>
                                    <x-heroicon-o-calendar-days class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Calendar') !!}</div>
                            </a>
                        </li>
                    @endcan
                    @can('access rentals')
                        <li>
                            <a class="{{ (request()->routeIs('rental-history')?'active':'') }}"
                               href="{{ route('rental-history') }}">
                                <div>
                                    <x-heroicon-o-queue-list class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Rental') !!}</div>
                            </a>
                        </li>
                    @endcan
                    @can('access clients')
                        <li>
                            <a class="{{ (request()->routeIs('clients')?'active':'') }}"
                               href="{{ route('clients') }}">
                                <div>
                                    <x-heroicon-o-user class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Clients') !!}</div>
                            </a>
                        </li>
                    @endcan
                    @can('access vehicles')
                        <li>
                            <a class="{{ (request()->routeIs('vehicles')?'active':'') }}"
                               href="{{ route('vehicles') }}">
                                <div>
                                    <x-heroicon-o-truck class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Vehicles') !!}</div>
                            </a>
                        </li>
                    @endcan
                    @can('access tasks')
                        <li>
                            <a href="{{ route('tasks') }}">
                                <div>
                                    <x-heroicon-o-list-bullet class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Tasks') !!}</div>
                            </a>
                        </li>
                    @endcan
                    @can('access messages')
                        <li>
                            <a href="{{ route('text-messages') }}">
                                <div>
                                    <x-heroicon-o-chat-bubble-oval-left-ellipsis class="w-5 h-5 text-gray-800"/>
                                </div>
                                <div> {!! __('Messages') !!}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>
        </div>

        <nav class="w-full flex flex-col justify-between">
            <ul class="space-y-1.5">
                @if(isDeveloper())
                    <li>
                        <a href="{{ route('log-viewer.index') }}" target="_blank">
                            <div>
                                <x-heroicon-o-list-bullet class="w-5 h-5 text-gray-800"/>
                            </div>
                            <div> Logs</div>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
<!-- End Sidebar -->
