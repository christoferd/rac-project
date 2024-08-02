@props(['selected' => '0'])
<select {{ $attributes->whereDoesntStartWith('selected')->merge(['class' => 'font-mono']) }} style="min-width: 100px;">
    <option value="0">Hora</option>
    @foreach($timeSlots as $ts)
        <option value="{!! $ts['value'] !!}" {!! ($selected===$ts['value']?'selected':'') !!}>{!! $ts['label'] !!}</option>
    @endforeach
</select>
