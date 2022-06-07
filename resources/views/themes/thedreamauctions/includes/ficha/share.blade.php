
<?php
$titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;
?>


<div class="zone-share-social col-xs-12 no-padding mt-3 text-center">
	{{-- <p class="shared">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p> --}}

	<p><a title="{{ trans(\Config::get('app.theme').'-app.lot.share_email') }}"  href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fa fa-envelope" aria-hidden="true"></i> {{ trans("$theme-app.lot.ask_question") }}</a></p>
	<div class="row ficha-separator mb-2 mt-2"></div>

	@if(!Session::has('user.cod'))
	<p><a href="javascript:openLogin();"><img class="img-responsive" style="max-width: 30px; display: inline-block" src="/default/img/icons/auction_60_icon_gold.png" alt=""> {{ trans("$theme-app.lot.register_to_bid") }}</a></p>
	<div class="row ficha-separator mb-2 mt-2"></div>
	@endif

	<p><a onclick="shareLot('{{ config('app.name') }}', '{{ $titulo }}','{{ $_SERVER['REQUEST_URI'] }}')" class="share-btn"><i class="fa fa-share-alt" aria-hidden="true"></i> {{ trans("$theme-app.lot.share") }}</a></p>
</div>
