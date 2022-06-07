@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@include('pages.panel.principal_bar')

<section class="account">
	<div class="container">
		<div class="row">
			<?php $tab="shipping-address";?>

			<div class="col-xs-12">
				@include('pages.panel.menu')
			</div>


			<div class="col-xs-12">
				<div class="user-datas-title title-collapse" data-toggle="collapse" data-target="#fact_accordion">

					<p>{{ trans(\Config::get('app.theme').'-app.user_panel.billing_address') }}</p>
					<p><small><i class="fa fa-info-circle" aria-hidden="true"></i> {!! trans(\Config::get('app.theme').'-app.user_panel.billing_address_info') !!}</small>
						<span style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span></p>


				</div>
			</div>
			{{-- Fact address --}}
			<div class="col-xs-12 address-panel collapse js-title-collapse" id="fact_accordion">
				<div class="panel panel-default" cod="new">
					<div class="panel-heading">
						<p class="panel-title">

							<a class="" data-toggle="collapse" data-parent="#fact_accordion" href="#addres_fact" cod="new">
								<label class="camel-case w-100" for="">
									<span >
										{{$data['user']->nom_cli}} -
										{{$data['user']->dir_cli}}{{$data['user']->dir2_cli}} -
										{{$data['user']->cp_cli}}, {{$data['user']->pro_cli}} -
										{{$data['countries'][$data['user']->codpais_cli] }}
									</span>
								</label>
							</a>

						</p>
					</div>
					<div id="addres_fact" class="panel-collapse collapse">
						<div class="panel-body" id="">
							@include('front::pages.panel.form_direcciones._form_readonly', ['user' => $data['user']])
						</div>
					</div>
				</div>
			</div>



			<div class="col-xs-12">
				<div class="user-datas-title title-collapse" data-toggle="collapse"
					data-target="#accordion,#accordion_new">
					<p>{{ trans(\Config::get('app.theme').'-app.user_panel.shipping_addresses') }} <span
							style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span></p>
				</div>
			</div>

			{{-- edit address --}}
			<div class="col-xs-12 panel-group address-panel collapse js-title-collapse @if(!empty($data['auction'])) in @endif" id="accordion">

				@foreach ($data['shippingaddress'] as $key => $address)

				<div class="panel panel-default" cod="{{$address->codd_clid}}">
					<div class="panel-heading">
						<p class="panel-title">

							<label class="w-100 d-flex align-items-center address-name-wrapper" for="fav_address_{{$address->codd_clid}}">

								<input type="radio" id="fav_address_{{$address->codd_clid}}" name="fav_address"
									value="fav_address_{{$address->codd_clid}}">

								<span class="camel-case address-name">
									{{$address->nomd_clid}} - {{$address->dir_clid}}{{$address->dir2_clid}} -
									{{$address->cp_clid}}, {{$address->pro_clid}} -
									{{$data['countries'][$address->codpais_clid] }}
								</span>

								@if ($address->codd_clid == $data['codd_clid'])
								<span
									class="gold clearfix">({{ trans(\Config::get('app.theme').'-app.user_panel.address_predeterminated') }})</span>
								@endif

								<a class="clearfix" data-toggle="collapse" data-parent="#accordion" href="#addres_{{$key}}"
									cod="{{$address->codd_clid}}">
								</a>

								<span style="margin-left: auto" class="edit_address-span">{{ trans(\Config::get('app.theme').'-app.user_panel.edit_address') }}</span>
								<button style="display: none" type="submit" form="save_{{$address->codd_clid}}" disabled>
									<span class="edit_address text-success">{{ trans(\Config::get("app.theme")."-app.user_panel.save_address") }}</span></button>

							</label>
						</p>
					</div>
					<div id="addres_{{$key}}" class="panel-collapse collapse js-addresses">
						<div class="panel-body" id="shipping_add_{{$address->codd_clid}}">
							<form id="save_{{$address->codd_clid}}" class="save_address_shipping">
								@csrf

								@include('front::pages.panel.form_direcciones._form', ['address' => $address, 'fxcli' => $data['user']])

							</form>
						</div>
					</div>
				</div>

				@endforeach

			</div>

			{{-- New address --}}
			<div class="col-xs-12 address-panel collapse @if(!empty($data['auction'])) in @endif" id="accordion_new">
				<div class="panel panel-default" cod="new">
					<div class="panel-heading">
						<p class="panel-title">

							<a class="" data-toggle="collapse" data-parent="#accordion" href="#addres_new" cod="new">
								<label for="" class="w-100">
									<i class="fa fa-plus-circle text-success"></i>
									<span>{{ trans(\Config::get('app.theme').'-app.user_panel.add_address') }}</span>
								</label>
							</a>

						</p>
					</div>
					<div id="addres_new" class="panel-collapse collapse">
						<div class="panel-body" id="shipping_add_new">
							<form id="save" class="save_address_shipping">
								@csrf
								@include('front::pages.panel.form_direcciones._form', ['address' => new stdClass(), 'fxcli' => $data['user']])
							</form>
						</div>
					</div>
				</div>
			</div>

			@if(!empty($data['auction']))
			<div class="col-xs-12 text-center">
				<a href="{{route('panel.allotment.sub', ['cod_sub' => $data['auction'], 'lang' => Config::get('app.locale')])}}" class="btn btn-color signup">
					{{ trans("$theme-app.user_panel.back_my_invoice") }}
				</a>
			</div>
			@endif

		</div>
		<input type="hidden" id="lang_dirreciones" value="<?=Config::get('app.locale')?>">
</section>


@if(Config::get('app.delivery_address'))
<div id="modalDeletAddress" class="container modal-block mfp-hide" data-to="modalDeletAddress">
	<div data-to="modalDeletAddress" class="">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class=" text-center single_item_content_">
						<p>{{ trans(\Config::get('app.theme').'-app.user_panel.delete_address') }}</p><br />
						<input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
						<input value="" name="cod" type="hidden" id="cod_delete">
						<input value="<?=Config::get('app.locale')?>" name="lang" type="hidden" id="lang">
						<button
							class=" btn button_modal_confirm modal-dismiss modal-confirm">{{ trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@endif

<input type="hidden" class="js-prefix" value='@json($data['prefix'])'>

@stop
