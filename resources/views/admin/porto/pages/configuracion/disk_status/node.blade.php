<ul>
	@foreach ($directories as $directory)
		<li class="jstree-closed" id="{{ $directory['path'] }}">
			{{ $directory['name'] }}: {{ $directory['size'] }}
		</li>
	@endforeach
</ul>
