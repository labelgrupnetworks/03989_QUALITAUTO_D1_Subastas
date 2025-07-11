@extends('admin::layouts.logged')

@section('content')

    <section class="content-body" role="main">

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" id="js-dropdownItems">
                        <button class="btn btn-secondary btn-sm" type="button">
                            {{ trans('admin-app.button.selecteds') }}
                        </button>
                        <button class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" data-objective="cli_ids" type="button" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="js-dropdownItems">
                            <li>
                                <button class="dropdown-item" data-objective="cli_ids" data-allselected="js-selectAll"
                                    data-title="{{ trans('admin-app.questions.erase_mass_cli') }}"
                                    data-response="{{ trans('admin-app.success.erase_mass_cli') }}"
                                    data-url="{{ route('clientes.destroy_selections') }}"
                                    data-urlwithfilters="{{ route('clientes.destroy_with_filters') }}"
                                    onclick="removeClientSelecteds(this.dataset)">
                                    {{ trans('admin-app.button.destroy') }}
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal"
                                    data-bs-target="#editMultpleClientsModal">
                                    {{ trans('admin-app.button.modify') }}
                                </button>
                            </li>
                        </ul>
                    </div>


                    <a class="btn btn-sm btn-primary" id="clientesExport"
                        href="{{ route('clientes.export') }}">{{ trans('admin-app.button.export') }}</a>

                    <a class="btn btn-sm btn-primary"
                        href="{{ route('clientes.create') }}">{{ trans('admin-app.button.new') }}
                        {{ trans('admin-app.fields.cli_creditosub') }}</a>
                </div>
                <div class="d-flex align-items-center">

                    {{-- Botón Filtros (abre modal) --}}
                    <button class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        <x-icon.boostrap icon="funnel-fill" />
                    </button>

                    {{-- Botón Columnas (abre dropdown) --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" id="colsDropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" type="button" aria-expanded="false">
                            <x-icon.boostrap icon="columns-gap" />
                        </button>
                        <ul class="dropdown-menu btn-sm dropdown-menu-end p-3" aria-labelledby="colsDropdown"
                            style="min-width:200px">
                            @foreach ($availableColumns as $field => $label)
                                <li class="form-check">
                                    <input class="form-check-input col-toggle" id="col-{{ $field }}" type="checkbox"
                                        value="{{ $field }}" @if (in_array($field, $visibleColumns)) checked @endif>

									<label for="col-{{ $field }}" @class([
                                        'form-check-label',
                                        'text-muted' => !in_array($field, $visibleColumns),
                                    ])>
                                        {{ $label }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        {{-- Tabla de Clientes --}}
        <div class="card">
            <div class="card-body">

                {{-- Tags de filtros activos --}}
                <div class="mb-3" id="filters-tags"></div>

                {{-- Aquí va tu tabla + paginación --}}
                <div id="table-container">
                    @include(
                        'admin::pages.usuario.cliente_v2.table',
                        compact('clientes', 'availableColumns', 'visibleColumns'))
                    @include('admin::pages.usuario.cliente_v2.pagination', compact('clientes'))
                </div>

            </div>
        </div>

        {{-- Modal de Filtros --}}
        <div class="modal fade" id="filterModal" aria-labelledby="filterModalLabel" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Configurar filtros</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2 align-items-end">
                            <div class="col-5">
                                <label class="form-label" for="filter-field">Campo</label>
                                <select class="form-select" id="filter-field">
                                    @foreach ($availableColumns as $f => $l)
                                        <option value="{{ $f }}">{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label" for="filter-op">Operador</label>
                                <select class="form-select" id="filter-op">
                                    <option value="contains">Contiene</option>
                                    <option value="equals">Igual a</option>
                                    <option value="starts">Empieza por</option>
                                </select>
                            </div>
                            <div class="col-9">
                                <label class="form-label" for="filter-val">Valor</label>
                                <input class="form-control" id="filter-val" type="text">
                            </div>
                            <div class="col-3 text-end">
                                <button class="btn btn-primary mt-3 w-100" id="add-filter">
                                    <x-icon.boostrap icon="plus-lg" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    @include('admin::pages.usuario.cliente_v2._edit_selecteds')


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const availableColumns = @json($availableColumns);
                let filters = [];
                let columns = @json($visibleColumns);

                const tagsDiv = document.getElementById('filters-tags');
                const container = document.getElementById('table-container');

                function renderTags() {
                    tagsDiv.innerHTML = '';
                    filters.forEach((f, i) => {
                        const span = document.createElement('span');
                        span.className = 'badge bg-secondary me-1 mb-1';
                        const opTxt = {
                            contains: 'contiene',
                            equals: '=',
                            starts: 'empieza por'
                        } [f.operator];
                        span.innerHTML = `
        ${availableColumns[f.field]} ${opTxt} “${f.value}”
        <button type="button" class="btn-close btn-close-white btn-sm ms-1"></button>
      `;
                        span.querySelector('button')
                            .addEventListener('click', () => {
                                filters.splice(i, 1);
                                renderTags();
                                fetchData();
                            });
                        tagsDiv.appendChild(span);
                    });
                }

                function fetchData(page = null) {
                    const params = new URLSearchParams();
                    params.set('filters', JSON.stringify(filters));
                    params.set('columns', JSON.stringify(columns));
                    if (page) params.set('page', page);

                    fetch("{{ route('admin.clientes.data') }}?" + params, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(json => {
                            container.innerHTML = json.table + json.pagination;
                            bindPagination();
                        });
                }

                // Añadir filtro
                document.getElementById('add-filter').addEventListener('click', () => {
                    const f = {
                        field: document.getElementById('filter-field').value,
                        operator: document.getElementById('filter-op').value,
                        value: document.getElementById('filter-val').value.trim(),
                    };
                    if (!f.value) return;
                    filters.push(f);
                    document.getElementById('filter-val').value = '';
                    renderTags();
                    fetchData();
                    // opcional: cerrar modal
                    var filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
                    filterModal.hide();
                });

                // Toggle columnas desde el dropdown
                document.querySelectorAll('.col-toggle').forEach(chk => {
                    chk.addEventListener('change', e => {
                        const col = e.target.value;
                        if (e.target.checked) {
                            if (!columns.includes(col)) columns.push(col);
                            e.target.nextElementSibling.classList.remove('text-muted');
                        } else {
                            columns = columns.filter(c => c !== col);
                            e.target.nextElementSibling.classList.add('text-muted');
                        }
                        fetchData();
                    });
                });

                // Paginación AJAX
                function bindPagination() {
                    container.querySelectorAll('.pagination a').forEach(a => {
                        a.addEventListener('click', ev => {
                            ev.preventDefault();
                            const url = new URL(a.href);
                            fetchData(url.searchParams.get('page'));
                        });
                    });
                }
                bindPagination();
            });
        </script>
    @endpush

@stop
