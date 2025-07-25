<div class="row gy-4">
	@foreach ($auctionSessionFiles as $file)
	<div class="col-12">
		<a class="d-flex gap-2 text-decoration-none" href="{{$file->url_format}}" target="_blank">
			<img src="{{$file->img ?? $file->path_icon}}" alt="" width="24px" height="24px">
			<p>{{ $file->description }}</p>
		</a>
	</div>
	@endforeach
</div>
