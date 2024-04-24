<ul>
	@foreach ($directories as $directory)
		<li class="jstree-closed" id="{{ $directory['name'] }}">
			{{ $directory['name'] }}: {{ $directory['size'] }}
		</li>
	@endforeach
</ul>
