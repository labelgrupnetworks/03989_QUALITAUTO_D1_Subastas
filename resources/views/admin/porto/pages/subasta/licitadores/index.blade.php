@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans('admin-app.title.licits') }}</h1>
            </div>

        </div>

        @include('admin::pages.subasta.licitadores.table')
    </section>
@stop
