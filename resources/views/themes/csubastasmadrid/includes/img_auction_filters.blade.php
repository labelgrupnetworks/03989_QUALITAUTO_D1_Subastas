@if(!empty($ficha_subasta))
<div class="sidebar_lot info-buy-filters">
    <img  width="100%" src="/img/load/subasta_large/SESSION_{{ $ficha_subasta->emp_sub }}_{{ $ficha_subasta->cod_sub }}_{{$ficha_subasta->reference}}.jpg" class="img-responsive">

	<div class="session-desc">
		<p>{!! $ficha_subasta->session_info ?? $ficha_subasta->descdet_sub ?? '' !!}</p>
	</div>

 </div>
 @endif
