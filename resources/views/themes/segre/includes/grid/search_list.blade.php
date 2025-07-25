<div class="col-md-12 row align-items-center gx-xl-1">
    <label class="w-auto" for="reference">
        {{ trans("$theme-app.lot_list.reference") }}
    </label>
    <div class="col">
        <input class="form-control form-control-sm filter-auction-input search-input_js" id="formGridReference"
            type="text" value="{{ request('reference', '') }}"
            placeholder="1364">
    </div>
</div>

<div class="col-md-12 row align-items-center gx-xl-1">
    <label class="w-auto" for="reference">
		{{ trans("web.global.descripcion") }}
    </label>
    <div class="col">
        <input class="form-control form-control-sm filter-auction-input search-input_js" id="formGridDescription"
            type="text" value="{{ request('description', '') }}"
            placeholder="{{ trans("$theme-app.lot_list.search_placeholder") }}">
    </div>

	<button id="formGridSubmit" class="btn btn-sm btn-link w-auto">
		<x-icon.fontawesome icon="magnifying-glass" />
		{{-- <x-icon.boostrap icon="search" /> --}}
	</button>
</div>

