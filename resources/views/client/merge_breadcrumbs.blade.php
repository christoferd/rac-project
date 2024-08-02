<nav class="breadcrumbs flex justify-around my-6">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        <li class="inline-flex items-center {!! $step==1?'active':'' !!}">
            <a href="{!! route('merge-clients') !!}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                {!! __('Select Records') !!}
            </a>
        </li>
        <li class="{!! $step==2?'active':'' !!}">
            <div class="flex items-center gap-2">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    {!! __('Edit Data') !!}
                </span>
            </div>
        </li>
        <li class="{!! $step==3?'active':'' !!}">
            <div class="flex items-center gap-2">
                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    {!! __('Finish') !!}
                </span>
            </div>
        </li>
    </ol>
</nav>
