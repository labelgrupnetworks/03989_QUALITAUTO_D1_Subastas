@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
        <header class="page-header">
                <div class="right-wrapper pull-right">
                        <ol class="breadcrumbs">
                                <li>
                                        <a href="/admin">
                                                <i class="fa fa-home"></i>
                                        </a>
                                </li>

                        </ol>

                        <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
                </div>
        </header>


	<div id="newbanner">
		@if($pos = strpos(mb_strtoupper(Session::get('user.usrw')), '@LABELGRUP'))
		<a href="/admin/newbanner/nuevo?ubicacion={{$ubicacion}}" class="btn btn-primary" style="float:right">Nuevo</a>
		@endif
		<h1>Banners {{$ubicacion}}</h1>

		@if (!empty($ubicaciones))

		<form action="" name="form_ubicacion">
			<select name="ubicacion">
				<option value="">Todos</option>
				@foreach ($ubicaciones as $key => $value)

				@if (empty($value))
					<option value="{{$value}}" @if(request('ubicacion') == '0') selected @endif>POR DEFECTO</option>
				@else
					<option value="{{$value}}" @if(request('ubicacion') == $value) selected @endif>{{$value}}</option>
				@endif

				@endforeach

			</select>
		</form>


		@endif

		<br>


		<div class="row ">
			<div class="col-12 col-md-1 text-center">
				<b>ID</b>
			</div>
			<div class="col-12 col-md-1 text-center">
				<b>IMG</b>
			</div>
			<div class="col-12 col-md-1 text-center">
				<b>DISEÑO</b>
			</div>
			<div class="col-12 col-md-2">
				<b>UBICACION</b>
			</div>
			<div class="col-12 col-md-2">
				<b>KEY</b>
			</div>
			<div class="col-12 col-md-3">
				<b>DESCRIPCIÓN</b>
			</div>
			<div class="col-12 col-md-2 text-right">
				<b>OPCIONES</b>
			</div>
		</div>
		<input type="hidden" id = "ubicacion" value="{{$ubicacion}}">
		<div class="@if(!empty($ubicacion)) sortableBanner @endif">
			@foreach($banners as $item)

			<div class="row items" id="{{ $item->id }}">
				<div class="col-12 col-md-1 text-center">
					{{ $item->id }}
				</div>
				<div class="col-12 col-md-1 text-center">
					@if(!empty($images[$item->id]))
						<img src="{{$images[$item->id]}}" width="100%">
					@endif
				</div>
				<div class="col-12 col-md-1 text-center">
					<img src="/themes_admin/porto/assets/img/tipo{{$item->id_web_newbanner_tipo}}.jpg" width="100%">
				</div>
				<div class="col-12 col-md-2">
					{{ $item->ubicacion }}
				</div>
				<div class="col-12 col-md-2">
					{{ $item->key }}
				</div>
				<div class="col-12 col-md-3">
					{{ $item->descripcion }}
				</div>
				<div class="col-12 col-md-2" style="display: inline-flex; justify-content: flex-end; flex-wrap: wrap; gap: 5px">
						<a
						@if ($item->activo)
							title="Desactivar"
							estado="on"
							class="btn btn-success jsChangeStatus"
						@else
							title="Activar"
							estado="off"
							class="btn btn-danger jsChangeStatus"
						@endif
							id="{{ $item->id }}">
								<i class="fa fa-power-off"></i>
						</a>
					<a href="/admin/newbanner/editar/{{ $item->id }}?ubicacion={{$ubicacion}}" class="btn btn-primary">Editar</a>

					@if($pos = strpos(mb_strtoupper(Session::get('user.usrw')), '@LABELGRUP'))
					<a href="/admin/newbanner/borrar/{{ $item->id }}" class="btn btn-danger">Eliminar</a>
					@endif
				</div>
			</div>

			@endforeach
		</div>
	</div>


	<script>
		window.onload = function(){
			$('select[name=ubicacion]').on('change', function(){
				form_ubicacion.submit();
			});
		}
	</script>

@stop



