@extends('layouts.default')

@section('title')
	{{ trans('web.head.title_app') }}
@stop

@section('content')
<div class="container " >
	<div class="pay_transfer_page">
		<?php
		# hay dos modos de llamar a la vista, pasandole variables o directamente,
		#si se le pasan variables hacemos un control para que no puedna poner lo que quieran


		if(!empty(request("control"))){
			$importe = request("trans");
			$idtrans = request("idtrans");

			$control = request("control");
			$md5 = md5($importe.Session::get('user.cod'));
			$imp =  base64_decode($importe);

			if( $control == $md5 ){
				echo trans('web.user_panel.text_transfer', ["pago" => \Tools::moneyFormat($imp,null,2),"idtrans" => $idtrans,"cuenta" => \Config::get('app.tranferCount')]) ;
			}

		}
		#si cargamos la vista directamente
		else{
			echo trans('web.user_panel.text_transfer', ["pago" => \Tools::moneyFormat($importe,null,2),"idtrans" => $idtrans,"cuenta" => \Config::get('app.tranferCount')]);
		}
		?>

	</div>
</div>
@stop
