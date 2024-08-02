@props(['label' => ''])
<div>
    @notEmpty($label)
         <label>{{ $label }}</label>
    @endNotEmpty
    <input type="text" {{ $attributes }} />
</div>
