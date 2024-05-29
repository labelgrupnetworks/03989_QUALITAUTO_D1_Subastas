<ul>
    @foreach ($directories as $directory)
        <li class="jstree-closed" id="{{ $directory['path'] }}">
            <span style="flex: 1">{{ $directory['name'] }}</span>
            <span class="node_size">{{ $directory['size'] }}</span>
        </li>
    @endforeach

    @foreach ($files as $file)
        <li id="{{ $file['path'] }}" data-jstree='{"type": "image"}'>
            <span style="flex: 1">
                <a href="{{ $file['link'] }}" target="_blank">
                    {{ $file['name'] }}
                </a>
            </span>
            <span style="margin-right: 1rem">{{ $file['lastModified'] }}</span>
            <span class="node_size">{{ $file['size'] }}</span>
        </li>
    @endforeach
</ul>
