

@php
	$img_path="/img/autores/".$artist->id_artist.".jpg";
@endphp

<div class="artist-wrapper col-xs-12 col-sm-6 col-md-4">
	<a title="{{ $artist->name_artist }}" class=" link_artist" alt="{{ $artist->name_artist }}"
		href="{{ route("artist",["name" => \Str::slug($artist->name_artist), "idArtist" => $artist->id_artist] )}}" >
		<div class="item_artist">
            <div class="item_img">
				@if(file_exists(public_path($img_path)))
                	<img class="img-responsive"  src="{{$img_path}}" alt="{{$artist->name_artist}}">
				@endif
            </div>

            <div class="data-container">
				<div class="title_item">
					<p class="max-line-1 m-0 bold">{{$artist->name_artist}}</p>
					@if(!empty($lotsByArtist[$artist->id_artist]))
						<p>{{ $lotsByArtist[$artist->id_artist] }} {{ trans($theme . '-app.subastas.obras') }}</p>
					@endif
				</div>
            </div>
        </div>
	</a>
</div>
