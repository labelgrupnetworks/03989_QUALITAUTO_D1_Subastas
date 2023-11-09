@if (sizeof($info) > 0)

    <ul id="sortable{{ $info[0]->bloque }}">
        @foreach ($info as $k => $item)
            <li>
                <div class="bannerItem" id="item{{ $item->id }}">

					<div class="text-right">
                        <a title="Editar" href="javascript:editaItemBloque({{ $item->id }})"
                            class="btn btn-primary"><i class="fa fa-edit"></i></a>

                        <a title="Eliminar" href="javascript:borraItemBloque({{ $item->id }})"
                            class="btn btn-danger"><i class="fa fa-times"></i></a>

                        @if ($item->activo)
                            <a title="Desactivar" href="javascript:desactivaItemBloque({{ $item->id }})"
                                class="btn btn-success"><i class="fa fa-power-off"></i></a>
                        @else
                            <a title="Activar" href="javascript:activaItemBloque({{ $item->id }})"
                                class="btn btn-danger"><i class="fa fa-power-off"></i></a>
                        @endif
                    </div>

					@if (trim($tipo) == 'imagen' || trim($tipo) == 'imgSingle' || trim($tipo) == 'imgBlock')
                        <div class="">
                            <img src="{{ $item->imagen }}" width="100%">
                        </div>
                        <div class="">
                            {!! $item->texto !!}
                        </div>
                    @elseif(trim($tipo) == 'texto')
                        <div class="">
                            {!! $item->texto !!}
                        </div>
					@elseif(trim($tipo) == 'link' || trim($tipo) == 'iframe')
						<div class="">
							<a href="{{ $item->url }}" target="_blank" style="overflow-wrap: break-word;">{{ $item->url }}</a>
						</div>
                    @endif

                </div>

            </li>
        @endforeach
    </ul>

    <script>
        $("#sortable{{ $info[0]->bloque }}").sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            update: function(event, ui) {

                id_web_banner = "{{ $info[0]->id_web_newbanner }}";
                bloque = "{{ $info[0]->bloque }}";
                orden = new Array();
                counter = 0;

                $("#sortable{{ $info[0]->bloque }} .bannerItem").each(function() {
                    id = $(this).attr("id").replace("item", "");
                    orden[counter] = id;
                    counter = counter + 1;
                })

                const request = {
                    _token: $("#_token").val(),
                    key: $("[name=nombre]").val(),
                    orden: JSON.stringify(orden),
                    bloque,
                    id_web_banner
                }

                $.post("/admin/newbanner/ordenaBloque", request, () => saved("Modificado"));
            }
        });
    </script>
@else
    <center>No hay resultados</center>

@endif
