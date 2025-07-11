@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1>{{ trans('admin-app.button.new') }} {{ trans_choice('admin-app.title.client', 1) }}</h1>
            </div>
            <div class="col-xs-3">
                <a href="{{ route('clientes.index') }}"
                    class="btn btn-primary right">{{ trans('admin-app.button.return') }}</a>
            </div>
        </div>

        <form action="{{ route('clientes.update', ['cliente' => $cliente->codcli]) }}" method="POST" id="clientesUpdate"
            enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row well">
                @include('admin::pages.usuario.cliente_v2._form', compact('formulario', 'clienteFxCli'))
            </div>

            <div class="row d-flex gap-5 mb-3 mt-3">
                <div class="col-xs-12 col-md-6 well" style="flex: 1">
                    @include('admin::pages.usuario.cliente_v2._files_table', [
                        'cliente' => $cliente,
                        'files' => $files,
                    ])
                </div>

                @if (Config::get('app.admin_client_dni', false))
                    <div class="col-xs-12 col-md-6 well" style="flex: 1">
                        @include('admin::pages.usuario.cliente_v2._dni', [
                            'cliente' => $cliente,
                            'dnis' => $dnis,
                        ])
                    </div>
                @endif

            </div>

            <div class="row">
                <div class="col-xs-12 text-center">
                    {!! $formulario->submit !!}
                </div>
            </div>
        </form>

    </section>

@stop
