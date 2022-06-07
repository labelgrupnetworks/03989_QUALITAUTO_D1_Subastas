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

			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>

	@if (!empty($reference))
	<h1>Sesión {{ $reference }} - Subasta {{$subasta}}</h1>
	@else
	<h1>Nueva sesión - Subasta {{$subasta}}</h1>
	@endif

	<a href="/admin/subasta/edit/{{$subasta}}" class="btn btn-primary right">Volver</a>

	<br><br>

	<form name="edit" id="edit" action="/admin/sesion/update" method="post" class="col-xs-12"
		enctype="multipart/form-data">
		@csrf

		<div class="well row">
			<h4>General</h4>
			<div class="col-xs-12">

				<div class="row">
					<div class="col-xs-12 col-md-6" style="padding-bottom:16px;">
						<div class="row">
							<div class="col-xs-2 col-xs-offset-2" style="padding-top:10px;">
								<img style="margin: auto" src="{{\Tools::url_img_session('subasta_medium',$subasta,$reference)}}"
								alt="" class="img-responsive" />
							</div>
						</div>
					</div>
					@foreach($formulario as $k => $item)

					@if ($k != 'SUBMIT' && $k != "id" && $k != "lin" && $k != "num")
					<div class="col-xs-12 col-md-6" style="padding-bottom:16px;">
						<div class="row">
							<div class="col-xs-4 text-right" style="padding-top:10px;">
								<label>{{ ucfirst($k)}}: </label>
							</div>
							<div class="col-xs-8">
								{!! $item !!}
							</div>
						</div>
					</div>
					@elseif ($k != "SUBMIT")
					{!! $item !!}
					@endif

					@endforeach

				</div>

				<div class="row">
					<div class="col-12">
						<h4>Textos</h4>
						<table class="table table-bordered table-condensed input-table">
							<thead>
								<tr>
									<td align="center">Idioma</td>
									<td align="center">Nombre</td>
									<td align="center">Descripción</td>
								</tr>
							</thead>
							<tbody>
								@foreach(\Config::get('app.locales') as $lang => $name)
								<tr>
									<td>{!! ucfirst($name) !!}</td>

									@foreach($formularioTextos['es'] as $k => $info)
									<td>{!! $formularioTextos[$lang][$k] !!}</td>
									@endforeach
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<br><br>
				<center>{!! $formulario['SUBMIT'] !!}</center>
				<br>

			</div>
		</div>


	</form>
	<div class="clearfix"></div>

	@if (!empty($reference))
	<div class="row">
		<div class="col-xs-12">
			<form name="subirfichero" action="/admin/sesion/addfile" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="reference" value="{{$reference}}">
				<input type="hidden" name="auction" value="{{$subasta}}">
				<div class="well row">
					<h4>Ficheros</h4>
					@if(session('errors'))
					@foreach ($errors as $error)
					<div class="alert alert-danger" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<strong>{{ $error }}</strong>
					</div>
					@endforeach
					@endif
					@if(session('success'))
					<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
								aria-hidden="true">&times;</span></button>
						<strong>{{ session('success')[0] }}</strong>
					</div>
					@endif

					<br>
					<div class="col-xs-12 col-md-7">
						@csrf

						<div class="row">
							<div class="col-xs-12 col-md-7">
								<b>Selecciona fichero</b>
								{!! $formFile['file'] !!}
							</div>

							<div class="col-xs-12 col-md-5">
								<b>Tipo de fichero</b>
								<br>
								{!! $formFile['typeFile'] !!}
							</div>

						</div>
						<br>
						<div class="row">

							<div class="col-xs-12 col-md-3">
								<b>Orden</b>
								<br>
								{!! $formFile['order'] !!}
							</div>

							<div class="col-xs-12 col-md-5">
								<b>Descripción</b>
								{!! $formFile['description'] !!}
							</div>

							<div class="col-xs-12 col-md-4">
								<b>Idioma</b>
								<br>
								{!! $formFile['langFile'] !!}
							</div>

						</div>
						<br>

						<b>Selecciona imagén para el fichero</b>
						{!! $formFile['img'] !!}
						<br>

						<b>Url de redirección</b>
						{!! $formFile['url'] !!}
						<br>
						<input type="submit" class="btn btn-primary">
					</form>

					</div>


					<div class="col-xs-12 col-md-5">
						<b>Ficheros asignados:</b>
						<table class="table table-striped table-files">
							<tr>
								<th>Tipo</th>
								<th>Nombre</th>
								<th>Acción</th>
							</tr>
							@foreach ($formFile['files'] as $item)
							<tr>
								<form method="POST" action="/admin/sesion/deletefile">
									@csrf
									<input type="hidden" name="auction" value="{{$item->auction}}">
									<input type="hidden" name="reference" value="{{$item->reference}}">
									<input type="hidden" name="idFile" value="{{$item->id}}">
									<td style="width: 10%">
										@if (!empty($item->type))
										<img src="{{ $formFile['icons'][$item->type] }}" width="100%">
										@endif
									</td>
									<td><a style="text-decoration: none;" title="{{ $item->description }}"
											target="_blank" href="/files{{ $item->path }}">{{ $item->description }}</a>
									</td>
									<td><button type="submit" class="btn btn-danger"><b>X</b></button></td>
								</form>
							</tr>
							@endforeach

						</table>
					</div>

				</div>

		</div>
	</div>
	@endif

</section>


@stop
