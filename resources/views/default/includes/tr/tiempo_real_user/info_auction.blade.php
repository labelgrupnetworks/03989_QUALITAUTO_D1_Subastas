<div class="tr_user_info_auction started hidden">
	<div>
		<ul class="nav nav-tabs">
			@if(Session::has('user'))
			<li>
				<a data-toggle="tab"
					href="#mensajes">{{ trans(\Config::get('app.theme').'-app.sheet_tr.room_msg') }}</a>
			</li>
			<li class="active">
				<a data-toggle="tab"
					href="#lotes">{{ trans(\Config::get('app.theme').'-app.sheet_tr.auctions_lots') }}</a>
			</li>
			<li>
				<a data-toggle="tab"
					href="#adjudicaciones">{{ trans(\Config::get('app.theme').'-app.sheet_tr.your_adjudications') }}</a>
			</li>
			@else
			<li class="active">
				<a data-toggle="tab" href="#lotes" style="border-bottom: 1px solid #ddd">{{ trans("$theme-app.lot_list.lots") }}</a>
			</li>
			@endif
		</ul>

		<div class="tab-content">

			@if(Session::has('user'))

			<div id="mensajes" class="tab-pane fade">
				@include('content.tr.tiempo_real_user.msg_sala')
			</div>
			<div id="lotes" class="tab-pane fade in active">
				@include('content.tr.tiempo_real_user.buscador')
			</div>
			<div id="adjudicaciones" class="tab-pane fade">
				@include('content.tr.tiempo_real_user.adjudicaciones')
			</div>

			@else

			<div id="lotes" class="tab-pane fade in active">
				@include('content.tr.tiempo_real_user.buscador')
			</div>

			@endif
		</div>
	</div>
</div>
