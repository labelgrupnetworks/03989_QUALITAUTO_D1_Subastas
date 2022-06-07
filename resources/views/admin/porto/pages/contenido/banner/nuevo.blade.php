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


	<div id="banners">
		<form method="post" id="nuevoBanner" action="/admin/newbanner/nuevo_run">
			<h1>Nuevo banner</h1>
			<br>
			<div class="row">
				<div class="col-xs-12 col-md-3">
					{!! $token !!}
					<label>Key:</label>
					{!! $nombre !!}
					{!! $tipo !!}
				</div>
			</div>

			<br>

			<label>Tipo:</label>
			<div class="row">
			@foreach ($tipos as $item)
				
				<div class="col-xs-12 col-sm-3">
					<div class="well text-center" onclick="javascript:comprueba_tipo({{$item->id}});">
						<img src='/themes_admin/porto/assets/img/tipo{{$item->id}}.jpg' width="50%">
						<br><br>
						{{ $item->nombre }}
					</div>
				</div>

			@endforeach
			</div>

			<br>



		</form>


	</div>	

<script>

	function comprueba_tipo(tipo) {

		a = document.getElementById('texto__1__nombre');

		if (comprueba_campo(a)) {

			$("#tipo_banner").val(tipo);
			$("#nuevoBanner").submit();
		}


	}


</script>


@stop
