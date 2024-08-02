<div class="mx-auto relative">
    <div
        class="fixed z-alert bottom-0 left-0 w-full h-auto
                    sm:w-xl sm:top-0 sm:bottom-auto sm:right-0 sm:left-auto
                    md:w-3/4 md:max-w-xl xl:w-xl ">
        {{-- Requires custom app settings to contain classes for each of the possible alert types --}}
        @foreach(SessionAlert::getSessionAlerts() as $sessionAlert)
            <x-alert :alert-type="$sessionAlert['type']">{!! $sessionAlert['message'] !!}</x-alert>
        @endforeach
    </div>
</div>
