@props(['tabs' => ['id'=>'label']])
<div x-data class="border-b border-gray-200 dark:border-gray-700">
    <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
        @foreach($tabs as $id => $label)
            <button type="button"
                    class="@if($loop->first) {!! 'active' !!} @endif hs-tab-active:font-semibold hs-tab-active:border-blue-600 hs-tab-active:text-blue-600 py-4 px-1 inline-flex items-center gap-2 border-b-[3px] border-transparent text-sm whitespace-nowrap text-gray-500 hover:text-blue-600"
                    id="TabNav{!! $id !!}" data-hs-tab="#{!! $id !!}" aria-controls="tabs-with-underline-1" role="tab">
                {!! __($label) !!}
            </button>
        @endforeach
    </nav>
</div>
