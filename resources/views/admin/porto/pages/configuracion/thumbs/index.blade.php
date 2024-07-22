@extends('admin::layouts.logged')

@section('content')
    <style>
        .log {
            text-align: left;
            background-color: #d1d1d1;
            border: 1px solid gainsboro;
            border-radius: 10px;
            min-height: 400px;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 10px;
            padding: 1rem;
            scroll-behavior: smooth;
        }
    </style>

    <section class="content-body" role="main">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">Generar minaturas</h1>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-md-4 well">
                <form name="search_lots">
                    <div class="form-group">
                        <label for="auctionInput">Código de subasta</label>
                        <input class="form-control" id="auctionInput" name="auction" type="text">
                    </div>
                    <div class="form-group">
                        <label for="numhcesInput">Hoja de cesión</label>
                        <input class="form-control" id="hcesInput" name="numhces" type="text">
                    </div>
                    <div class="form-group">
                        <label for="linhcesInput">Línea</label>
                        <input class="form-control" id="linhcesInput" name="linhces" type="text">
                    </div>
                    <div class="form-group">
                        <label for="refInput">Referencia</label>
                        <input class="form-control" id="refInput" name="ref" type="text">
                    </div>
                    <div class="form-group">
                        <label for="sizeInput">Tamaño</label>
                        <select class="form-control" id="sizeInput" name="size" type="text">
                            @foreach ($sizes as $size)
                                <option value="{{ $size->size_web_images_size }}">
                                    {{ $size->size_web_images_size }} - {{ $size->name_web_images_size }}
                                </option>
                            @endforeach
                            <option value="">Todos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <div class="col-xs-12 col-md-8">
                <div class="log" id="div_log"></div>

                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" id="progressBarImg"
                        role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span>
                            <span id="progressBarValue">
                                {{ trans('admin-app.general.zero_percent') }}
                            </span>
                            <span>
                                {{ trans('admin-app.success.completed') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </section>
@endsection
