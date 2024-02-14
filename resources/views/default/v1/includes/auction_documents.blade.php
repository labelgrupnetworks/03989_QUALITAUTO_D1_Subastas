@php
    $filesIcons = [
        1 => '/img/icons/pdf.png',
        2 => '/img/icons/video.png',
        3 => '/img/icons/image.png',
        4 => '/img/icons/document.png',
    ];

    $noIcon = '/img/icons/document.png';
@endphp

<div class="snippet_documentacion" id="docs{{ $subasta->id_auc_sessions }}">
    <a onclick="javascript:$('#docs{{ $subasta->id_auc_sessions }}').toggle('slide', {direction:'right'}, 500)"
        style="color:#000;font-size:18px;position:absolute;right:10px;top:10px;cursor:pointer;">x</a>
    <b>{{ trans($theme . '-app.subastas.documentacion') }}:</b>

    @foreach ($files as $file)
        <div class="row">
			<div class="col-xs-1"></div>
            <div class="col-xs-1 text-center">
                @if ($file->type == 5)
                    <i class="fa fa-map-marker" aria-hidden="true" style="font-size: 25px;"></i>
                @else
                    <img src="{{ $filesIcons[$file->type] ?? $noIcon }}" width="80%">
                @endif
            </div>
            <div class="col-xs-10">
                <a style="text-decoration: none;" title="{{ $file->description }}" target="_blank"
                    href="{{ $file->type == 5 ? $file->url : "/files/$file->path" }}">
                    {{ $file->description }}
                </a>
            </div>
        </div>
    @endforeach
</div>
