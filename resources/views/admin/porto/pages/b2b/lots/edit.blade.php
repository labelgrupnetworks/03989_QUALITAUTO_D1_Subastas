@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')

        <div class="row">
            <div class="col-xs-12">
                <div class="well">

                    <div class="d-flex align-items-center">
                        <h1 class="m-0">
                            {{ trans('admin-app.button.edit') }} {{ trans('admin-app.title.lot') }} -
                            {{ $fgAsigl0->ref_asigl0 }}
                        </h1>

                        <div class="ml-auto">
                            @if (session('success'))
                                <a class="btn btn-primary" href="{{ route('admin.b2b.lots.create') }}">
                                    {{ trans('admin-app.button.new') }} {{ trans('admin-app.title.lot') }}
                                </a>
                            @endif

                            <a class="btn btn-primary" href="{{ route('admin.b2b.lots') }}">
                                {{ trans('admin-app.button.return') }}
                            </a>

                            @if ($anterior)
                                <a class="btn btn-warning"
                                    href="{{ route('admin.b2b.lots.edit', ['ref_asigl0' => $anterior]) }}">
                                    {{ trans('admin-app.button.prev') }}
                                </a>
                            @endif
                            @if ($siguiente)
                                <a class="btn btn-warning"
                                    href="{{ route('admin.b2b.lots.edit', ['ref_asigl0' => $siguiente]) }}">
                                    {{ trans('admin-app.button.next') }}
                                </a>
                            @endif

                            <a class="btn btn-info"
                                href="{{ Tools::url_lot($cod_sub, null, '', $fgAsigl0->ref_asigl0, $fgAsigl0->num_hces1, $fgAsigl0->webfriend_hces1, $fgAsigl0->descweb_hces1) }}"
                                target="_blank">
                                Ver ficha
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <form id="loteUpdate" action="{{ route('admin.b2b.lots.update', [$fgAsigl0->ref_asigl0]) }}" method="POST"
            enctype="multipart/form-data">
            @method('PUT')
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
                        @include('admin::pages.b2b.lots._lot_images', ['images' => $images])
                    </div>

                    <div class="well">
                        @include('admin::pages.b2b.lots._lot_files', [
                            'formulario' => $formulario,
                            'files' => $files,
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
        $("input[name=starthour]").val('{{ $fgAsigl0->hini_asigl0 }}');
        $("input[name=endhour]").val('{{ $fgAsigl0->hfin_asigl0 }}');
    </script>

@stop
