	<div class="bread">

			<div class="col-xs-12 col-sm-10 no-padding bread-after">
				<div class="bread">
					@include('includes.bread_product')
				</div>
			</div>
			<div class="col-xs-12 col-sm-2 no-padding follow b-siguiente">
				<div class="next d-flex align-item-center justify-content-flex-end text-right">
								@if(!empty($data['previous']))
					<a class="color-letter nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}" href="{{$data['previous']}}"><i class="fa fa-angle-left fa-angle-custom"></i> {{ trans(\Config::get('app.theme').'-app.subastas.last') }}</a>
								@endif
								@if(!empty($data['next']))
					<a class="color-letter nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}" href="{{$data['next']}}">{{ trans(\Config::get('app.theme').'-app.subastas.next') }} <i class="fa fa-angle-right fa-angle-custom"></i></a>
								@endif
				</div>
			</div>

	</div>
