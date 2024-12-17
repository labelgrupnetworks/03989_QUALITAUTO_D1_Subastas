@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        @include('admin::includes.header_content')

		<div class="row">
			<div class="col-xs-12">
				<div class="well">
					<div class="d-flex align-items-center">
						<h1 class="m-0">{{ trans('admin-app.button.new') }} Empresa</h1>
						<a class="btn btn-primary ml-auto" href="{{ url()->previous() }}">{{ trans('admin-app.button.return') }}</a>
					</div>

				</div>
			</div>
		</div>

        <form id="companyStore" action="{{ route('admin.b2b.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-xs-12">
                    <div class="well">
                        @include('admin::pages.b2b.companies._form', [
                            'formulario' => $formulario
                        ])
                    </div>
                </div>

                {{-- <div class="col-xs-4">
                    <div class="well">
						@include('admin::pages.b2b.companies._image', ['images' => []])
                    </div>
                </div> --}}

            </div>

			<div class="row">
				<div class="col-xs-12 text-center">
					{!! $formulario->submit !!}
				</div>
			</div>


        </form>

    </section>
@stop
