
		<div class="col-xs-12 no-padding" >
			<div class="col-xs-12 col-sm-7 no-padding bread-after">
				<div class="bread">
					@include('includes.bread')
				</div>
			</div>
			<div class="col-xs-12 col-sm-5 no-padding follow">
				<div class="next d-flex align-item-center justify-content-space-between text-right">
								@if(!empty($data['previous']))
					<a class="color-letter nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}" href="{{$data['previous']}}"><i class="fa fa-angle-left fa-angle-custom"></i> {{ trans(\Config::get('app.theme').'-app.subastas.last') }}</a>
								@endif
								@if(!empty($data['next']))
					<a class="color-letter nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}" href="{{$data['next']}}">{{ trans(\Config::get('app.theme').'-app.subastas.next') }} <i class="fa fa-angle-right fa-angle-custom"></i></a>
								@endif
				</div>
			</div>

		</div>
