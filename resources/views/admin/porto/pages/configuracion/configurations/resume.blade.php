@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1 class="m-0">Configuraciones modificadas</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-bordereds" style="width:100%; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Valor Actual</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sections as $section)
                            <tr>
                                <th class="bg-dark text-white" colspan="3">Sección: {{ $section }}</th>
                            </tr>
                            @foreach ($configs->where('category', $section) as $config)
                                <tr>
                                    <td>{{ $config->key }}</td>
                                    <td>
										<p>{{ $config->meta['description'] }}</p>
									</td>
                                    <td style="overflow-wrap: break-word;">{{ $config->value }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @stop
