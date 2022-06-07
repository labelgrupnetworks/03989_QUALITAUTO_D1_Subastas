
		<div class="col-xs-12 no-padding" >
			<div class="col-xs-12 col-sm-7 no-padding bread-after">
				<div class="bread">
					@include('includes.bread')
				</div>
			</div>
			<div class="col-xs-12 col-sm-5 no-padding follow">

				<div class="col-xs-12 no-padding text-right">
					<select id="actual_currency">
	                  @foreach($divisas as $divisa)
	                      @if($divisa->cod_div != 'EUR')
	                          <?php //quieren que salgan los dolares por defecto (sin no hay nada o hay euros  ?>
	                          <option value='{{ $divisa->cod_div }}' <?= ($data['js_item']['subasta']['cod_div_cli'] == $divisa->cod_div || ($divisa->cod_div == 'USD' &&  ($data['js_item']['subasta']['cod_div_cli'] == 'EUR'  || $data['js_item']['subasta']['cod_div_cli'] == '' )))? 'selected="selected"' : '' ?>>
	                               {{ $divisa->cod_div }}
	                          </option>
	                      @endif
	                  @endforeach
	            	</select>
	            </div>
	            <div class="clearfix"></div>

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
