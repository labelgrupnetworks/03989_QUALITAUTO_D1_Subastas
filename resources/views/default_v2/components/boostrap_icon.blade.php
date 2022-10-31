{{-- https://icons.getbootstrap.com/ --}}
@php
	$size ??= '16';
	$sizeX ??= $size;
	$sizeY ??= $size;
	$color ??= 'currentColor';
@endphp
<svg class="bi" width="{{$sizeX}}" height="{{$sizeY}}" fill="{{$color}}">
	<use xlink:href="/bootstrap-icons.svg#{{$icon}}"></use>
</svg>
