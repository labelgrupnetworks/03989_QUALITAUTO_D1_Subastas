@if ($is_iframe)
    <head>
        @include('admin::includes.head')
    </head>
@endif

<div id="banners" class="p-1">
    <form method="post" id="nuevoBanner" action="/admin/newbanner/nuevo_run">
		@csrf

		@if(!$is_iframe)
        <h1>{{ trans("admin-app.title.new_banner") }}</h1>
		<br>
		@else
		{!! $ubicacion !!}
		{!! $id_content !!}
		{!! FormLib::Hidden("to_frame", 1, true) !!}
		@endif

        <div class="row">
            <div class="col-xs-12 col-md-3">

                <label>{{ trans("admin-app.fields.key_word") }}:</label>
                {!! $nombre !!}
                {!! $tipo !!}
            </div>
        </div>

        <br>

        <label>{{ trans("admin-app.fields.type") }}:</label>
        <div class="row">
            @foreach ($tipos as $item)
                <div class="col-xs-12 col-sm-3">
                    <div class="well text-center" onclick="javascript:comprueba_tipo({{ $item->id }});">
                        <img src='/themes_admin/porto/assets/img/tipo{{ $item->id }}.jpg' width="50%">
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

@if ($is_iframe)
    <footer>
        @include('admin::includes.foot')
    </footer>
@endif
