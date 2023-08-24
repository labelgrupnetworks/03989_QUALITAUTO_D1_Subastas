@if ($is_iframe)

    <head>
        @include('admin::includes.head')
    </head>
@endif

<div id="editbanner" class="p-1">
    <form method="post" id="editBanner" action="/admin/newbanner/nuevo_run">

        <div class="right">
            @if (request('ubicacion') == 'HOME')
                <a href="/admin/newbanner/ubicacionhome" class="btn btn-primary">Volver</a>
            @else
                <a href="/admin/newbanner" class="btn btn-primary">Volver</a>
            @endif

            &nbsp;&nbsp;&nbsp;
            <a href="javascript:vista_previa('{{ $banner->key }}')" class="btn btn-primary">Vista previa</a>
            &nbsp;&nbsp;&nbsp;
            <a href="javascript:editar_run()" class="btn btn-success">Guardar</a>
        </div>

        <h1>Editar banner</h1>
        <br>
        <div class="row">
            {!! $token !!}
            {!! $id !!}

            <div class="col-xs-12 col-md-6">
                <label>Key:</label>
                {!! $nombre !!}
            </div>

            <div class="col-xs-12 col-md-2">
                <label>Orden:</label>
                {!! $orden !!}
            </div>

            <div class="col-xs-12 col-md-2 text-center">
                <label>Activo:</label>
                {!! $activo !!}
            </div>

        </div>
        <br>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <label>Descripción:</label>
                {!! $descripcion !!}
            </div>

            <div class="col-xs-12 col-md-6">
                <label>Ubicación:</label>
                {!! $ubicacion !!}
                <small><i>Posibles ubicaciones: {{ $ubicaciones }}</i></small>
            </div>

        </div>

    </form>

    <br>
    <hr>
    <br>
    <p>*Se puede modificar el orden arrastrando los elementos
    <p>
    <div class="bloquesBanner">
        @foreach ($bloques as $k => $bloque)
		<div class="bloqueBanner">
			<a href="javascript:nuevoItemBloque('{{ $banner->id }}',{{ $k }})"
				class="btn btn-primary">Nuevo</a>
			<h4>{{ ucfirst($bloque) }}</h4>
			<br>
			<div class="bannerItems" id="bannerItems{{ $k }}"></div>
		</div>
        @endforeach
    </div>

</div>

@if ($is_iframe)
    <footer>
        @include('admin::includes.foot')
    </footer>

    <div id="modal_message" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title"></h2>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
@endif
