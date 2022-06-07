@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')
        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans('admin-app.button.sort') }} {{ trans('admin-app.title.articles') }}</h1>
            </div>
            <div class="col-xs-12 text-right" style="margin-top: 2rem">
                <a href="{{ route('articles.index') }}"
                    class="btn btn-primary">{{ trans('admin-app.button.return') }}</a>
            </div>
        </div>

        <div class="row well">

            @foreach ($sections as $section)
                <form id="order_articles_{{ $section->cod_sec }}" action="{{ route('articles.order_store') }}"
                    method="post">
                    @csrf
                    <div class="col-xs-12 col-sm-9">
                        <h3>{{ $section->des_sec }}</h3>
                        <table class="table" id="sortableTable">

                            <tr>
                                <th style="border-right: 1px solid lightgray" style="width: 30px;" >#</th>
                                <th class="text-center" style="width: 100px;" >{{ trans('admin-app.title.image') }}</th>
                                <th class="text-center" style="width: 100px;" >{{ trans('admin-app.fields.articles.id_art0') }}</th>
                                <th>{{ trans('admin-app.fields.articles.des_art0') }}</th>
                            </tr>
                            </thead>
                            <tbody class="sortable-talbe">
                                @foreach ($section->articles as $article)
                                    <tr id="{{ $article->id_art0 }}-{{ $article->sec_art0 }}">
                                        <input type="hidden" name="sec" value="{{ $section->cod_sec }}">
                                        <input type="hidden" name="ref[]"
                                            value="{{ $article->id_art0 }}-{{ $article->sec_art0 }}">
                                        <td class="ref-position" style="border-right: 1px solid lightgray; width: 30px;">
                                            {{ $loop->iteration }}</td>
                                        <td class="text-center" style="width: 100px;"><img src="/articulos/{{ $article->id_art0 }}.jpg"
                                                alt="{{ $article->id_art0 }}" style="height: 50px"></td>
                                        <td class="text-center" style="width: 100px;">{{ $article->id_art0 }}</td>
                                        <td>{!! $article->des_art0 !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="hidden-xs col-sm-3">
                    </div>
                </form>
            @endforeach

        </div>

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
                stop: function(event, ui) {
					// Modifica los índices de la tabla para que se ordenen al finalizar el movimiento
					const arrayPositions = $(this).sortable('toArray');
                    arrayPositions.forEach((reference, iteration) => {
                        if (reference != '') {
                            document.querySelector(`tr[id="${reference}"] .ref-position`)
                                .innerText = iteration + 1;
                        }
                    });
					// Envía el formulario con los nuevos índices para que se ordene
                    $.ajax({
                        url: '{{ route('articles.order_store') }}',
                        type: 'POST',
						data: $(this.closest('form')).serialize(),
                        success: function(data) {
							saved("Guardado");
                        },
                        error: function(data) {
                            error("No se pudo guardar");
                        }
                    });
                },
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
