<table>

    <tbody>
		<tr>
			<td   width="20" align="center">
				<strong>EXPOSICIÓN</strong>
			</td>

			<td width="20" align="center">
				<strong>REFERENCIA</strong>
			</td>

			<td width="80" align="center">
				<strong>TITULO</strong>
			</td>
			<td width="20" align="center">
				<strong>STOCK</strong>
			</td>
			<td width="30" align="center">
				<strong>ALMACEN</strong>
			</td><td width="30" align="center">
				<strong>OBSERVACIONES</strong>
			</td><td width="30" align="center">
				<strong>FECHA DE ALTA</strong>
			</td>
			<td width="20" align="center">
				<strong>PRECIO COSTE</strong>
			</td>
			<td width="20" align="center">
				<strong>PRECIO</strong>
			</td>

		</tr>
        @foreach ($lots as $lot)
            <tr>
				<td>
					{{ $lot->sub_asigl0 }}
				</td>

                <td>
					{{ $lot->ref_asigl0 }}
				</td>

                <td>
					{{ $lot->descweb_hces1 }}
				</td>
				<td>
					{{ $lot->stock_hces1 }}
				</td>
				<td>
					{{ $lot->des_alm }}
				</td>	<td>
					{{ $lot->obsdet_hces1 }}
				</td>	<td>
					{{ $lot->fecalta_asigl0 }}
				</td>


                <td class="precio">{{ \Tools::moneyFormat($lot->pc_hces1) }}€ </td>
				<td class="precio">{{ \Tools::moneyFormat($lot->impsalhces_asigl0) }}€ </td>
            </tr>
        @endforeach



    </tbody>
</table>
