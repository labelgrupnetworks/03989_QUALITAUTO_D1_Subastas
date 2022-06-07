<div class="aside credito">
	<div class="row">
		<div class="col-xs-12">
			<div class="credit-block">
				<p>{{ trans(\Config::get('app.theme').'-app.subastas.credit') }} <span class="novelty">{{ trans(\Config::get('app.theme').'-app.sheet_tr.novelty') }}</span></p>
				<h4>{{ trans(\Config::get('app.theme').'-app.subastas.current_credit') }}:
					<span
						data-current-credit="{{ $data['credit_info']['current_credit'] }}"
						id="current_credit">{{ $data['credit_info']['current_credit_format'] }}
					</span>
					{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				</h4>

				<h4>{{ trans(\Config::get('app.theme').'-app.subastas.credit_used') }}:
					<span
						id="credit_used">{{ $data['credit_info']['credit_used_format'] }}
					</span>
					{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				</h4>

				<h4>{{ trans(\Config::get('app.theme').'-app.subastas.available_credit') }}:
					<span
						id="available_credit">{{ $data['credit_info']['available_credit_format'] }}
					</span>
					{{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
				</h4>

				<a href="{{ route('creditPanel', ['cod_sub' => $data['subasta_info']->cod_sub, 'name' => str_slug($data['subasta_info']->lote_actual->name), 'id_auc_sessions' => $data['subasta_info']->lote_actual->id_auc_sessions]) }}"
					class="btn button credit-btn">{{ trans(\Config::get('app.theme').'-app.subastas.apply_for_credit') }}</a>
			</div>
		</div>
	</div>
</div>
