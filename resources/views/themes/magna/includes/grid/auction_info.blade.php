@php
    $emp = Config::get('app.emp');
    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpeg");
@endphp

<img src="{{ $auctionImage }}" alt="">

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-3"></div>

		<div class="col-12 col-lg-9">
			<div class="auction-type-name">
				{{ $auction->des_sub }}
			</div>

			<div class="auction-expo">
				<div class="auction-expo-dates">
					<p class="fw-bold">Exposición</p>
					<p>{{ $auction->expofechas_sub }}</p>
					<p>{{ $auction->expohorario_sub }}</p>
					<p>{{ $auction->expolocal_sub }}</p>
				</div>
				<div class="auction-session-dates">
					<p class="fw-bold">Subasta</p>
					<p>{{ $auction->sesfechas_sub }}</p>
					<p>{{ $auction->seshorario_sub }}</p>
				</div>
			</div>

			<h1>
				{{ $auction->name }}
			</h1>
			<p>
				{{ $auction->description }}
			</p>
        </div>
    </div>
</div>
