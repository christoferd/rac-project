@props(['type'=>'button','bg'=>'white','color'=>'indigo-700','size'=>24])
{{--KEEP AS type=button to avoid accidentally submitting something inside components--}}
{{--NPM: class="bg-white text-indigo-700 border-indigo-700"--}}
<button type="{!! $type !!}" {!!  $attributes->merge([
'class' => 'inline-flex gap-2 items-center px-4 py-2 border rounded-md font-medium text-xs uppercase tracking-widest outline-none
hover:outline-1 hover:outline-gray-500 active:opacity-75 focus:border-gray-900 focus:ring focus:ring-gray-300
disabled:opacity-25 transition'.(' bg-'.$bg).(' text-'.$color).(' border-'.$color)]) !!}>
    <span>{!! $slot !!}</span>
</button>
