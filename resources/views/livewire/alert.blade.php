<div class="mx-auto relative">
    @notEmpty($alerts)
    {{-- Requires custom app settings to contain classes for each of the possible alert types --}}
    <?php
    /**
     * @var array  $alerts
     * @var string $alertType
     * @var string $alertMessage
     */
    ?>
    <div id="LivewireAlertShell"
         {{-- sm:bottom-auto <-- what's this for // Chris D. 30-Jul-2023 --}}
         class="fixed z-alert top-0 left-1 w-full h-auto
                    sm:w-xl sm:top-0 sm:right-0 sm:left-auto
                    md:w-3/4 md:max-w-xl xl:w-xl ">
        @foreach($alerts as $alertSettings)
            <x-alert :alert-type="$alertSettings['type']">{{ $alertSettings['message'] }}</x-alert>
        @endforeach
    </div>
    @endNotEmpty
</div>
