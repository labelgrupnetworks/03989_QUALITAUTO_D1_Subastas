{{-- https://icons.getbootstrap.com/ --}}
@php
	$size = $size ?? '16';
	$sizeX = $sizeX ?? $size;
	$sizeY = $sizeY ?? $size;
	$color = $color ?? 'currentColor';
@endphp
<svg class="bi" width="{{$sizeX}}" height="{{$sizeY}}" fill="{{$color}}">
	<use xlink:href="/bootstrap-icons.svg#{{$icon}}"></use>
</svg>
