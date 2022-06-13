<div class="panel-body">
		<div style="height: 20px; margin: 5px 0px">
			<div class="loader search-panel-loader" style="display:hide;width: 25px;height: 25px;"></div>
		</div>
		<table style="width:100%">
			<thead>
				<tr>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.cod_cli_licit') }}</th>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.name_licit') }}</th>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.riesini_cli') }}</th>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.ries_cli') }}</th>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.current_credit') }}</th>
					<th>{{ trans(\Config::get('app.theme').'-app.sheet_tr.moment') }}</th>
				</tr>
			</thead>
			<tbody class='panel_clientes_credito' style="text-align: left"></tbody>
		</table>

</div>
