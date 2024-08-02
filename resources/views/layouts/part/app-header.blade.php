<div id="AppHeader"
     class="bg-white border-b px-4 sm:px-6">
    <div class="print-only">
        {{ $pageTitle??'?No Page Title?' }}
    </div>
    <div class="no-print flex items-center py-0 sm:py-2">

        {{-- Sidebar Toggle Button--}}
        <button type="button" class="text-gray-500 hover:text-gray-600" data-hs-overlay="#application-sidebar" aria-controls="application-sidebar"
                aria-label="Toggle navigation">
            <span class="sr-only">Toggle Navigation</span>
            <svg class="w-5 h-5" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
        </button>
        {{-- End Sidebar Toggle --}}

        {{-- Breadcrumb / Title --}}
        <div class="flex-1">
            <ol class="ml-3 flex items-center whitespace-nowrap min-w-0" aria-label="Breadcrumb">
                <li class="flex items-center text-sm text-gray-800 dark:text-gray-400">
                    <svg class="flex-shrink-0 mx-3 overflow-visible h-2.5 w-2.5 text-gray-400 dark:text-gray-600" width="16" height="16"
                         viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2"
                              stroke-linecap="round"/>
                    </svg>
                </li>
                <li class="font-semibold text-gray-800 truncate dark:text-gray-400" aria-current="page">
                    {{ $pageTitle??'?No Page Title?' }}
                </li>
            </ol>
        </div>
        {{-- End Breadcrumb / Title --}}

        {{-- Settings Dropdown --}}
        <div id="navUser" class="ml-3 relative">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <button class="flex border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                 alt="{{ Auth::user()->name }}"/>
                        </button>
                    @else
                        <span class="inline-flex rounded-md">
                            <button type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                {{ Auth::user()->name }}

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                        </span>
                    @endif
                </x-slot>

                <x-slot name="content">
                    {{-- Account Management --}}
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-dropdown-link href="{{ route('profile.show') }}" class="text-decoration-none">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}" class="text-decoration-none">
                            {{ __('API Tokens') }}
                        </x-dropdown-link>
                    @endif

                    <div class="border-t border-gray-200"></div>

                    {{-- Authentication --}}
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</div>
