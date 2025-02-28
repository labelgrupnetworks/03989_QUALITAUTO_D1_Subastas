<table cellpadding="2" width="100%"  >
	<tr style="text-align:center;font-weight:bold;background-color: #555;color: #FFF;">
		<td>{{ trans('web.shopping_cart.article') }}</td>

		<td>{{ trans('web.articles.units') }}</td>

		<td>{{ trans('web.articles.price') }}</td>


		<td>{{ trans('web.user_panel.total_pay') }}</td>

	</tr>
@foreach($articulos as $articulo)
	@include("emails.articulos.articulo")
@endforeach


<tr style="text-align:center;font-weight:bold">
	<td></td>
	<td></td>
	<td></td>
	<td style="text-align:right;">{{  \Tools::moneyFormat($articulo["total_pedc0"] , trans('web.lot.eur') ,2)}}</td>

</tr>
</table>
