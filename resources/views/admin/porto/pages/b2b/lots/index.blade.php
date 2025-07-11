@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        <section id="loader-page">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </section>


        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans('admin-app.title.lot') }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
                {{-- configuracion de tabla --}}
                <a class="btn btn-primary btn-sm" href="{{ route('admin.b2b.lots.create') }}">
                    {{ trans('admin-app.button.new') }}
                    {{ trans('admin-app.title.lot') }}
                </a>

                <a class="btn btn-success btn-sm" href="/themes/b2b/assets/files/plantillaejemplo.xlsx"
                    download="plantilla.xlsx">
                    {{ trans('admin-app.button.download_excel_template') }}
                </a>

                <a class="btn btn-info btn-sm" class="btn btn-success btn-sm"
                    href="{{ route('admin.lote.getimport', ['id' => $auction->cod_sub]) }}">
                    {{ trans('admin-app.button.upload_excel') }}
                </a>


                @include('admin::includes.config_table_v2', [
                    'id' => 'b2b_lots_table',
                    'params' => $tableParams,
                    'formulario' => $formulario,
                ])
            </div>


            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-condensed table-align-middle" id="b2b_lots_table"
                    data-order-name="order" style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($tableParams as $field => $display)
                                @include('admin::components.tables.sortable_column_header', [
                                    'field' => $field,
                                ])
                            @endforeach
                            <th>
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($lots as $lot)

                            <tr id="{{ $lot->ref_asigl0 }}">

                                @foreach ($tableParams as $field => $display)
                                    <td class="{{ $field }}"
                                        @if (!$tableParams[$field]) style="display: none" @endif>
                                        @if ($field == 'img_lot')
                                            <img class="object-fit-contain"
                                                src="{{ Tools::url_img('lote_small', $lot->numhces_asigl0, $lot->linhces_asigl0) }}"
                                                style="width: 50px; height: 50px;">
                                        @elseif(in_array($field, ['impsalhces_asigl0', 'impres_asigl0', 'max_puja', 'max_orden']))
                                            {{ Tools::moneyFormat($lot->{$field}, '€') }}
                                        @else
                                            {{ $lot->{$field} }}
                                        @endif
                                    </td>
                                @endforeach

                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('admin.b2b.lots.edit', ['ref_asigl0' => $lot->ref_asigl0]) }}"
                                        title="{{ trans('admin-app.button.edit') }}"><i class="fa fa-pencil-square-o"
                                            aria-hidden="true"></i>{{ trans('admin-app.button.edit') }}
                                    </a>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal"
                                        data-id="{{ $lot->ref_asigl0 }}"
                                        data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lot->ref_asigl0]) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="{{ $numberOfColumns }}">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

        <div class="col-xs-12 d-flex justify-content-center">
            {{ $lots->links() }}
        </div>

        @include('admin::includes._delete_modal', [
            'routeToDelete' => route('admin.b2b.lots.destroy', ['ref_asigl0' => 0]),
        ])

        <script>
            $('#deleteModal').on('show.bs.modal', function(event) {

                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');

                //obtenemos el id del data action del form
                var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
                $('#formDelete').attr('action', action);

                var modal = $(this);
                modal.find('.modal-title').text(name);
            });
        </script>

    </section>
@stop
