@props(['size' => '1em', 'sizeX', 'sizeY', 'color' => 'currentColor', 'icon', 'type' => 'solid', 'version' => '6'])

@php
    $sizeX ??= $size;
    $sizeY ??= $size;

    $versions = [
        '5' => '5.15.14',
        '6' => '6.5.2',
    ];
@endphp

<svg class="lb-icon" width="{{ $sizeX }}" height="{{ $sizeY }}" fill="{{ $color }}">
    <use href="/vendor/font-awesome/{{ $versions[$version] }}/sprites/{{ $type }}.svg#{{ $icon }}"
        xlink:href="/vendor/font-awesome/{{ $versions[$version] }}/sprites/{{ $type }}.svg#{{ $icon }}">
    </use>
</svg>
