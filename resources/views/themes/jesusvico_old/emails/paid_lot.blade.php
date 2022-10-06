<table style="width: 100%;" cellpadding="15">
	<tbody>
		@foreach ($lots as $lot)
		<tr>
			<td style="width: 20%; text-align:center">
				<img width="100%"
					src="{{\Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1)}}"
					alt="{{ $lot->descweb_hces1 }}">
			</td>
			<td style="vertical-align: top;">
				<p><strong>{{ $lot->descweb_hces1 }}</strong></p>
				<p>{{ trans("$theme-app.global.precio") }}: <strong>{{ \Tools::moneyFormat($lot->impsalhces_asigl0, '€') }}</strong></p>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
