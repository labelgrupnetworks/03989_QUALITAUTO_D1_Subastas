@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans('admin-app.title.lots') }}</h1>
            </div>
            <div class="col-xs-12 text-right" style="margin-top: 2rem">
                <a class="btn btn-primary"
                    href="{{ route("$parent_name.$resource_name.create", ['cod_sub' => $cod_sub, 'menu' => 'subastas']) }}">{{ trans('admin-app.button.new') }}
                    {{ trans('admin-app.title.lot') }}</a>
            </div>
        </div>

        <div class="">
            @include('admin::pages.subasta.lotes._table', [
                'cod_sub' => $cod_sub,
                'lotes' => $lotes,
                'pujas' => $pujas,
                'ordenes' => $ordenes,
                'formulario' => $formulario,
                'render' => $render,
                'tableParams' => $tableParams,
                'propietarios' => $propietarios,
                'resource_name' => $resource_name,
                'parent_name' => $parent_name,
                'tipo_sub' => $tipo_sub,
            ])
        </div>


    </section>
@stop
