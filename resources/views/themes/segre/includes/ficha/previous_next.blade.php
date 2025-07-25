@php
    $previuos = $data['previous'];
    $next = $data['next'];
@endphp

<div class="d-flex gap-5 text-uppercase">
    @if ($previuos)
	<a href="{{ $previuos }}">
		<span><</span>
		{{ trans("$theme-app.subastas.last") }}
	</a>
    @endif

    @if ($next)
	<a href="{{ $next }}">
		{{ trans("$theme-app.subastas.next") }}
		<span>></span>
	</a>
	@endif
</div>
