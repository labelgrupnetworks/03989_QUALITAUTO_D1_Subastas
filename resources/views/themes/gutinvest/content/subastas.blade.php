
<div class="container">
    <div class="row">
        <div class="title-subastas col-xs-12">
            @if($data['type'] == 'W' )
                {{ trans(\Config::get('app.theme').'-app.foot.presenciales')}}
			@elseif($data['subc_sub'] == 'H')
				{{ trans(\Config::get('app.theme').'-app.foot.historico')}}
			@elseif ($data['type'] == null)
                {{ trans(\Config::get('app.theme').'-app.foot.online_sales')}}
            @else
                {{ $data['name'] }}
            @endif

            ({{ count($data['auction_list'])}})
        </div>
    </div>
</div>


<div class="container">
    <div class="row hei">
            @include('includes.blocs')
    </div>
</div>
