@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">

		<div class="row">
			<div class="col-xs-12">
				<div class="well">
					<div class="d-flex align-items-center">
						<h1 class="m-0">{{ trans('admin-app.button.new') }} {{ trans('admin-app.title.lot') }}</h1>
						<a class="btn btn-primary ml-auto" href="{{ url()->previous() }}">{{ trans('admin-app.button.return') }}</a>
					</div>

				</div>
			</div>
		</div>

        <form id="loteStore" action="{{ route('admin.b2b.lots.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xs-12 col-lg-8">
                    <div class="well">
                        @include('admin::pages.b2b.lots._form', [
                            'formulario' => $formulario,
                            'fgAsigl0' => $fgAsigl0,
                        ])
                    </div>
                </div>

                <div class="col-xs-12 col-lg-4">
                    <div class="well">
						@include('admin::pages.b2b.lots._lot_images', ['images' => []])
                    </div>

					<div class="well">
						@include('admin::pages.b2b.lots._lot_files', [
                            'formulario' => $formulario,
                            'files' => [],
                            'fgAsigl0' => $fgAsigl0,
                        ])
                    </div>
                </div>

            </div>

			<div class="row">
				<div class="col-xs-12 col-lg-8 text-center">
					{!! $formulario->submit !!}
				</div>
			</div>


        </form>

    </section>

    <script>
        $("input[name=reflot]").on('change', function(e) {
            $("input[name=idorigin]").val("{{ $cod_sub }}-" + $(this).val());
        });
    </script>

@stop
