<div class="row articles row-cols-1 row-cols-sm-3">
	@foreach ($banner->activeItems as $banner)
		<article class="col">
			<a href="{{ $banner->url ?? '#' }}">
				<picture>
					<source srcset="{{ $banner['images']['desktop'] ?? '' }}" media="(min-width: 992px)">
					<img src="{{ $banner['images']['mobile'] ?? '' }}" class="img-fluid" alt="banner image">
				</picture>

				<p>{!! $banner->texto ?? '' !!}</p>
			</a>
		</article>
	@endforeach
</div>
