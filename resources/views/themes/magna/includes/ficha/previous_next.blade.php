@php
    $previuos = $data['previous'];
    $next = $data['next'];
@endphp

<div class="d-flex align-content-center">
    @if ($previuos)
        <div class="me-auto">
            <a class="btn btn-link no-decoration ps-0" href="{{ $previuos }}">
                <x-icon.boostrap icon="caret-left-fill" size=".75em" />
                {{ trans("$theme-app.subastas.last") }}
            </a>
        </div>
    @endif

    @if ($next)
        <div class="ms-auto">
			<a class="btn btn-link no-decoration pe-0" href="{{ $next }}">
				{{ trans("$theme-app.subastas.next") }}
                <x-icon.boostrap icon="caret-right-fill" size=".75em" />
            </a>
        </div>
    @endif
</div>
