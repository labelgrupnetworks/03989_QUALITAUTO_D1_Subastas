<table>
    <thead>
        <tr>
            <td class="title" colspan="2" width="40" height="50">
                {{-- si hay mas de un artista no se pone nada --}}
                @if (count($artists) == 1)
                    <H1> {{ $artists[0]->name_artist }}</H1>
                @endif

                {{-- Si no hay artistao el que hay no se llama igual que la exposición --}}
                @if (count($artists) == 0 || trim(mb_strtoupper($auction->des_sub)) != trim(mb_strtoupper($artists[0]->name_artist)))
                    <h2> {{ $auction->des_sub }}</h2>
                @endif


                @php
                    $startDate = Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->dfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
                    $endDate = Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $auction->hfec_sub)->locale(\Tools::getLanguageComplete(\Config::get('app.locale')));
                @endphp

				<br>

                {{ $startDate->day }} {{ trans(\Config::get('app.theme') . '-app.galery.de') }}
                {{ $startDate->monthName }}
                -
                {{ $endDate->day }} {{ trans(\Config::get('app.theme') . '-app.galery.de') }} {{ $endDate->monthName }}
                <br />
                {{ $endDate->year }}
            </td>
            <td class="imgAuction" colspan="2">
                @php
                    $arrContextOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ];

					$file="img/thumbs/780/AUCTION_" . Config::get('app.emp') . "_$auction->cod_sub.jpg";
					$src = $file;
                @endphp
				@if (file_exists($file))
                <img src="{{ $src }}" height="150"/>
				@endif
            </td>
        </tr>
    </thead>

    <tbody>
        @foreach ($lots as $lot)
            <tr>
                <td>
					{{ $lot->ref_asigl0 }}
				</td>

                <td>
					{{ $lot->descweb_hces1 }}
				</td>

                <td width="40">

                    @if (!empty($caracteristicas[$lot->num_hces1 . '_' . $lot->lin_hces1][2]))
                        {{ $caracteristicas[$lot->num_hces1 . '_' . $lot->lin_hces1][2] }}
                    @endif
                </td>
                <td width="15">
                    @if (!empty($caracteristicas[$lot->num_hces1 . '_' . $lot->lin_hces1][3]))
                        {{ $caracteristicas[$lot->num_hces1 . '_' . $lot->lin_hces1][3] }}
                    @endif
                </td>
                <td class="precio">{{ \Tools::moneyFormat($lot->impsalhces_asigl0) }}€ </td>
            </tr>
        @endforeach

		<tr>
			<td class="sinIva" colspan="5" style="text-align: right;">Sin Iva </td>

		</tr>

    </tbody>
</table>
