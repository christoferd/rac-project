@props(['alertType'=>'', 'timeoutMs' => true, 'title' => null])
@if($alertType !== '')
<div x-data="{ showAlert: true }" x-cloak x-show="showAlert"
     @if($timeoutMs === true)
         x-init="$nextTick(() => { setTimeout(function() { showAlert = false; } , {{ config('mindflow.alert.timeout_ms', 3000) }}) })"
     @endif
     @click.stop="showAlert = false"
     class="alert-{!! $alertType !!} bg-white rounded p-4 mt-2 mr-2 flex items-center shadow-lg h-auto border-gray-200 border cursor-pointer transition"
     style="opacity: {{ config('mindflow.alert.opacity', '0.8') }}">

    @if($alertType === 'error')
        <div class="bg-red-200 mr-4 rounded-full p-2">
            <div class="bg-red-100 text-red-700 rounded-full">
                <i data-feather="alert-circle" class="text-sm w-6 h-6 font-semibold"></i>
            </div>
        </div>
        <div class="flex-1">
            @if($title !== '')
                <b class="text-gray-900 font-semibold">
                    @if(!is_null($title))
                        {!! __($title) !!}
                    @else
                        {!! __('Error') !!}
                    @endif
                </b>
            @endif
            <div class="text-sm pr-2">
                {!! $slot !!}
            </div>
        </div>
    @elseif($alertType === 'success')
        <div class="bg-green-200 mr-4 rounded-full p-2">
            <div class="bg-green-100 border-green-300 text-green-700 rounded-full p-1 border-2">
                <i data-feather="check" class="text-sm w-4 h-4 font-semibold"></i>
            </div>
        </div>
        <div class="flex-1">
            @if($title !== '')
                <b class="text-gray-900 font-semibold">
                    @if(!is_null($title))
                        {!! __($title) !!}
                    @else
                        {!! __('Success') !!}
                    @endif
                </b>
            @endif
            <div class="text-sm pr-2">
                {!! $slot !!}
            </div>
        </div>
    @elseif($alertType === 'warning')
        <div class="bg-orange-200 mr-4 rounded-full p-2">
            <div class="bg-orange-100 border-orange-300 text-yellow-500 rounded-full p-1 border-2">
                <i data-feather="alert-triangle" class="text-sm w-4 h-4 font-semibold"></i>
            </div>
        </div>
        <div class="flex-1">
            @if($title !== '')
                <b class="text-gray-900 font-semibold">
                    @if(!is_null($title))
                        {!! __($title) !!}
                    @else
                        {!! __('Warning') !!}
                    @endif
                </b>
            @endif
            <div class="text-sm pr-2">
                {!! $slot !!}
            </div>
        </div>
    @elseif($alertType === 'info')
        <span class="text-indigo-100 text-indigo-200 text-indigo-300 text-indigo-400 text-indigo-500"></span>
        <span class="bg-indigo-100 bg-indigo-200 bg-indigo-300 bg-indigo-400 bg-indigo-500"></span>
        <div class="bg-indigo-300 mr-4 rounded-full p-2">
            <div class="bg-indigo-100 text-indigo-500 rounded-full">
                <i data-feather="info" class="text-sm w-6 h-6 font-semibold"></i>
            </div>
        </div>
        <div class="flex-1">
            {{--                                <b class="text-gray-900 font-semibold">--}}
            {{--                                    {!! __('Note') !!}--}}
            {{--                                </b>--}}
            <div class="text-sm pr-2">
                {!! $slot !!}
            </div>
        </div>
{{--    @else --}}
{{--    // Chris D. 6-Feb-2024 REMOVED because this error is appearing at weird times --}}
{{--        <div class="border border-2 border-red-800 p-3 text-red-700 font-bold text-base">--}}
{{--            $alertType not supported: {{ json_encode($alertType) }}--}}
{{--        </div>--}}
    @endif

    <button class="text-gray-400 hover:text-gray-900 transition duration-300 ease-in-out cursor-pointer pl-1">
        <i data-feather="x-circle"></i>
    </button>
</div>
@endif
