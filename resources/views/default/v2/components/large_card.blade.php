<article class="card card-custom-large h-100">
	<div class="row g-0 h-100">
		<div class="col-md-8 d-flex flex-column">

			<div class="card-body d-flex flex-column align-items-start">
				<header>
					<h5 class="card-title">{{ $title }}</h5>
					<h6 class="card-subtitle mb-2 text-muted">{{ $subtitle }}</h6>
				</header>

				<p class="card-text max-line-3 mb-2">{{ $content }}</p>
			</div>

			<footer class="card-footer">
				<div class="row row-cols-2 gy-1 card-links">
					<div class="col">
						<a class="btn btn-sm btn-outline-border-lb-primary" href="{{ $linkInfo }}" aria-label="Plus">
							<svg class="bi" width="12" height="12" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#plus"></use>
							</svg>
							{{ $linkInfoText }}
						</a>
					</div>
					<div class="col">
						<a class="btn btn-sm btn-outline-border-lb-primary" href="{{ $linkInfo }}">
							<svg class="bi" width="12" height="12" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#folder"></use>
							</svg>
							{{ $linkInfoText }}
						</a>
					</div>
				</div>
			</footer>

		</div>
		<div class="col-md-4 card-img-wrapper">
			<img src="https://demoauction.labelgrup.com/img/load/subasta_medium/SESSION_002_1340_001.jpg"
				class="w-100 h-100" alt="...">

			<div class="btn-live-wrapper">
				<button class="btn btn-lb-primary">{{ trans("web.lot.bid_live") }}</button>
			</div>

		</div>
	</div>
</article>
