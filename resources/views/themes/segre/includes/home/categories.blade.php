<div class="container text-center">
    <h2>
		{{ trans('web.home.catalogs_title') }}
	<p class="text-color-light">
		{{ trans("web.home.all_lots_products") }}
	</p>

	{!! BannerLib::bannerWithView('home-categories', 'categories') !!}
</div>
