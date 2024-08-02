@props(['id'])
<div id="{!! $id !!}" role="tabpanel" aria-labelledby="{!! $id !!}" {!! $attributes !!}>
    {!! $slot !!}
</div>
