
<div class="bread-content">
        <a title="Home" class="color-letter bread-link" href="/">{{ trans($theme.'-app.subastas.breadcrumb') }}</a>
        @if (!empty($bread))
            @foreach ($bread as $crumb)
                /
                @if(empty($crumb["url"]))
                    {!!$crumb["name"]!!}
                @else
                    <a class="color-letter bread-link" title=" {{$crumb["name"]}}" href=" {{$crumb["url"]}}">{{$crumb["name"]}}</a>
                @endif
            @endforeach
        @endif
</div>



