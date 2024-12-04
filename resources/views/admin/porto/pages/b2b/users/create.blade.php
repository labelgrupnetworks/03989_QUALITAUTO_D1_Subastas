@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1>{{ trans('admin-app.button.new') }} {{ trans_choice('admin-app.title.client', 1) }}</h1>
            </div>
            <div class="col-xs-3">
                <a class="btn btn-primary right" href="{{ url()->previous() }}">{{ trans('admin-app.button.return') }}</a>
            </div>
        </div>

        <form action="{{ route('admin.b2b.users.store') }}" method="POST">
            @csrf

            <div class="row well">
                <div class="col-xs-12 col-md-6 col-md-push-3">
                    @foreach ($formulario as $field => $input)
                        <label class="mt-1" for="{{ $field }}">{{ trans("admin-app.fields.$field") }}</label>
                        {!! $input !!}
                    @endforeach

                    <div class="text-center mt-3">
                        <button class="btn btn-primary" type="submit">Guardar</button>
                    </div>
                </div>

            </div>
        </form>

    </section>

@stop
