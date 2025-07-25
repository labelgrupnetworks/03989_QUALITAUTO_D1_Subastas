@php
    use App\Models\V5\FgSub;
    use App\Models\V5\AucSessions;

    $nextAuction = FgSub::firstActiveAuction(['cod_sub', 'des_sub', 'descdet_sub']);
	if(!$nextAuction) return;

    $sessions = AucSessions::whereAuction($nextAuction->cod_sub)->get();
	$auctionUrl = Tools::url_auction($nextAuction->cod_sub, $nextAuction->des_sub, null)
@endphp

<div class="row ms-3 ps-3 pe-3 pe-md-0 next-auction-wrapp">

    <div class="home-next-auction_info col-5 col-md-3">
        <h2 class="home-next-auction_title">
            {{ $nextAuction->des_sub }}
        </h2>

        <div class="mb-3 text-color-light">
            {!! $nextAuction->descdet_sub !!}
        </div>

        <a class="btn btn-lb-primary" href="{{ $auctionUrl }}">
            {{ trans("web.global.auction") }}
        </a>
    </div>

    <div class="next-auction_session col-6 col-md-8 ms-auto">

        <div class="px-2">
            <a href="{{ $auctionUrl }}">
                <img src="{{ Tools::auctionImage($nextAuction->cod_sub) }}" alt="{{ $nextAuction->des_sub }}">
            </a>
        </div>
        @foreach ($sessions as $session)
            @php
                $sesionName = $session->name;
                $urlSession = Tools::url_auction($session->auction, $session->name, $session->id_auc_sessions, '001');

                #calculamos en que página empieza la session
                $cuantosLotes = App\Models\V5\FgAsigl0::select('count(ref_asigl0) cuantos')
                    ->where('SUB_ASIGL0', $nextAuction->cod_sub)
                    ->where('ref_asigl0', '<', $session->init_lot)
                    ->first();

                $lotsPerPage = request('total', 24);
                $pagina = intdiv($cuantosLotes->cuantos, $lotsPerPage);

                $pagina += 1;

                $urlSession .= "?page={$pagina}&total={$lotsPerPage}";
            @endphp
            <div class="px-2">
                <a href="{{ $urlSession }}">
                    <img src="{{ Tools::auctionImage($nextAuction->cod_sub, null, $session->reference) }}"
                        alt="{{ $session->name }}">
                </a>
            </div>
        @endforeach
    </div>

</div>

<script>
    $('.next-auction_session').slick({
        infinite: false,
        slidesToShow: 2,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
        autoplay: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
                autoplay: true,
                autoplaySpeed: 3000
            }
        }]
    });
</script>
