<div class="btn-group" id="{{ $id }}">
    <button class="btn btn-secondary btn-sm" type="button">
        {{ $label }}
    </button>
    <button class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
        data-objective="cli_ids" type="button" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>

	<ul class="dropdown-menu" aria-labelledby="{{ $id }}">
        {{ $slot }}
    </ul>
</div>
