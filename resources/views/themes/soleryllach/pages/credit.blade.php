@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-12 resultok">
                <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.subastas.credit') }}</h1>
        </div>
	</div>

	<div class="row">
		<div class="col-xs-6">
			<h4>{{ trans(\Config::get('app.theme').'-app.subastas.current_credit') }}</h4>
			<p>{{  \Tools::moneyFormat($currentCredit) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
		</div>
		<div class="col-xs-6">

			@if(!empty($creditIncreases))
				<h4>{{ trans(\Config::get('app.theme').'-app.subastas.credit_request') }}</h4>
				<div>
					<button class="btn btn-credit" data-value="{{ $creditIncreases[0] }}">{{ \Tools::moneyFormat($creditIncreases[0]) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</button>
				</div>

				<input type="button" id="add-credit" class="btn btn-primary mt-2" value="{{ trans(\Config::get('app.theme').'-app.user_panel.return_credit') }}">
			@else
			<h4>{{ trans(\Config::get('app.theme').'-app.subastas.no_more_credit') }}</h4>
			@endif
			<a class="btn btn-primary mt-2" href="{{ $urlTiempoReal }}">{{ trans(\Config::get('app.theme').'-app.user_panel.return_subastas') }} </a>

		</div>
	</div>
</div>

<div id="modalCredit" class="container modal-block mfp-hide ">
	<div data-to="increaseCredit" class="modal-sub-w">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class=" text-center single_item_content_">
						<span class="class_h1">
							<p id="insert_msg_title"></p>
						</span>
						<br/>
						<p id="insert_msgweb">{{ trans(\Config::get('app.theme').'-app.subastas.credit_request_message') }} <span id="creditValue"></span></p>
						<br/>
						@if(!empty($creditIncreases))
							<form action="{{ route('increaseCredit') }}" method="POST">
								@csrf
								<input type="hidden" id="modal_credit" name="credit" value="{{ $creditIncreases[0] }}">
								<input type="hidden" name="cod_sub" value="{{ $cod_sub }}">
								<input type="hidden" id="modal_urlTiempoReal" name="urlTiempoReal" value="{{ $urlTiempoReal }}">
								<input type="submit" class="btn button_modal_confirm btn-custom" value="{{ trans(\Config::get('app.theme').'-app.lot.accept') }}">
							</form>
						@endif
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@stop


