<p class="bread">
    <i class="fa fa-home"></i> <a title="Home" href="/">{{ trans(\Config::get('app.theme').'-app.subastas.breadcrumb') }}</a> 
    @if (!empty($bread))
        @foreach ($bread as $crumb)    
            <i class="fa fa-angle-right"></i> 
            @if(empty($crumb["url"]))
                {!!$crumb["name"]!!}
            @else
                <a title=" {{$crumb["name"]}}" href=" {{$crumb["url"]}}"> {{$crumb["name"]}}</a> 
                
            @endif
        @endforeach
    @endif
</p>
			