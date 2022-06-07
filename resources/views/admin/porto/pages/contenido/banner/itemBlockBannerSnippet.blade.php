@if (sizeof($info)>0)
<ul id="sortable{{$info[0]->bloque}}">
	@foreach ($info as $k => $item)
	<li>
		<div class="row" id="item{{$item->id}}">
		@if (trim($tipo) == "imagen" ||  trim($tipo) == "imgSingle" ||  trim($tipo) == "imgBlock")
	    	<div class="col-xs-12 col-md-3">
		        <img src="{{$item->imagen}}" width="100%">
		    </div>
		    <div class="col-xs-12 col-md-5">
		        {!!$item->texto!!}
		    </div>

		@elseif(trim($tipo) == "texto")

		    <div class="col-xs-12 col-md-8">
		        {!!$item->texto!!}
		    </div>

		@endif


	    <div class="col-xs-12 col-md-4 text-right">
	        <a title="Editar" href="javascript:editaItemBloque({{$item->id}})" class="btn btn-primary"><i class="fa fa-edit"></i></a>
			<a title="Eliminar" href="javascript:borraItemBloque({{$item->id}})" class="btn btn-danger"><i class="fa fa-times"></i></a>
			@if ($item->activo)
				<a title="Desactivar" href="javascript:desactivaItemBloque({{$item->id}})" class="btn btn-success"><i class="fa fa-power-off"></i></a>
			@else
				<a title="Activar" href="javascript:activaItemBloque({{$item->id}})" class="btn btn-danger"><i class="fa fa-power-off"></i></a>
			@endif
	    </div>
	</div>

</li>
	@endforeach
</ul>
@else

	<center>No hay resultados</center>

@endif

<script>
	$( "#sortable{{$info[0]->bloque}}" ).sortable({
		forcePlaceholderSize: true,
		forceHelperSize: true,
		update: function( event, ui ) {

			id_web_banner = "{{$info[0]->id_web_newbanner}}";
			bloque =  "{{$info[0]->bloque}}";
			orden = new Array();
			counter = 0;
			$( "#sortable{{$info[0]->bloque}} .row" ).each(function() {
				id = $(this).attr("id").replace("item","");
				orden[counter] = id;
				counter = counter+1;
			})

			token = $("#_token").val();
		    $.post( "/admin/newbanner/ordenaBloque", {bloque:bloque, orden:JSON.stringify(orden),id_web_banner:id_web_banner, _token:token}, function( data ) {

		    	}

		    );

		}
	});

</script>
