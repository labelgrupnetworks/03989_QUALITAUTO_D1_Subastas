@php
    use App\Models\V5\FgEspecial1;
    $specialists = FgEspecial1::getSpecialists();
@endphp

<section class="container container-short my-5">

    <section class="mb-5">
		{!! BannerLib::bannersPorKey(
			'team',
			'',
			['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
			null,
			false,
			'',
			$page_settings,
		) !!}
	</section>

    <div class="text-md-end">
        <p class="seo-block-subtitle">{{ trans("$theme-app.pages.team_subtitle") }}</p>
        <h2 class="seo-block-title">{{ trans("$theme-app.pages.team_title") }}</h2>
        <div class="seo-block-content ms-auto">
            <p class="mb-5">{{ trans("$theme-app.pages.team_text") }}</p>
            <p class="text-start ms-auto w-md-75">{{ trans("$theme-app.pages.team_valentino") }}</p>
        </div>
    </div>

    <div class="team-members row row-cols-sm-2 row-cols-md-3 g-4">
        @foreach ($specialists as $specialist)
            <div class="team-member">
                <div class="team-member-image">
                    <img src="{{ $specialist->image }}.jpg?a=1731507411" alt="{{ $specialist->nom_especial1 }}">
                </div>
                <div class="team-member-info">
                    <p>{{ $specialist->specialty?->title }}</p>
                    <h3 class="ff-highlight">{{ Str::title($specialist->nom_especial1) }}</h3>
                    <p>{{ $specialist->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <p class="ms-auto w-md-75">{{ trans("$theme-app.pages.team_extra_text") }}</p>

</section>

<section class="container container-short py-4 mb-4">
    <x-contact-section>
        <x-slot:topAddress>
            <h2 class="contact-address-subtitle">{{ trans("$theme-app.pages.contact_subtitle") }}</h2>
            <h3 class="contact-address-title">{{ trans("$theme-app.pages.contact_title") }}</h3>
        </x-slot:topAddress>
    </x-contact-section>
</section>
