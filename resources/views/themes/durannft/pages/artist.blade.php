@extends('layouts.default')

@section('title')
    {{ $artist->name_artist }}
@stop

@section('content')

    <div class="container mb-3 artist-page">

        <div class="row">
            <div class="col-xs-12 text-center">
                <h1 class="titlePage title-80 bold my-4">{{ trans(\Config::get('app.theme') . '-app.artist.artists') }}
                </h1>
            </div>
        </div>

        <div class="row artist-row">

            @if (file_exists('img/autores/' . $artist->id_artist . '.jpg'))
                <div class="col-xs-12 col-md-10 artist-image"
                    style="background-image: url(/img/autores/{{ $artist->id_artist }}.jpg)">
                </div>
            @endif
            <div class="col-xs-12 col-md-7 artist-content">
                <h1 class="name_artist">{{ $artist->name_artist }} </h1>
                <div class="bio_artist max-line-3" id="biographyArtist">
                    <div class="biographyArtistText"> {!! $artist->biography_artist !!}</div>
                </div>
            </div>


        </div>

        <div class="row">

            <div class="col-xs-12 mt-5">

                {{-- Pestañas --}}
                <ul class="nav nav-tabs artist-nav-tab" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#obras" aria-controls="obras" role="tab" data-toggle="tab">{{ trans(\Config::get('app.theme') . '-app.subastas.obras') }}</a>
                    </li>
                    <li role="presentation">
                        <a href="#bio" class="" aria-controls="bio" role="tab" data-toggle="tab">{{ trans(\Config::get('app.theme') . '-app.galery.biography') }}</a>
                    </li>
                    <li role="presentation">
                        <a href="#proxeventos" aria-controls="proxeventos" role="tab" data-toggle="tab">{{ trans(\Config::get('app.theme') . '-app.galery.upcoming_events') }}</a>
                    </li>
                </ul>

                <!-- Paneles de cada pestaña -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="obras">
                        @if (count($activeLots) > 0)
                            <div class="row mt-2">
                                <div class="col-xs-12 grid-artist">
                                    <div id="activeLots" class="tab-pane fade in active">
                                        <div class="row mt-2">
                                            @foreach ($activeLots as $item)
                                                @include("includes/artists/lot")
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="bio">
                        <div class="row mt-2">
                            <div class="col-xs-12 info-artist">
                                <p class="info_artist"> {{ $artist->info_artist }} </p>
                                <div id="js-artist-bio" class="artist-bio" style="--max-line: 3">
									<div>
                                    	{!! $artist->biography_artist !!}
									</div>
								</div>
                                <p id="js-artist-read-more" class="read-more"><small>Leer más</small></p>
								<p id="js-artist-read-less" class="hidden read-less"><small>Leer menos</small></p>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="proxeventos">
                        <p>{!! $artist->extra_artist !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
