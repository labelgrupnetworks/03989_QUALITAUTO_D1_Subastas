<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="{{ trans(\Config::get('app.theme') . '-app.head.meta_author') }}">
    <meta name="description" content="{{ trans(\Config::get('app.theme') . '-app.head.meta_description') }}">
    <title>
        {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
    </title>
    <meta name="title" content="{{ trans(\Config::get('app.theme') . '-app.head.title_app') }}">

	<style>
		@page {
			margin: 100px 50px;
			text-align: justify;
		}

		table,
		th,
		td {
			border: 1px solid black;
			border-collapse: collapse;
		}

		th,
		td {
			padding: 5px 0px 5px 4px;
		}

		th {
			text-align: center;
			font-weight: bold;
		}

		td {
			text-align: left;
		}
	</style>
</head>

<body>
    <main>
        <p style="margin-bottom: 20px">
			<b>INTERNATIONAL BUSSINES AUCTIONS, S.L</b> (INBUSA), con C.I.F. número B-8409148, y domicilio social en Madrid, Calle Poeta Joan Maragall nº 51, 4º-3, C.P.28020
		</p>

		<p><b><u>C E R T I F I C A</u></b></p>

		<p>
			<b>PRIMERO.-</b>  Que por encargo de la Administración Concursal, y de conformidad a las bases aprobadas judicialmente,
			 se ha llevado a cabo la subasta online de bienes de la concursada {{ $auctionName }},
			correspondiente a la fase de liquidación del Concurso de Acreedores nº {{ $auto }}.
		</p>

		<p>
			<b>SEGUNDO.-</b>  Que, según el informe de adjudicación provisional emitido por INBUSA respecto a cada uno de los lotes descritos en la subasta,
			ha sido recibida como mayor oferta realizada la descrita en el sigueinte cuadro:
		</p>

		<table class="table-bordered table-content" style="width: 100%; max-width: 100%;">
			<thead>
				<tr>
					<th>Lote</th>
					<th>Oferante</th>
					<th>NIF</th>
					<th>Importe</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($awards as $award)
					<tr>
						<td>{{ $award->ref }}</td>
						<td>{{ $award->nom_cli }}</td>
						<td>{{ $award->cif_cli }}</td>
						<td>{{ Tools::moneyFormat($award->himp_csub, '€', 2) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		<p style="margin-bottom: 20px">
			<b>TERCERO.-</b> Se adjunta al presente certificado copia del informe de adjudicación provisional relativo a los lotes descritos en el cuadro contenido en el apartado anterior;
			significado que la adjudicación definitiva deberá ser realizada por la Administración Concursal.
		</p>

		<p>Lo que firma, a los efectos oportunos, para su constancia y/o exhibición ante cualquier institución o instancia, en Madrid a {{ $nowDate }}.</p>
    </main>

	<footer>
		<div style="float: right;">
			<p style="margin-bottom: 100px;">P.O.</p>
			<p>Fdo. Tomás Mejias Meigares</p>
		</div>
	</footer>
</body>

</html>
