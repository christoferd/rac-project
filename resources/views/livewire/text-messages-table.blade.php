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
                    <div class="border-b pb-1 text-sm">{!! count($textMessages) !!} {!! __('Messages') !!}</div>
                    @if(count($textMessages))
                        {{--
                        Small Screens
                        --}}
                        <div class="block qs:hidden">
                            <div x-data class="flex-column gap-1">
                                @foreach($textMessages as $textMessage)
                                    <div class="border-b pb-2 pt-2"
                                         onclick="openOffCanvas('TextMessageEditor'); Livewire.dispatch('ClickedEditTextMessage', [{!! $textMessage['id'] !!}]);"
                                    >
                                        {{--Title--}}
                                        <div>
                                            {{ $textMessage['message_title'] }}
                                        </div>
                                        {{--Notes--}}
                                        <div>
                                            {{ $textMessage['message_notes'] }}
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
                                {{--Actions--}}
                                <th scope="col" class="no-print"></th>
                                <th scope="col" class="">{!! __('Title') !!}</th>
                                <th scope="col" class="">{!! __('Notes') !!}</th>
                                <th scope="col" class="">{!! __('Message') !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($textMessages as $textMessage)
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                    {{--Actions--}}
                                    <td class="no-print">
                                        <div class="flex gap-2 items-center">
                                            {{--Edit Button--}}
                                            <div class="ptr"
                                                 onclick="log('! dispatch ClickedEditTextMessage'); Livewire.dispatch('ClickedEditTextMessage', [{!! $textMessage['id'] !!}]); openOffCanvas('TextMessageEditor');">
                                                <x-heroicon-o-pencil-square class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $textMessage['message_title'] }}</td>
                                    <td class="text-wrap whitespace-normal">{{ $textMessage['message_notes'] }}</td>
                                    <td>
                                        <div class="border border-green-800 rounded-xl px-2 py-3 max-w-120 whitespace-normal text-wrap"
                                        >{!! nl2br(Mews\Purifier\Facades\Purifier::clean($textMessage['message_content'])) !!}</div>
                                    </td>
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
