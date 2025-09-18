@extends('layouts.default')

@section('title')
    {{ trans('web.head.title_app') }}
@stop

@section('content')
    <main class="grid">

        <div class="container grid-header">
            <div class="row">

                <div class="col-12">
                    @include('includes.breadcrumb')
                </div>

				@if(!empty($auction))
				<div class="col-12">
                    <h1>{{ $seo_data->h1_seo }}</h1>
                </div>
				@endif

                <div class="col-12 py-3">
                    @if (is_null($auction) && !empty($infoOrtsec))
						<h1>{{ $infoOrtsec->des_ortsec0 }}</h1>

                        {!! BannerLib::bannersPorKey("grid-{$infoOrtsec->key_ortsec0}", 'grid-top-banner', [
                            'dots' => false,
                            'autoplay' => true,
                            'autoplaySpeed' => 5000,
                            'slidesToScroll' => 1,
                            'arrows' => false,
                        ]) !!}

                    @endif
                </div>


            </div>
        </div>



        @include('content.grid')
    </main>
@stop
