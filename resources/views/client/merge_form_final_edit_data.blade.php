<x-app-layout>

    {{-- Slot for app layout --}}
    <x-slot name="pageTitle">
        {!! __t('Merge', 'Clients') !!}
    </x-slot>

    <div x-data="{
    setFormFieldValue: function(inputRef,valRef) {
        console.log('inputRef='+inputRef);
        console.log('valRef='+valRef);
        if(inputRef.includes('textarea')) {
            console.log('inputRef is a textarea');
        }
        else {
            // assume form input
            console.log('inputRef assumed to be input');
        }
        // Chris D. 24-Dec-2023 - this works for both TEXTAREA and INPUT
        $refs[inputRef].value = $refs[valRef].innerHTML;
    }}"
         x-init="console.log('x-init runs before JS DOM Ready!')"
         class="relative overflow-x-auto max-w-4xl mx-auto">

        <div class="mx-auto">
            @include('client.merge_breadcrumbs', ['step'=>2])
        </div>

        <form action="{!! route('merge-run') !!}" method="post" class="border-t p-8 pb-40">
            @csrf
            <div id="sticky-banner" tabindex="-1"
                 class="fixed top-6 right-40 z-50 flex justify-between w-0 p-0 m-0 border-none bg-transparent">
                <div class="flex-col items-center">
                    <x-button-submit type="submit"
                                     wire:loading.attr="disabled">
                        <div class="flex items-center gap-2">
                            <span>{!! __('Merge') !!}</span>
                            <x-heroicon-m-chevron-right class="w-4 h-4"/>
                        </div>
                    </x-button-submit>
                </div>
            </div>

            <table class="mx-auto w-full max-w-260">
                <thead>
                <tr>
                    <th class="text-left w-1/2">
                        {!! __t('Existing Data') !!}
                    </th>
                    <th class="text-left w-1/2">
                        {!! __t('New Data') !!}
                    </th>
                </tr>
                </thead>
                <tbody>
                {{-- To make things much easier, let's just display all clients, regardless of the quantity. // Chris D. 24-Dec-2023 --}}
                @foreach($fields as $fieldName)
                    @php
                        $xRefInput = 'input_'.$fieldName;
                        if($fieldName === 'notes') {
                            $xRefInput = 'input_textarea_'.$fieldName;
                        }
                    @endphp
                    <tr>
                        {{--
                            Original Data
                        --}}
                        <td class="text-left !whitespace-normal">
                            <div class="mt-2.5 mb-2 font-bold">{!! __(Client::label($fieldName)) !!}</div>
                            @foreach($records as $i => $record)
                                <div class="flex items-center gap-2">
                                    @if(!empty($record[$fieldName]))
                                        <div x-ref="{!! "recVal_{$fieldName}_{$i}" !!}">{{ trim($record[$fieldName]) }}</div>
                                        <div>
                                            {{--Allow copying one of these with push of a button--}}
                                            <button type="button" class="px-2 py-1 border border-gray-400 rounded"
                                                    x-on:click.prevent="setFormFieldValue('{!! $xRefInput !!}', '{!! "recVal_{$fieldName}_{$i}" !!}')">
                                                <x-heroicon-m-chevron-right class="w-4 h-4"/>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        {{--
                            New Data
                        --}}
                        {{--Allow editing the final field data--}}
                        <td class="text-left !whitespace-normal">
                            <div class="my-2.5">
                                <label class="font-bold">{!! __(Client::label($fieldName)) !!}</label>
                            </div>
                            <div>
                                @if($fieldName === 'notes')
                                    <textarea name="notes" x-ref="input_textarea_notes"
                                              class="w-full bg-white rounded-md border hover:border-gray-500 border-gray-200 placeholder:text-gray-400 text-base px-4 py-2"
                                              onkeyup="this.autoResizeHeight()"
                                              onclick="this.autoResizeHeight()"
                                    >{{ request()->old($fieldName, $records[0][$fieldName]) }}</textarea>
                                @else
                                    <input type="text" x-ref="input_{!! $fieldName !!}" name="{!! $fieldName !!}"
                                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                           value="{{ request()->old($fieldName, $records[0][$fieldName]) }}"/>
                                @endif
                            </div>
                            @error($fieldName)
                            <p class="text-red-600 text-md pt-1">
                                {!! $message !!}
                            </p>
                            @enderror
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    </div>

</x-app-layout>
