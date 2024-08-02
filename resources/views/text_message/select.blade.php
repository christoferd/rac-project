<x-app-layout>

    <x-slot name="pageTitle">{!! __('Messages') !!}</x-slot>

    <div class="flex justify-between">
        <div>
            <h3>{!! __('Client') !!}: {{ $client->name }}</h3>
            <h3 class="{!! $validPhoneNumber?'':'text-red-500 font-bold' !!}">{!! __('Phone') !!}: {{ $fullPhoneNumber }}</h3>
        </div>
        <button class="btn" onclick="window.close()">
            <x-heroicon-o-x-mark class="w-5 h-5"/>
            {{--style to fix centering issue--}}
            <span style="line-height: 17px">{!! __('Close') !!}</span>
        </button>
    </div>

    <div class="min-h-20 flex flex-col gap-4 mt-4">
        @if($validPhoneNumber)
            <div>
                <a href="{!! \App\Library\WhatsappLib::url($fullPhoneNumber) !!}" class="btn no-underline ptr">
                    <span>{!! __t('New', 'Message') !!}</span>
                    <x-heroicon-o-chevron-right class="w-4 h-4"/>
                </a>
            </div>

            @foreach($messages as $message)
                {{--Only show messages where all key-tags have been replaced.--}}
                @if(!str_contains($message->message_content, '{'))
                    <div class="flex flex-col gap-2 border rounded p-3">
                        <h4 class="font-bold mb-0 pb-0">{{ $message->message_title }}</h4>
                        <div class="flex flex-wrap gap-3">
                            <div class="border border-green-800 rounded-xl px-2 py-1 px-2 max-w-120 whitespace-normal text-wrap text-sm"
                            >{!! nl2br(Mews\Purifier\Facades\Purifier::clean($message->message_content)) !!}</div>
                            <div class="flex-shrink">
                                <a href="{{ $message->whatsapp_url }}" class="btn no-underline ptr">
                                    <span>Whatsapp</span>
                                    <x-heroicon-o-chevron-right class="w-4 h-4"/>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>


</x-app-layout>
