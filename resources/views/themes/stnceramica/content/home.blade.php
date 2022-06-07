@php
$categoryBannerOptions = "{
  'dots': false,
  'arrows': false,
  'rows': 5,
  'slidesPerRow': 5,
  'responsive': [
    {
      'breakpoint': 576,
      'settings': {
		'rows': 5,
        'slidesPerRow': 1,
      }
    }
  ]
 }";
@endphp


<div class="home-banner">
    {!! \BannerLib::bannersPorKey('home-top-banner', 'home-top-banner', ['arrows' => false, 'dots' => true, 'infinite' => true]) !!}
</div>



<div class="category-container mt-5">
	@if(Session::has('user'))
	{!! \BannerLib::bannersPorKey('category-banner', 'home-category-banner', $categoryBannerOptions) !!}
	@else
	{!! \BannerLib::bannersPorKey('category-nologin', 'home-category-banner', $categoryBannerOptions) !!}
	@endif
</div>

