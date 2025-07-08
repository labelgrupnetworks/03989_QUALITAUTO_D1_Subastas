<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Imprimir Documento #{{ $subasta->cod_sub }}</title>
    <link type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 20mm;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            font-family: sans-serif;
            font-size: 9pt;
        }

        /* Cabecera fija */
        header.print-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header.print-header h1 {
            font-size: 12pt;
        }

        .print-header p {
            margin: 0;
        }

        .logo>* {
            width: 100%;
            height: auto;
            max-width: 150px;
        }

        .print-content {
            margin-top: 110px;
            margin-bottom: 0px;
            page-break-after: always;
        }

        td {
            /* white-space: nowrap; */
			vertical-align: middle;
        }
    </style>
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
</head>

<body onload="window.print()">

    <header class="print-header">
        <div class="">
            <h1>Listado órdenes de licitador y telefónicas</h1>
            <p>{{ now()->locale('es')->isoFormat('LLLL') }}</p>
            <p>Subasta:: {{ $subasta->cod_sub }}</p>
        </div>
        <div class="logo">
            <x-icon.logo />
        </div>
    </header>

    @foreach ($bidPaddles->groupBy('operador_orlic') as $operator => $groupedBidPaddles)
        <div class="print-content">
            <table class="table table-striped table-sm" id="tableBidPaddles">
                <thead>
                    <tr>
                        <th style="width: 5%">Lote</th>
                        <th style="width: 5%">Licitador</th>
                        <th style="width: 15%">Nombre licitador</th>
                        <th style="width: 10%">Hasta importe</th>
                        <th style="width: 5%">Tipo</th>
                        <th style="width: 5%">Oper.</th>
                        <th style="width: 20%">Nombre Operador</th>
                        <th style="width: 15%">Teléfonos</th>
                        <th style="width: 15%">Observaciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($groupedBidPaddles as $bidPaddle)
                        <tr class="bidPaddle">

                            <td>{{ $bidPaddle->ref_orlic }}</td>
                            <td>{{ $bidPaddle->licit_orlic }}</td>
                            <td>{{ $bidPaddle->rsoc_licit }}</td>
                            <td>{{ Tools::moneyFormat($bidPaddle->himp_orlic, '€') }}</td>
                            <td>
                                @if (!$bidPaddle->has_max_bidds && $bidPaddle->is_max_order)
                                    <x-icon.gavel />
                                @endif
                            </td>
                            <td>{{ $bidPaddle->operador_orlic }}</td>
                            <td>{{ $bidPaddle->phoneBiddingAgent->nom_operadores ?? 'Operador no asignado' }}</td>
                            <td>
                                {{ $bidPaddle->tel1_orlic }} {{ $bidPaddle->tel2_orlic }}
                                {{ $bidPaddle->tel3_orlic }}
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
