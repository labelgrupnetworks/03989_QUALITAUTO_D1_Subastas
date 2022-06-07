<div id="ajax_shipping_add" class="delivereaForm col-xs-12 color-letter">

	<div class="tabs-deliverea">

		<div class="content-tabs pl-0 col-xs-12 col-sm-5 col-md-3">
			<div class="plus-address"><a class="color-letter seeshippingaddress" cod="new"><i
						class="fa fa-2x fa-plus-circle"></i>
					<span>{{ trans(\Config::get('app.theme').'-app.user_panel.add_address') }}</span></a>
			</div>

			<div class="content-address">

				<ul class="nav nav-tabs">
					@foreach($data['shippingaddress'] as $key => $address)
					@if($address->codd_clid == 'W1')
					<div class="title-address">
						{{ trans(\Config::get('app.theme').'-app.user_panel.address_predeterminated') }}
					</div>
					<li role="presentation"
						class='<?=$data['codd_clid'] == $address->codd_clid?'selected':''; ?> address'>
						<a class="seeshippingaddress color-letter" cod="{{$address->codd_clid}}">
							{{$address->dir_clid}}{{$address->dir2_clid}} ({{$address->pro_clid}})
						</a>
					</li>
					<div class="title-address">
						{{ trans(\Config::get('app.theme').'-app.user_panel.address_normal') }}</div>
					@else
					<li role="presentation"
						class='<?=$data['codd_clid'] == $address->codd_clid?'selected':''; ?> address'>
						<a class="seeshippingaddress colo-letter" cod="{{$address->codd_clid}}">
							{{$address->dir_clid}}{{$address->dir2_clid}} ({{$address->cp_clid}})
						</a>
					</li>
					@endif
					@endforeach
				</ul>
			</div>
		</div>

		<!-- Tab panes -->
		<div class="tab-content col-xs-12 col-sm-7 col-md-9">
			<?php $address = $data['address'];?>

			<div class="inputs-custom-group">
				<form class="col-xs-12 save_address_shipping">
					@if(empty($data['new']) && !empty( $data['codd_clid']) && $data['codd_clid'] !=
					'W1')
					<div class="deliverea_tit">
						{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio') }}
					</div>
					@else
					<div class="deliverea_tit">
						{{ trans(\Config::get('app.theme').'-app.user_panel.title_envio_predeterminated') }}
					</div>
					@endif

					<input name="_token" type="hidden" value="{{ csrf_token() }}" />
					<input class="codd_clid" type="hidden" name="codd_clid"
						value="<?= !empty( $data['codd_clid'])? $data['codd_clid']:'W1'; ?>">

					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label
							for="nombre">{{ trans(\Config::get('app.theme').'-app.login_register.nombre_apellido') }}</label>
						<input type="text" class="form-control" name="usuario"
							placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}" required
							value="<?= !empty($address->nomd_clid)?$address->nomd_clid:''; ?>">
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label for="telefono">{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}</label>
						<input type="text" name="telefono" class="form-control"
							placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.phone') }}" required=""
							maxlength="40" value="<?= !empty($address->tel1_clid)?$address->tel1_clid:''; ?>">
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-2">
						<label for="codigoVia">{{ trans(\Config::get('app.theme').'-app.login_register.via') }}</label>
						<select id="" name="clid_codigoVia" class="form-control">
							<option value="">---</option>
							@if (!empty($data) && !empty($data["via"]) )
							@foreach ($data["via"] as $via)
							<option <?= !empty($address->sg_clid) && $via->cod_sg == $address->sg_clid?'selected':'';?>
								value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-10">
						<label for="direccion">{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}</label>
						<input type="text" name="clid_direccion" class="form-control dreccion_cambiar" id="direccion"
							placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.address') }}" required
							maxlength="60"
							value="<?= !empty($address->dir_clid)?$address->dir_clid:''; ?><?= !empty($address->dir2_clid)?$address->dir2_clid:''; ?>">
					</div>


					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label for="pais">{{ trans(\Config::get('app.theme').'-app.user_panel.pais') }}</label>
						<select id="country_envio" name="clid_pais" class="form-control" required>
							<option value="">---</option>
							@if (!empty($data) && !empty($data["countries"]) )
							@foreach ($data["countries"] as $country)
							<option
								<?= (!empty($address->codpais_clid) && $address->codpais_clid == $country->cod_paises)? 'selected' : ''; ?>
								value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label
							for="codigo_postal">{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}</label>
						<input id="codigo_postal" type="text" name="clid_cpostal" class="form-control"
							placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.zip_code') }}" required
							maxlength="10" value="<?= !empty($address->cp_clid)?$address->cp_clid:''; ?>">
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label>{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}</label>
						<input id="clid_provincia" name="clid_provincia" class="form-control" maxlength="30" type="text"
							placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.provincia') }}"
							value="<?= !empty($address->pro_clid)?$address->pro_clid:'';?>" />
					</div>
					<div class="form-group col-xs-12 col-md-6 col-lg-6">
						<label for="nombre">{{ trans(\Config::get('app.theme').'-app.user_panel.city') }}</label>
						<input id="clid_poblacion" type="text" name="clid_poblacion" class="form-control"
							placeholder="{{ trans(\Config::get('app.theme').'-app.user_panel.city')}}" required
							maxlength="30" value="<?= !empty($address->pob_clid)?$address->pob_clid:'';?>">
					</div>

					<div class="deliverea-buttons col-xs-12 no-padding">
						@if(empty($data['new']) && !empty( $data['codd_clid']) && $data['codd_clid'] !=
						'W1')
						<div class="btn-delete">
							<button class="btn btn-danger delete-address" type="button"
								cod="<?= !empty( $data['codd_clid'])? $data['codd_clid']:'';?>">{{ trans(\Config::get('app.theme').'-app.user_panel.delete') }}</button>
						</div>
						@endif
						<div class="btn-save">
							<button class="btn button-principal"
								type="submit">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
						</div>
						@if(empty($data['new']) && !empty( $data['codd_clid']) && $data['codd_clid'] !=
						'W1')
						<div class="btn-save ">
							<button class="button-principal btn fav-address" type="button"
								cod="<?= !empty( $data['codd_clid'])? $data['codd_clid']:'';?>">{{ trans(\Config::get('app.theme').'-app.user_panel.define_default') }}</button>
						</div>
						@endif
					</div>
				</form>
				<input type="hidden" id="lang_dirreciones" value="<?=Config::get('app.locale')?>">
			</div>
		</div>
	</div>
</div>
