@extends('admin::layouts.logged')
@section('content')




    <section role="main" class="content-body">
        @include('admin::includes.header_content')
        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans('admin-app.fields.orden_asigl0') }} {{ trans('admin-app.title.featured_sales') }}</h1>
            </div>
            <div class="col-xs-12 text-right" style="margin-top: 2rem">
                <button class="btn btn-success" type="submit" form="order_destacadas_form">
                    {{ trans('admin-app.button.save') }}</button>
                <a href="/admin" class="btn btn-primary">{{ trans('admin-app.button.return') }}</a>
            </div>
        </div>
        <form id="order_destacadas_form" action="{{ route($parent_name.'.lotes.order_destacadas_store') }}" method="post">
                <div class="row well">
                    {{-- <div class="col-xs-12 mt-1 mb-2">
                    </div> --}}
                    <div class="col-xs-12 col-sm-6">
                        @csrf

                        <table class="table" id="sortableTable">
                            <thead>
                                <tr>
                                    <th style="border-right: 1px solid lightgray">#</th>
                                    <th>{{-- {{ trans('admin-app.fields.orden_asigl0') }} --}} Subasta</th>
                                    <th>{{ trans('admin-app.fields.reflot') }}</th>
                                    <th>{{ trans('admin-app.fields.descweb_hces1') }}</th>
                                </tr>
                            </thead>
                            <tbody class="sortable-talbe">
                                @foreach ($lots as $lot)
                                    <tr id="{{ $lot->ref_asigl0 }}">
                                        <input type="hidden" name="ref[]"
                                            value="{{$lot->sub_asigl0}}-{{$lot->ref_asigl0}}">
                                        <td class="ref-position" style="border-right: 1px solid lightgray">
                                            {{ $loop->iteration }}</td>
										<td class="text-center">{{ $lot->sub_asigl0 }}</td>
										<td class="text-center">{{ $lot->ref_asigl0 }}</td>
                                        <td>{!! $lot->descweb_hces1 !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
        </form>
    </section>

    <script>
        $(document).ready(function() {
            $(".sortable-talbe").sortable({
                //containment: "parent", //delimita el movimieno al div padre
                opacity: 0.5,
                placeholder: "sortable-placeholder",
                tolerance: "pointer",
                items: $(this).data('child'),
                over: function(event, ui) {
                    //$(ui.helper).css('border', '2px dashed red');
                    $(ui.placeholder).css('border', '2px dashed #000').css('border-radius', '10px');
                    //$(ui.item).css('border', '1px solid gray');
                },
                //Al parar
                //Inicialmente actualizaba el listado de posiciones al finalizar el movimiento, con change lo hacemos al momento
                /* stop: function (event, ui) {
                	const positions = document.querySelectorAll('.ref-position');
                	//Convertimos NodeList a array para iterar en ellos
                	[...positions].forEach( (position, iteration) => {
                		position.innerText = iteration + 1;
                	});
                }, */
                //mientras se desliza
                change: function(event, ui) {

                    const arrayPositions = $(this).sortable('toArray');
                    arrayPositions.forEach((reference, iteration) => {
                        if (reference != '') {
                            document.querySelector(`tr[id="${reference}"] .ref-position`)
                                .innerText = iteration + 1;
                        }
                    });
                }
            });
        });
    </script>
@stop
