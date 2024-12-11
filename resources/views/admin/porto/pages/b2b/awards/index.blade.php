@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        <section id="loader-page">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </section>

        @include('admin::includes.header_content')

        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Adjudicaciones</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
                @include('admin::includes.config_table_v2', [
                    'id' => 'b2b_bids_table',
                    'params' => $tableParams,
                    'formulario' => $formulario,
                ])
            </div>

            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-condensed table-align-middle" id="b2b_bids_table"
                    data-order-name="order" style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($tableParams as $field => $display)
                                @include('admin::components.tables.sortable_column_header', [
                                    'field' => $field,
									'display' => $display,
                                ])
                            @endforeach
                            <th>
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($awards as $award)

                            <tr>
                                @foreach ($tableParams as $field => $display)
                                    <td class="{{ $field }}" @style([
                                        'display' => $tableParams[$field] ? 'table-cell' : 'none',
                                    ])>
                                        {{ $award->{$field} }}
                                    </td>
                                @endforeach

                                <td>
                                    {{-- <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal"
                                        data-id="{{ $lot->ref_asigl0 }}"
                                        data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.lot'), 'id' => $lot->ref_asigl0]) }}">
                                        <i class="fa fa-trash"></i>
                                    </button> --}}
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
            {{ $awards->links() }}
        </div>

        {{-- @include('admin::includes._delete_modal', [
            'routeToDelete' => route('admin.b2b.lots.destroy', ['ref_asigl0' => 0]),
        ]) --}}

        {{-- <script>
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
        </script> --}}

    </section>
@stop
