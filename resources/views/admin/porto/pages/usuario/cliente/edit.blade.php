
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


	<h1>Clientes</h1>
	<br>

	<form name="edit" id="edit" action="/admin/cliente/edit_run" method="post" class="col-10">
		{{csrf_field()}}
		<div class="row">
			@foreach($formulario as $k => $item)

				@if ($k != 'SUBMIT' && $k != "id" && $k != 'condiciones2')
					<div class="col-xs-12 col-md-5" style="padding-bottom:15px;">
						<div class="row">
							<div class="col-xs-4 text-right">
								<label>{{ ucfirst($k)}}: </label>
							</div>
							<div class="col-xs-8">
								{!! $item !!}
							</div>
						</div>
					</div>
				@elseif ($k != "SUBMIT" && $k != 'condiciones2')
					{!! $item !!}
				@endif

			@endforeach
			<div class="col-xs-12 col-md-5">
				<div class="row">
					<div class="col-xs-4 text-right">
						{!! $formulario['condiciones2'] !!}
					</div>
					<div class="col-xs-8">
						<label>El cliente autoriza a que confirmen las referencias</label>
					</div>
				</div>
			</div>
		</div>
		<br>
		<center>{!! $formulario['SUBMIT'] !!}</center>

	</form>

@stop
