

<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3  square" >
    <a title="{{ $artist->name_artist }}" class=" link_artist" href="{{ route("artist",["name" => \Str::slug($artist->name_artist), "idArtist" => $artist->id_artist] )}}" >

        <div class="item_artist">
            <div class="item_img">
				@if(file_exists(public_path($img_path)))
                	<img class="img-responsive"  src="{{$img_path}}" alt="{{$artist->name_artist}}">
				@endif
            </div>

            <div class="data-container">
				<div class="title_item">
					{{$artist->name_artist}}
				</div>
				<div class="description_lot">
					{{$artist->info_artist}}
				</div>

            </div>

        </div>
    </a>
</div>
