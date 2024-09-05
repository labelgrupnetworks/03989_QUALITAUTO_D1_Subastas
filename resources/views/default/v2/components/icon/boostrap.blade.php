@props(['size' => '1em', 'sizeX', 'sizeY', 'color' => 'currentColor', 'icon'])

@php
	$sizeX ??= $size;
	$sizeY ??= $size;
@endphp

<svg class="bi lb-icon" width="{{$sizeX}}" height="{{$sizeY}}" fill="{{$color}}">
	<use xlink:href="/vendor/bootstrap/5.2.0/icons/bootstrap-icons.svg#{{$icon}}" href="/vendor/bootstrap/5.2.0/icons/bootstrap-icons.svg#{{$icon}}"></use>
</svg>
