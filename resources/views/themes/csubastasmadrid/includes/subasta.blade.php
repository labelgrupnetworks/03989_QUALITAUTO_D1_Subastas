<div class="col-xs-12">
	<div class="item_subasta row">

		<div class="col-xs-12 col-sm-6 col-md-4">
			<a title="{{ $subasta->name }}" href="<?= $url_lotes ?>">
				<div>
					<img src="{{ Tools::url_img_session('subasta_medium', $ficha_subasta->cod_sub, $ficha_subasta->reference) }}" alt="{{ $subasta->name }}"
						class="img-responsive" />
				</div>
			</a>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-5">
			<div class="item_subasta_item">
				{{ $subasta->name }}
			</div>

			<div class="item_subasta_item_text">
				{!! $ficha_subasta->session_info ?? $ficha_subasta->descdet_sub !!}
			</div>
		</div>

		<div class="col-xs-12 col-md-3">

			{{-- @if ($subasta->subc_sub != 'N')
				<p><a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
						class="btn btn-lotes btn-3">{{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}</a>
				</p>
			@endif --}}

			<p><a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
					class=" btn btn-lotes btn-3">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
			</p>

			<p>
				@if (!empty(request('finished') && filter_var(request('finished'), FILTER_VALIDATE_BOOLEAN)))
					<a title="{{ $subasta->name }}" href="{{ $url_lotes_no_vendidos }}"
						class=" btn {{-- btn-lotes btn-color --}} btn-3">{{ trans(\Config::get('app.theme') . '-app.subastas.lotes_no_vendido') }}</a>
				@endif
			</p>

			@if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time() && $subasta->subastatr_sub == 'S')
				<p class="text-center" style="">
					<a class="btn {{-- btn-block btn-live --}} btn-2" style="" href="{{ $url_tiempo_real }}"
						target="_blank">{{ trans("$theme-app.subastas.bid_live") }}</a>
				</p>
			@endif


			@if ($subasta->uppreciorealizado == 'S')
				<p class="text-center">
					<a class="btn {{-- btn-subasta --}} btn-2" title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
						target="_blank"
						href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
				</p>
			@endif

			@if ($subasta->upcatalogo == 'S')
				<p class="text-center">
					<a class="btn {{-- btn-subasta --}} btn-3"
						title="{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}" target="_blank"
						href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</a>
				</p>
			@endif
		</div>
	</div>
</div>
