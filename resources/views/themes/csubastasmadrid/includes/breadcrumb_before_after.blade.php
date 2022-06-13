<div class="container">
	<div class="row hei">
		<div class="col-xs-12 col-sm-8">
			<div class="bread">
                            @include('includes.bread')
                        </div>
		</div>
		<div class="col-xs-12 col-sm-4 text-right">
			<div class="next">
                            @if(!empty($data['previous']))
				<a class="nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}" href="{{$data['previous']}}"><i class="fa fa-angle-left fa-angle-custom"></i> {{ trans(\Config::get('app.theme').'-app.subastas.last') }}</a>
                            @endif
                            @if(!empty($data['next']))
				<a class="nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}" href="{{$data['next']}}">{{ trans(\Config::get('app.theme').'-app.subastas.next') }} <i class="fa fa-angle-right fa-angle-custom"></i></a>
                            @endif
			</div>
		</div>
	</div>
</div>