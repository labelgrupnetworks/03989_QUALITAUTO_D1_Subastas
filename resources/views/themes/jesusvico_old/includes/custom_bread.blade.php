<div class="bread-content">

	@if (!empty($bread))

	<ul itemscope itemtype="https://schema.org/BreadcrumbList">

		<?php
		if(empty($bread[0]["url"])){
			$bread[0]["url"] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}
		?>

		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a class="btn-bread" itemtype="https://schema.org/Thing" itemprop="item"

				title="{{$bread[0]['name']}}" href="{{ $lote_actual->subc_sub == 'S' ? \Routing::translateSeo('presenciales') : \Routing::translateSeo('subastas-historicas') }}">

				<span itemprop="name">{{ trans(\Config::get('app.theme').'-app.subastas.auctions')}}</span>
			</a>
			<meta itemprop="position" content="1" />
		</li>


		@if (!empty($data['subasta_info']) && $data['subasta_info']->lote_actual->tipo_sub != 'V')

			@php
				$url_subasta = $lote_actual->url_subasta;
			@endphp

			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<a class="btn-bread" itemtype="https://schema.org/Thing" itemprop="item" title="{{$bread[0]['name']}}" href="{{ $url_subasta }}">
					<span itemprop="name">{{$bread[0]["name"]}}</span>
				</a>
				<meta itemprop="position" content="2" />
			</li>

		@endif


		<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a class="btn-bread" itemtype="https://schema.org/Thing" itemprop="item" title="{{ trans(\Config::get('app.theme').'-app.lot.go_back') }}" href="javascript:(window.history.back())">
				<span itemprop="name">{{ trans(\Config::get('app.theme').'-app.lot.go_back') }}</span>
			</a>
			<meta itemprop="position" content="3" />
		</li>

	</ul>

	@endif
</div>
