<article class="card auction-card gallery-collection-card">
	<img class="card-img-top"
		src="{{ Tools::url_img('square_medium', $galleryCollection->num_hces1, $galleryCollection->lin_hces1) }}"
		alt="">
	<div class="card-body">
		<div class="text-center w-100">
			<p class="ff-highlight card-title">
				{{ Tools::changePositionNamesWithComa($galleryCollection->name_artist) }}</p>
		</div>
	</div>
	<a class="stretched-link"
		href="{{ route('artistaFondoGaleria', ['id_artist' => $galleryCollection->id_artist]) }}"></a>
</article>
