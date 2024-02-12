@php
    $previuos = $data['previous'];
    $next = $data['next'];
@endphp

<div class="d-flex align-content-center">
    @if ($previuos)
        <div class="btn-group me-auto">
            <a class="btn btn-lb-primary d-flex align-items-center" href="{{ $previuos }}">
                @include('components.boostrap_icon', ['icon' => 'chevron-left'])
            </a>
            <a class="btn btn-light" href="{{ $previuos }}">{{ trans("$theme-app.subastas.last") }}</a>
        </div>
    @endif

    @if ($next)
	<div class="btn-group ms-auto">
			<a class="btn btn-light" href="{{ $next }}">{{ trans("$theme-app.subastas.next") }}</a>
            <a class="btn btn-lb-primary d-flex align-items-center" href="{{ $next }}">
                @include('components.boostrap_icon', ['icon' => 'chevron-right'])
            </a>
        </div>
    @endif
</div>
