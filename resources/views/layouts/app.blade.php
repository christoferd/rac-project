@props(['jsRefreshPageDaily'=>0])
<!DOCTYPE html>
<html lang="{!! str_replace('_', '-', app()->getLocale()) !!}">
<head>
    <meta charset="utf-8">
{{-- Orig:    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">--}}
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, user-scalable=yes, minimal-ui">

    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <title>{!! isset($pageTitle)?$pageTitle.' | ':'' !!}{!! config('app.name', 'App') !!}</title>
    <!-- scripts -->
    @vite(['resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>

    <!-- CSS Styles -->
    {{-- livewire: Manually bundling Alpine in your JavaScript build --}}
    {{-- https://livewire.laravel.com/docs/alpine#manually-bundling-alpine-in-your-javascript-build --}}
    @livewireStyles

    {{-- 8-Feb-2024 ! Removed 'resources/css/tippy-637.css' from here due to error in production "Unable to locate file in Vite manifest..."--}}

    @vite(['resources/css/app.css', 'resources/css/tippy-637.css'])

    {{--    , 'resources/css/spotlight-078.css'])--}}

    @bukStyles(true)

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900">
@include('app.disconnected-since-last-use-message')
<livewire:disconnected-message/>
{{--
Confirmation Modal
- use: openLiveConfirmModal(...)
--}}
<div id="ConfirmModal"
     class="no-print hidden w-screen sticky top-4 bg-transparent inset-x-0 z-confirm">
    <div class="mx-auto w-80 xs:w-100 qs:w-120 bg-white border rounded-lg shadow-lg p-6 ">
        <h3 class="cm-title text-rose-700">Confirmar</h3>
        <div id="ConfirmModalText" class="cm-text my-3"></div>
        <div id="ConfirmModalButtons" class="flex gap-6 items-center justify-around">
            <button type="button"
                    onclick="closeLiveConfirmModal();"
                    class="w-full max-w-20 py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-gray-300 font-semibold bg-white text-gray-700 hover:bg-gray-100 hover:shadow focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-all text-sm">
                {!! __('No') !!}
            </button>
            <button type="button"
                    class="w-full max-w-20 py-2 px-3 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-rose-700 text-white hover:bg-rose-600 hover:shadow focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition-all text-sm"
                    onclick="confirmLiveConfirmModal()">
                {!! __('Yes') !!}
            </button>
        </div>
    </div>
</div>
<div id="ConfirmModalOverlay" class="no-print modal-overlay hidden" onclick="closeLiveConfirmModal();"></div>

{{--
Messages
--}}
<div id="messages" class="w-full h-0 overflow-visible">

    <div id="SessionAlerts" class="h-0 my-0 px-4 overflow-visible fixed w-full z-alert">
        @include('alerts.session_alerts')
    </div>

    <div id="LivewireAlerts" class="h-0 my-0 px-4 overflow-visible fixed w-full z-alert">
        @livewire('alert')
    </div>

</div>

{{--
Header
--}}
<header>
    @include('layouts.part.app-header')
    @include('layouts.part.app-sidebar-nav')
</header>
{{--
Main
--}}
<main class="w-full py-3 px-4 sm:px-6 md:px-6">
    {!! $slot !!}
</main>
{{--
Footer
--}}
<footer>
    <div class="flex items-center justify-center gap-3">
        <div>
            <span class="no-print">
            {!! ((memory_get_peak_usage(true) / 1024) / 1024).'MB' !!} &middot;
            </span>
            {!! date('d/m/Y H:i:s') !!}
        </div>
        <button onclick="window.print()" class="no-print">
            <x-heroicon-o-printer class="text-gray-700 h-4 w-4"/>
        </button>
    </div>
</footer>

@stack('scripts')

@stack('modals')
<div id="ModalOverlay" class="modal-overlay hidden"></div>

{{-- Image Gallery with HammerJs --}}
<x-my-modal-top id="ImageGalleryHammer"
                shell-class="fixed w-screen h-screen max-w-4xl left-0 top-0 p-1 qs:p-2 sm:p-4 w-screen sm:left-1/2 sm:-translate-x-1/2"
                panel-class="p-2 sm:p-4 h-full overflow-hidden"
>
    <x-image-gallery my-modal-id="ImageGalleryHammer"/>
</x-my-modal-top>

<script type="text/javascript" src="/assets/js/custom.js" />

@if($jsRefreshPageDaily !== 0)
    <script type="text/javascript">
        // Refresh page next day if still open
        const cal_dateNow = new Date();
        let cal_dateNowDate = 0; // date number of month
        document.addEventListener('DOMContentLoaded', (event) => {
            cal_dateNowDate = cal_dateNow.getDate(); // number from 1 to 31
        });
        document.addEventListener('click', (event) => {
            // Check if date of month today is different
            if(cal_dateNowDate !== (new Date()).getDate()) {
                cal_dateNowDate = (new Date()).getDate();
                openLiveConfirmModal("{!! __('Reloading page...') !!}");
                setTimeout(() => {
                    console.log("Reloading page...");
                    window.location = '{!! route('calendar') !!}';
                }, 1500);
            }
        });
    </script>
@endif
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
@bukScripts(true)
@livewireScriptConfig
</body>
</html>
