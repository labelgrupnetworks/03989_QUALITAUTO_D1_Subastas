@extends('admin::layouts.logged')
@section('content')
    <section class="content-body" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">Subastas iniciales</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 table-responsive">
                <table class="table table-striped table-condensed table-responsive" id="clientes" style="width:100%;margin-bottom: 70px;">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Tipo</th>
                            <th>Abierta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($auctions as $auction)
                            <tr id="{{ $auction['idauction'] }}">
                                <td>{{ $auction['idauction'] }}</td>
                                <td>{{ $auction['name'] }}</td>
                                <td>{{ $auction['status'] }}</td>
                                <td>{{ $auction['type'] }}</td>
                                <td>{{ $auction['visiblebids'] }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-default dropdown-toggle" id="dropdownMenu1"
                                            data-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="true">
                                            <i class="fa fa-gear"></i> Acciones
                                        </button>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            @if (!$auction['isCreated'])
                                                <li>
                                                    <a
                                                        href="{{ route('admin.test-auctions.create', ['idauction' => $auction['idauction']]) }}">
                                                        Crear subasta
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a
                                                        href="{{ route('admin.test-auctions.reset', ['idauction' => $auction['idauction']]) }}">
                                                        Reiniciar subasta
                                                    </a>
                                                </li>
                                                @if (!$auction['isFirstLotCreated'])
                                                    <li>
                                                        <a
                                                            href="{{ route('admin.test-auctions.create-lots', ['idauction' => $auction['idauction']]) }}">
                                                            Crear Lotes
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <a
                                                            href="{{ route('admin.test-auctions.reset-lots', ['idauction' => $auction['idauction']]) }}">
                                                            Reiniciar Lotes
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </section>
@endsection
