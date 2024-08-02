@props(['for' => '', 'name' => ''])
@if($for !== '')
    @error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
    @enderror
@endif
@if($name !== '')
    @error($name)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
    @enderror
@endif
