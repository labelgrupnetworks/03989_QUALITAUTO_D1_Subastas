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
							data-id="{{ $item->id }}" data-key="{{ $item->key }}">
								<i class="fa fa-power-off"></i>
						</a>
					<a href="/admin/newbanner/editar/{{ $item->id }}?ubicacion={{$ubicacion}}" class="btn btn-primary">Editar</a>

					@if($pos = strpos(mb_strtoupper(Session::get('user.usrw')), '@LABELGRUP'))
					{{-- <a href="/admin/newbanner/borrar/{{ $item->id }}" class="btn btn-danger">Eliminar</a> --}}
					<button class="btn btn-danger" data-toggle="modal" data-target="#deleteBannerModal"
						data-id="{{ $item->id }}"
						data-name="{{ trans('admin-app.title.delete_resource', ['resource' => trans('admin-app.title.banner'), 'id' => $item->id]) }}">
						{{ trans('admin-app.title.delete') }}
					</button>
					@endif
				</div>
			</div>

			@endforeach
		</div>
	</div>

	@include('admin::includes._delete_banner_modal', ['routeToDelete' => "/admin/newbanner/borrar/",])

	<script>
		window.onload = function(){
			$('select[name=ubicacion]').on('change', function(){
				form_ubicacion.submit();
			});
		}

		$('#deleteBannerModal').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var name = button.data('name');
			var url = '/admin/newbanner/borrar/' + id;


			//obtenemos el id del data action del form
			var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
			$('#formDelete').attr('action', action);

			// actualizamos en enlace del formulario #formDelete por la variable url
			$('#submitDeleteBanner').attr('href', url);

			var modal = $(this);
			modal.find('.modal-title').text(name);
		});
	</script>

@stop



