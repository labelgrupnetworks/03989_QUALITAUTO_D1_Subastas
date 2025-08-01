<ul class="nav nav-tabs" role="tablist">
    @if (!in_array('noLots', Config::get('app.config_menu_admin')))
        <li class="nav-item">
            <a class="nav-link" id="lotes-tab" href="{{ route('subastas.show', $cod_sub) . '#lotes' }}" role="tab"
                aria-controls="lotes" aria-selected="false">{{ trans('admin-app.title.lots') }}</a>
        </li>
    @endif
    @if (!in_array('noBids', Config::get('app.config_menu_admin')))
        <li class="nav-item">
            <a class="nav-link" id="pujas-tab" href="{{ route('subastas.show', $cod_sub) . '#pujas' }}" role="tab"
                aria-controls="pujas" aria-selected="false">{{ trans('admin-app.title.bids') }}</a>
        </li>
    @endif
    @if (!in_array('noOrders', Config::get('app.config_menu_admin')) && !empty($ordersTable))
        <li class="nav-item">
            <a class="nav-link" id="ordenes-tab" href="{{ route('subastas.show', $cod_sub) . '#ordenes' }}"
                role="tab" aria-controls="ordenes" aria-selected="false">{{ trans('admin-app.title.orders') }}</a>
        </li>
    @endif
    @if (!in_array('noAwards', Config::get('app.config_menu_admin')))
        <li class="nav-item">
            <a class="nav-link" id="adjudicaciones-tab" href="{{ route('subastas.show', $cod_sub) . '#adjudicaciones' }}"
                role="tab" aria-controls="adjudicaciones"
                aria-selected="false">{{ trans('admin-app.title.awards') }}</a>
        </li>
    @endif

    @if (in_array('lotsNotAwards', Config::get('app.config_menu_admin')))
        <li class="nav-item">
            <a class="nav-link" id="notAwards-tab" href="{{ route('subastas.show', $cod_sub) . '#notAwards' }}"
                role="tab" aria-controls="notAwards"
                aria-selected="false">{{ trans('admin-app.title.not_awards') }}</a>
        </li>
    @endif

	@if (Config::get('app.show_operadores', false))
		<li class="nav-item @if($active === 'operadores') active @endif">
			<a class="nav-link" id="operadores-tab" href="{{ route('subastas.phone_orders.index', $cod_sub) }}"
				role="tab" aria-controls="operadores" aria-selected="false">Operadores</a>
		</li>
	@endif

</ul>
