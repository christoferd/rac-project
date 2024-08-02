<select {!! $attributes !!}>
    @foreach(\App\Models\Client::$selectOptions['rating'] as $val => $label)
    <option value="{!! $val !!}">{{ $label }}</option>
    @endforeach
</select>
