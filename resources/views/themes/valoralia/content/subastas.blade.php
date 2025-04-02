@php
    use App\Models\V5\AucSessionsFiles;
    use App\Models\V5\FgSub;

    //Obtener todos los codigos de subasta
    $codsSub = Arr::pluck($data['auction_list'], 'cod_sub');
    $codsSub = array_unique($codsSub);

    //Obtener todos los archivos de las subastas
    $allFiles = AucSessionsFiles::query()
        ->select('"auction"', '"description"', '"type"', '"path"', '"order"', '"url"')
        ->whereIn('"auction"', $codsSub)
        ->where('"lang"', Tools::getLanguageComplete(Config::get('app.locale')))
        ->orderBy('"order"')
        ->get();

	if($data['type'] == FgSub::TIPO_SUB_ONLINE) {
		$data['auction_list'] = array_reverse($data['auction_list']);
	}
@endphp

<div class="all-auctions color-letter">
    <div class="container">
        <div class="row">
            <div class="auctions-list col-xs-12">

                    @foreach ($data['auction_list'] as $subasta)
                        @include('includes.auction', ['allFiles' => $allFiles, 'subasta' => $subasta])
                    @endforeach

            </div>
        </div>
    </div>
</div>
