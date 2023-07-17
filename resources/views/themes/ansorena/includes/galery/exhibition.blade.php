<article class="card auction-card exhibition-card">
	<img class="card-img-top" src="{{ $exhibition->image }}" alt=""
		height="680" @if ($lazyLoad ?? false) loading="lazy" @endif>
	<div class="card-body">
		<div class="text-center w-100">
			<p class="ff-highlight card-title">
				{{ $exhibition->artist }}
			</p>

			<p class="ff-highlight fs-20 mb-3">{!! $exhibition->title !!}</p>
			<p class="text-uppercase ls-2">{{ $exhibition->initialDate }} -
				{{ $exhibition->finalDate }}
				{{ $exhibition->year }}</p>
		</div>
	</div>
	<a class="stretched-link" href="{{ $exhibition->url }}"></a>
</article>
