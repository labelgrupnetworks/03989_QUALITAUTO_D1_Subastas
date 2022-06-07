<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="bread">
                            @include('includes.bread')
                        </div>
		</div>
		<div class="col-xs-12 col-sm-12">
			<div class="next">
                            @if(!empty($data['previous']))
                            <a class="nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}" href="{{$data['previous']}}"><i class="fa fa-caret-left"></i> <strong>{{ trans(\Config::get('app.theme').'-app.subastas.last') }}</strong></a>
                            @endif
                            @if(!empty($data['next']))
                            <a class="nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}" href="{{$data['next']}}"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.next') }}</strong> <i class="fa fa-caret-right"></i></a>
                            @endif
			</div>
		</div>
	</div>
</div>