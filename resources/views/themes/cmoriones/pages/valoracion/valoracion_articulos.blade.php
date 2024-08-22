@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    @php
        $bread[] = ['name' => $data['title']];
		$entityRequest = request()->get('rep', 'AC');
		$entityTypes = [
			'AC' => trans("$theme-app.valoracion_gratuita.entity_ac"),
			'EB' => trans("$theme-app.valoracion_gratuita.entity_eb"),
			'FI' => trans("$theme-app.valoracion_gratuita.entity_fi"),
		];

		$entity = $entityTypes[$entityRequest] ?? $entityTypes['AC'];
    @endphp

    <main class="valoracion-page">
        <div class="container">

            @include('includes.breadcrumb')

            <h1 class="titlePage">
				{{ $entityTypes[$entity] ?? $entity }}
				{{-- {{ trans("$theme-app.valoracion_gratuita.solicitud_valoracion") }} --}}
			</h1>

            {{-- <p class="optimal-text-lenght">{!! trans("$theme-app.valoracion_gratuita.desc_assessment") !!}</p> --}}

            <form class="mt-3" id="form-valoracion-adv" action="">
                @csrf

                <input name="captcha_token" data-sitekey="{{ config('app.captcha_v3_public') }}" type="hidden"
                    value="">

				<input type="hidden" name="entidad" value="{{$entity}}">
				<input type="hidden" name="operacion" value="Venta">
				<input type="hidden" name="campana" value="subastasonline">


                <p class="text-danger h4 hidden msg_valoracion">
                    {{ trans(\Config::get('app.theme') . '-app.valoracion_gratuita.error') }}</p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label"
                                for="name">{{ trans("$theme-app.valoracion_gratuita.name") }}</label>
                            <input class="form-control" id="name" name="name" type="text"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.name") }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="email">{{ trans("$theme-app.valoracion_gratuita.email") }}</label>
                            <input class="form-control" id="email" name="email" type="email"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.email") }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="telf">{{ trans("$theme-app.valoracion_gratuita.telf") }}</label>
                            <input class="form-control" id="telf" name="telf" type="phone"
                                placeholder="{{ trans("$theme-app.valoracion_gratuita.telf") }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"
                                for="propiedades">{{ trans("$theme-app.valoracion_gratuita.n_of_properties") }}</label>
							<select class="form-select" name="propiedades">
								<option value="0-100">Desde 0 a 100</option>
								<option value="100-200">De 100 a 300</option>
								<option value="300-1000">De 300 a 1000</option>
								<option value="+1000">Más de mil</option>
							</select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                for="descripcion">{{ trans("$theme-app.valoracion_gratuita.description") }}</label>
                            <textarea class="form-control" name="descripcion" rows="12" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
					<div class="col-12">
						<p class="captcha-terms">
							{!! trans("$theme-app.global.captcha-terms") !!}
						</p>
					</div>
                </div>

                <button class="button-send-valorate btn btn-lb-primary" id="valoracion-adv"
                    type="submit">{{ trans("$theme-app.valoracion_gratuita.send") }}</button>
            </form>
        </div>
    </main>
@stop
