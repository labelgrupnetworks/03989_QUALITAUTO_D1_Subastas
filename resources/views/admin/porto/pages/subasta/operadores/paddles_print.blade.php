<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Imprimir Documento #{{ $subasta->cod_sub }}</title>
    <style>
        /* aquí tus estilos “print-friendly” */
        @media print {

            /* oculta botones o elementos no deseados */
            .no-print {
                display: none;
            }

            body {
                margin: 0;
                padding: 0;
                font-size: 12pt;
            }

            /* estilos para el documento */
            .page {
                page-break-after: always;
                margin: 0 auto;
                width: 100%;
                text-align: center;
            }

            .licit-number,
            .licit-name,
            .licit-lote,
            .licit-importe {
                margin: 0;
                padding: 0;
            }
        }

        body {
            margin: 1cm;
            font-family: sans-serif;
        }

        .page {
            page-break-after: always;
            text-align: center;
            margin-bottom: 2cm;
        }

        .bidPaddle {
            border: 1px solid #000;
            padding: 2rem;
            margin: 0.5em;
            display: inline-block;
            box-sizing: border-box;
            text-align: left;
            width: 100%;
        }

        .licit-operator {
            margin: 0;
        }

        .licit-number {
            font-size: 12em;
            text-align: center;
            margin: 15px 0;
            line-height: 1;
        }

        .licit-number-open {
            color: red;
        }

        .licit-name {
            font-size: 1em;
            margin: 0.5em 0;
        }

        .licit-lote,
        .licit-importe {
            font-size: 1em;
            margin: 0.5em 0;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print">
        <!-- Un botón para cerrar o reimprimir si quieres -->
        <button onclick="window.close()">Cerrar</button>
    </div>

    {{-- Imprimier dos paletas por página --}}
    {{-- @foreach ($products->chunk(3) as $chunk) --}}
    @foreach ($bidPaddles->chunk(2) as $chunk)
        <div class="page">
            @foreach ($chunk as $bidPaddle)
                <div class="bidPaddle">
                    {{-- Mostar el nombre del operador en la esquina superior izquierda --}}
                    <p class="licit-operator">
                        {{ $bidPaddle->phoneBiddingAgent->nom_operadores ?? 'Operador no asignado' }}
                    </p>

                    {{-- Mostrar centrado y en grande el número de la paleta --}}
                    <p @class([
                        'licit-number',
                        'licit-number-open' =>
                            (!$bidPaddle->has_max_bidds && $bidPaddle->is_max_order),
                    ])>{{ $bidPaddle->licit_orlic }}</p>

                    {{-- Por debajo mostrar el nombre de la palete, numero de lote e importe --}}
                    <p class="licit-name">{{ $bidPaddle->rsoc_licit }}</p>
                    <p class="licit-lote">
                        Lote: {{ $bidPaddle->ref_orlic }}.
                        Titulo: {{ strip_tags($bidPaddle->descweb_hces1) }}
                    </p>
                    <p class="licit-importe">Hasta Importe: {{ Tools::moneyFormat($bidPaddle->himp_orlic, '€') }}</p>
                    <p class="licit-phones">
                        Teléfonos: {{ $bidPaddle->tel1_orlic }} {{ $bidPaddle->tel2_orlic }}
                        {{ $bidPaddle->tel3_orlic }}
                    </p>
                </div>
            @endforeach
        </div>
    @endforeach

    <!-- o tu maquetación completa del documento -->
</body>

</html>
