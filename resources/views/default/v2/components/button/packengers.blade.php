@props(['codSub' => '', 'refAsigl0' => '', 'source' => 'estimate'])

@php
    $lotFotURL = "{$codSub}-{$refAsigl0}";
	$urlToPackengers = Config::get('app.urlToPackengers');
    $urlCompletePackengers = "$urlToPackengers/{$lotFotURL}?source=$source";
@endphp

<a class="d-block btn btn-outline-lb-secondary" href="{{ $urlCompletePackengers }}" target="_blank">
	<x-icon.boostrap icon="truck" />
    {{ trans('web.lot.packengers_ficha') }}
</a>
