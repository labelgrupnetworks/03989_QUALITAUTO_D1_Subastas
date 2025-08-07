@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Características</h1>
            </div>

        </div>

        @include('admin::pages.subasta.features.table')
    </section>
@stop
