<x-admin::table.toolbar>
    {{ $toolbar ?? '' }}
</x-admin::table.toolbar>

<div class="card">
    <div class="card-body">
        {{-- Tags de filtros activos --}}
        <div class="mb-3" id="filters-tags"></div>

        {{-- Aquí va tu tabla + paginación --}}
        <div id="table-container">
            {{ $table ?? '' }}
        </div>
    </div>
</div>
