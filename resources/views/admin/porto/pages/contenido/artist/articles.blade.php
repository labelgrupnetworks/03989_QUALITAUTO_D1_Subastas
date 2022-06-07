@foreach($articles as $article)
<div class="col-xs-5">
	<label style="margin-top: 1rem" for="title_article_1">{{ trans("admin-app.fields.artist.title") }}</label><br/>
	<input type="text" name="title_article[{{$article->id_artist_article}}]" style="width: 100%;" value="{{$article->title_artist_article}}"><br/>
</div>	
<div class="col-xs-5">
	<label style="margin-top: 1rem" for="url_article_1">{{ trans("admin-app.fields.artist.url") }}</label><br/>
	<input type="text"  name="url_article[{{$article->id_artist_article}}]" style="width: 100%;" value="{{$article->url_artist_article}}">
</div>	
<div class="col-xs-2">

	<input type=button class="btn btn-danger artistDelete_JS" data-id="{{$article->id_artist_article}}" value="Eliminar" style="margin-top: 33px;">
</div>	

@endforeach
