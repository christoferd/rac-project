<td class="border p-1 overflow-auto transition cursor-pointer duration-500 ease hover:bg-gray-300">
    {{--    xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 --}}
    <div class="flex flex-col mx-auto w-full overflow-hidden">
        {{--        xl:w-40 lg:w-30 md:w-30 sm:w-full w-10 --}}
        <div class="bottom flex-grow w-full cursor-pointer">
            <div class="event text-sm leading-4 resource-info">
                {{ $slot }}
            </div>
        </div>
    </div>
</td>
