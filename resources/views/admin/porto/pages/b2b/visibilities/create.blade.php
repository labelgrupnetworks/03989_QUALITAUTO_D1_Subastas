@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1>{{ trans('admin-app.button.new_fem') }} {{ trans('admin-app.title.visibility') }}</h1>
            </div>
            <div class="col-xs-3">
                <a class="btn btn-primary right"
                    href="{{ route('admin.b2b.visibility') }}">{{ trans('admin-app.button.return') }}</a>
            </div>
        </div>


        <div class="row well">

            <form action="{{ route('admin.b2b.visibility.store') }}" method="POST">
                @csrf
                @include('admin::pages.subasta.visibilidades._form', [
                    'formulario' => $formulario,
                    'visibility' => $visibility,
                ])
            </form>

        </div>

    @stop
