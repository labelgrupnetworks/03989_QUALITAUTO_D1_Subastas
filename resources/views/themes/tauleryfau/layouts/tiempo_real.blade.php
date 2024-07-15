<!doctype html>
<html>
<head>

    <meta charset="utf-8">
<meta name="description" content="{{ trans($theme.'-app.head.meta_description') }}">
<meta name="author" content="{{ trans($theme.'-app.head.meta_author') }}">
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta name="robots" content="noindex">

<title>@yield('title', 'Tauler y fau')</title>
<link rel="shortcut icon" href="{{ URL::asset('/themes/'.$theme.'/img/favicon.ico') }}">

<link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/3.4.1/dist/css/bootstrap.min.css') }}" >

<!-- Selects -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/bootstrap-select/css/bootstrap-select.min.css') }}" />

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

<!--<link rel="stylesheet" href="{{ URL::asset('/vendor/IconHoverEffects/css/default.css') }}" />-->
<link rel="stylesheet" href="{{ URL::asset('/vendor/IconHoverEffects/css/component.css') }}" />

<!-- datetimepicker -->
<link rel="stylesheet" href="{{ URL::asset('/vendor/datetimepicker/css/bootstrap-datetimepicker.css') }}" />


<!-- Animate.css -->
<link rel="stylesheet" href="{{ URL::asset('/css/animate.css') }}" />
@include('includes.google_head')
<!-- Common Javascript -->
<script>
        var fecha_server = <?= getdate()[0] * 1000?> ;
	var routing = {
		subastashome: 	"{{ \Routing::slug('subastashome') }}",
		login: 			"{{ \Routing::slug('login') }}",
		registro: 		"{{ \Routing::slug('registro') }}",
		subasta: 		"{{ \Routing::slug('subasta') }}",
	};

	var messages = {
		'error': {
			<?php foreach (trans($theme."-app.msg_error") as $key => $value) : ?>
				'<?php echo $key; ?>': '<?php echo $value;?>',
			<?php endforeach; ?>
		},

		'success': {
			<?php foreach (trans($theme."-app.msg_success") as $key => $value) : ?>
				'<?php echo $key; ?>': '<?php echo $value;?>',
			<?php endforeach; ?>
		},

		'neutral': {
			<?php foreach (trans($theme."-app.msg_neutral") as $key => $value) : ?>
				'<?php echo $key; ?>': '<?php echo $value;?>',
			<?php endforeach; ?>
		}

	};
</script>
<script src="{{ URL::asset('vendor/jquery/3.6.0/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/3.4.1/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('js/common.js') }}?a={{rand()}}"></script>

<script src="{{ URL::asset('vendor/IconHoverEffects/js/modernizr.custom.js') }}"></script>

<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script src="{{ URL::asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- multi idioma plugin datetimepicker -->
@if(\App::getLocale() !='en')
<script src="<?php echo URL::asset('vendor/datetimepicker/js/locales/bootstrap-datetimepicker.'. \App::getLocale() . '.js') ?>"></script>
@endif
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/autocomplete/jquery.auto-complete.min.js') }}"></script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>

<link rel="stylesheet" type="text/css" href="/vendor/slick/slick.css" >
<link rel="stylesheet" type="text/css" href="/vendor/slick/slick-theme.css" >
<script src="{{ URL::asset('vendor/slick/slick.js') }}"></script>

<link rel="stylesheet" href="{{ URL::asset('/themes/'.$theme.'/css/components/carrousel_tr.css') }}?a=<?= rand(); ?>" />
<script src="{{ URL::asset('/themes/'.$theme.'/js/components/carrousel_tr.js') }}?a=<?= rand(); ?>"></script>















    @include('includes.head_tiempo_real')
    @if(Route::current()->parameter('proyector'))
    <style>
    html,body
	{
	  height: 100% !important;
	  font-size: 24px;
	}
    .navbar-brand {
    	text-align: center;
    	width: 100% !important;
    	font-size: 45px !important;
    }

    .navbar-header {
    	width: 100% !important;
    }

    header.enc h3 {
    	max-width: none;
    }
    .img-lot {
    	max-width: none !important;
    	width:100% auto !important;
    }
    .salida p {
    	font-size: 32px !important;
    }

    #ficha .salida {
    	color: #2B373A;
    	background: none;
    }
    h2 {
    	font-size: 30px !important;
    }

    .fondo1 {
    	display: flex;
		justify-content: center; /* align horizontal */
		align-items: center; /* align vertical */
    }

    #pujas_list{
    	overflow: hidden !important;
    }

    </style>
    <script>
    $(document).ready(function() {
    	$('.fondo1, .fondo2').css('height',(window.innerHeight - 104));
    	$('#pujas_list').css('height',(window.innerHeight - 208));
	});
    </script>
    @endif

    <style>
    .enc {
    	padding:0;
    }
    /*
    #clock {
	 background: linear-gradient(177deg, #3268d5, #545454);
	background-size: 10% 100%;

	-webkit-animation: AnimationName 10s ease infinite;
	-moz-animation: AnimationName 10s ease infinite;
	-o-animation: AnimationName 10s ease infinite;
	animation: AnimationName 10s ease infinite;
		}



	@-webkit-keyframes AnimationName {
	    0%{background-position:22% 0%}
	    50%{background-position:79% 100%}
	    100%{background-position:22% 0%}
	}
	@-moz-keyframes AnimationName {
	    0%{background-position:22% 0%}
	    50%{background-position:79% 100%}
	    100%{background-position:22% 0%}
	}
	@-o-keyframes AnimationName {
	    0%{background-position:22% 0%}
	    50%{background-position:79% 100%}
	    100%{background-position:22% 0%}
	}
	@keyframes AnimationName {
	    0%{background-position:22% 0%}
	    50%{background-position:79% 100%}
	    100%{background-position:22% 0%}
	}*/

    </style>
</head>

<body class="tiempo_real">
	<div class="language">
							<div class="select-currency">
                                    <?php

                                    /* Diferentes tipos e monedas */
                                        use App\libs\Currency;
                                        $currency = new Currency();
                                        $divisas = $currency->getAllCurrencies()
                                    ?>
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
								@if (\Config::get( 'app.enable_language_selector' ))
									<?php /* <select
										id="selectorIdioma"
										actuallang="/{{ \App::getLocale() }}/"
										name="idioma"
										class="form-control"
										style="width: 100px;height: 27px;font-size: 12px;padding: 0;background:#eee;color: black;border: 0;"
									>
										<option value="es" style="background-image:url(/themes/{{$theme}}/assets/img/flag_es.png)"><?= trans($theme.'-app.head.language_es') ?></option>
										<option value="en"><?= trans($theme.'-app.head.language_en') ?></option>
									</select>     */ ?>

									<div class="dropdown">
										  <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<?php foreach(Config::get('app.locales') as $key => $value) { ?>
													@if(\App::getLocale() == $key)

													<a  title="<?= trans($theme.'-app.head.language_'.$key) ?>" href="#">
                                                                                                            <img alt="<?=$key;?>" class="img-responsive" style="display: inline-block" src="/themes/tauleryfau/assets/img/flag_<?= $key ?>.png" width="15px"> <?= trans($theme.'-app.head.language_'.$key) ?>
                                                                                                        </a>
												@endif
												<?php } ?>

    											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dLabel">
												<?php foreach(Config::get('app.locales') as $key => $value) { ?>

													@if(\App::getLocale() != $key)
                                                                                                            <?php
                                                                                                            $url_tiempo_real="/$key/api/subasta/".$data['subasta_info']->lote_siguiente->cod_sub."-".str_slug($data['subasta_info']->lote_siguiente->name)."-".$data['subasta_info']->lote_siguiente->id_auc_sessions;
                                                                                                            ?>
                                                                                                            <li>
                                                                                                                <a  title="<?= trans($theme.'-app.head.language_'.$key) ?>" href="{{$url_tiempo_real}}">
                                                                                                                    <img alt="<?=$key;?>" class="img-responsive" style="display: inline-block" src="/themes/tauleryfau/assets/img/flag_<?= $key ?>.png" width="15px"> <?= trans($theme.'-app.head.language_'.$key) ?>
                                                                                                                </a>
                                                                                                            </li>
                            @endif


                    <?php } ?>


							</ul>
							</div>
									@elseif(\Config::get( 'app.google_translate' ))

											<div id="google_translate_element"></div>

										<script type="text/javascript">
											function googleTranslateElementInit() {
												new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,ca,de,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
											}
										</script>
										<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
									@endif
									</div>
		<header class="enc">
				<nav>
					<div class="nav-content">
						<div class="logo hidden-xs hidden-sm">
							<a class="brand" style="max-width: 300px;" href="{{ \Routing::slug('/') }}"><img class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.png" ></a>
						</div>
						<div class="subasta">
							<div class="logo hidden-md hidden-lg">
								<a class="brand" href="{{ \Routing::slug('/') }}"><img class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.png" ></a>
                            </div>
							<h3 class="auction_number">{{ $data['name'] }}</h3>
						</div>
						<div class="menu">
							@if(!Route::current()->parameter('proyector'))
								<ul>
									<li>
										<div class="audio">
											<img class="audio-btn off" src="/img/icons/volume_off.png" alt="">
										</div>
									</li>
									@if(!Session::has('user'))
										<li>
											<button class="btn btn-primary btn-xs open_own_box" data-ref="login">{{ trans($theme.'-app.login_register.ini_tr') }}</button>
										</li>
										<li>
											<a href="{{ \Routing::slug('login') }}" class="btn btn-primary btn-xs btn-register">{{ trans($theme.'-app.login_register.register') }}</a>
										</li>
									@else
										<li class="group user_session">
											<a href="{{ \Routing::slug('logout') }}/tr" class="btn btn-danger btn-xs" >{{ trans($theme.'-app.login_register.logout') }}</a>
										</li>
										<li class="group user_session hidden-xs">
											<a onclick="toggleFullScreen()" class="btn btn-primary  btn-xs" >{{ trans($theme.'-app.sheet_tr.full_screen') }}</a>
										</li>
										<li class="group user_verified">
											<div>
												<span class="img_verified">
													<span>
														<i class="fa fa-check"></i>
													</span>
												</span>
											</div>
											<div class="u_data">
												<div class="u_name">{{ Session::get('user.name') }}</div>
												<div class="u_verified">{{ trans_choice($theme.'-app.sheet_tr.verified_bidders', 1) }} <span>{{ $data['js_item']['user']['cod_licit'] }}</span></div>
											</div>
										</li>
									@endif
										</ul>
									@endif
									<div class="close-menu hidden-lg hidden-md hidden-sm">
										<i class="fa fa-close"></i>
									</div>
								</div>
								<div class="menu-responsive hidden-sm hidden-lg hidden-md">
									<div class="icon-responsive-live">
										<i class="fa fa-bars"></i>
									</div>
								</div>
							</div>
						</div>


					</nav>
			</header>



    <div>
        @yield('content')
    </div>

    <div class="own_box" data-rel="login">
		<div rel="content">
			<div id="login_box">
				<form id="accerder-user-form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
		            	<div class="form-group has-feedback">
						    <div class="input-group">
						      <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
						      <input data-error="{{ trans($theme.'-app.login_register.write_valid_email') }}" type="email" name="email" class="form-control" placeholder="Email" required>
						    </div>
						    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
						    <div class="help-block with-errors"></div>
						</div>

						<div class="form-group has-feedback">
						    <div class="input-group">
						      <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						      <input maxlength="20" type="password" name="password" class="form-control" placeholder="Password" required>
						    </div>
						    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
						    <div class="help-block with-errors"></div>
						</div>
                                        <span class="message-error-log"></span>
			    		<input onclick="javascript:login_web()" class="btn btn-primary btn-block btnLogin " type="button" value="{{ trans($theme.'-app.login_register.sign_in') }}">
				</form>
			</div>
		</div>
	</div>

	<div id="modalComprar" class="modal-block mfp-hide" data-to="comprarLote">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.buy_lot_question') }}</p>
						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

 @if(Session::has('user') && $data['js_item']['user']['is_gestor'])
	<div id="modalConfigPausada" class="modal-block mfp-hide" data-to="pausarSubastaMinutos">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg"> La subasta se reanudará en: <br /> </p>
                                                   <?php
                                                    $minutes_default = !empty(Config::get('app.default_minuts_pause'))? Config::get('app.default_minuts_pause') : 0;
                                                    $days_default = floor($minutes_default/1440);
                                                    $minutes_default = $minutes_default % 1440;

                                                    $hours_default = floor($minutes_default/60);
                                                    $minutes_default = $minutes_default % 60;

                                                   ?>
                                                    <div class="input-append date " >
                                                            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                                                                <div class="col-xs-4">
                                                                        <div>{{ ucwords (trans($theme.'-app.msg_neutral.days')) }}</div>
                                                                        <select class="form-control pause_auction" id="days_pause" style='padding: 0; padding-left: 3px;'  >
                                                                                @for($dp = 0;$dp < 50; $dp++)
                                                                                <option value="{{$dp}}"  <?=   $dp == $days_default? "selected='selected'" : ""  ?>>{{ $dp }} </option>
                                                                                @endfor
                                                                        </select>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                        <div>{{ ucwords (trans($theme.'-app.msg_neutral.hours')) }}</div>
                                                                        <select class="form-control pause_auction" id="hours_pause" style='padding: 0; padding-left: 3px;'  >
                                                                                @for($hp = 0;$hp < 24; $hp++)
                                                                                    <option value="{{$hp}}" <?=   $hp == $hours_default? "selected='selected'" : ""  ?>>{{ $hp }} </option>
                                                                                @endfor
                                                                        </select>
                                                                </div>
                                                                <div class="col-xs-4">
                                                                        <div>{{ ucwords (trans($theme.'-app.msg_neutral.minutes')) }}</div>
                                                                        <select class="form-control pause_auction" id="minutes_pause" style='padding: 0; padding-left: 3px;'  >
                                                                                @for($mp = 0;$mp < 60; $mp++)
                                                                                        <option value="{{$mp}}"  <?=   $mp == $minutes_default? "selected='selected'" : ""  ?>>{{ $mp }} </option>
                                                                                @endfor
                                                                        </select>
                                                                </div>
                                                            </div>
                                                            <div style="margin-top: 10px;" class="col-xs-12">
                                                                    {{ trans($theme.'-app.sheet_tr.activate_auctions') }} <br>
                                                                    <span id="restart_auc_date" style="font-size: 16px;"> </span>
                                                                    <input id="total_minutes_pause" type="hidden" value="<?= !empty(Config::get('app.default_minuts_pause'))? Config::get('app.default_minuts_pause') : 0 ?>" >
															</div>
															<div style="margin-top: 10px;" class="col-xs-12">
                                                                    <input id="new_status_auction" type="checkbox" value="reload" > Recargar Página usuarios
                                                            </div>
                                                    </div>
                                                    <div class="col-xs-12" style='margin-top: 20px'>
                                                            <button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
                                                            <button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
                                                    </div>
                                        </div>
                                </div>
                        </div>
                </section>
        </div>
 <div id="modalPausarTime" class="modal-block mfp-hide" data-to="pausarSubasta">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.activate_auctions') }}   <br /> </p>
                                                    <div class="input-append date " >
                                                        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                                                            <div class="col-xs-12">
                                                               {{ trans($theme.'-app.user_panel.date') }}
                                                                <input type="text" id="date_pause" value="<?= date('d m Y' ) ?>" data-inputmask="'mask': '99/99/2099'" class="inputmask" style="width:100px">
                                                            </div>


                                                             <div class="col-xs-12">
                                                                 <br>
                                                                {{ trans($theme.'-app.msg_neutral.hour') }}
                                                                <input type="text" id="hour_pause" value="{{ Config::get('app.put_off_auction') }}" data-inputmask="'mask': '99:99:00'" class="inputmask" style="width:70px">
                                                            </div>
                                                        </div>

                                                                                                               </div>
                                                    <div class="col-xs-12" style='margin-top: 20px'>
                                                            <button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
                                                            <button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
                                                    </div>
                                        </div>
                                </div>
                        </div>
                </section>
        </div>
@endif
	<script type="text/javascript">
		$(document).ready(function(){

        $(".form_datetime").datetimepicker({
            //format: "dd/mm/yyyy hh:ii",
            startDate: "<?php echo date('Y/m/d H:i') ?>",
            endDate:   "<?php echo $hastah; ?> 23:59",
            todayHighlight: true,
            language: auction_info.lang_code.toLowerCase(),
            todayBtn: true,
        });
    });
    </script>

	<div id="modalPausada" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg"> {{ trans($theme.'-app.sheet_tr.auction_stopped') }} <br /></p>
						<button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalPendiente" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg"> {{ trans($theme.'-app.sheet_tr.auction_pendiente') }}</p>
						<button class="btn btn-primary modal-dismiss">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalDisconnected" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="txt_loading"> {{ trans($theme.'-app.sheet_tr.auction_disconnected') }}</p>
						<div class="loader"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
    <div id="modalCancelarPujaUser" class="modal-block mfp-hide" data-to="cancelar_puja_user">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.cancel_bid_question') }}</p>

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalActivarAudio" class="modal-block mfp-hide" data-to="activar_audio">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.want_audio_activated') }}</p>

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

    <div id="modalCloseBids" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="txt_loading"> {{ trans($theme.'-app.sheet_tr.loading') }} </p>
                                                <p class="txt_esperando_sala hidden"> {{ trans($theme.'-app.sheet_tr.esperando_sala') }} </p>
						<div class="loader"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
@if(Session::has('user') && $data['js_item']['user']['is_gestor'])
<div id="modal_cancelasignlicit" class="modal-block mfp-hide" data-to="pausar_lote">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="txt_msg">Ha entrado una puja mientras asignabas el licitador</p>

						<button class="btn btn-primary lotPause cancelasignlicit" data-status="P">Aceptar</button>

					</div>
				</div>
			</div>
		</section>
	</div>
	<div id="modalEndLot" class="modal-block mfp-hide" data-to="asign_licit">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<div class="winner_undefined hidden">
							<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.winner_undefined') }}</p>
							<div class="col-sm-6 col-sm-offset-3">
								<input type="text" class="form-control" name="w_undefined" id="w_undefined">
							</div>
							<br>
							<br>
							<br>
						</div>

						<p id="modalEndLot_msg_error" class="error_msg hidden" style="color:#d9534f;">{{ trans($theme.'-app.msg_error.no_licit') }}</p>
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.end_lot_question') }}</p>
						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss cancelasignlicit">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalStart" class="modal-block mfp-hide" data-to="iniciar_subasta">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.start_auction_question') }}</p>
						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>
        <div id="modalLotAbrir" class="modal-block mfp-hide" data-to="abrir_lote">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.open_lot') }}</p>

						<button class="btn btn-primary modal-confirm lotPause" data-status="P">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>
        <div id="modalLotPause" class="modal-block mfp-hide" data-to="pausar_lote">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.pause_lot') }}</p>

						<button class="btn btn-primary modal-confirm lotPause" data-status="P">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalLotPauseReanudar" class="modal-block mfp-hide" data-to="reanudar_lote">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.resume_lot') }}</p>

						<input id="lotOrden" autocomplete="off" class="form-control" type="hidden">
						<br />

						<button class="btn btn-primary modal-confirm lotResume" data-status="N">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div id="modalCancelarPuja" class="modal-block mfp-hide" data-to="cancelar_puja">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.cancel_bid_question') }}</p>

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

        <div id="modalCancelarOrden" class="modal-block mfp-hide" data-to="cancelar_orden">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.cancel_order_question') }}</p>

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>



	<div id="modalLotReanudarList" class="modal-block mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.stopped_lots') }}</p>
						<br />
						<div id="reanudarList">

						</div>
						<br />

						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!-- modelo -->
	<div id="reanudarListModel" style="text-align:left; margin-bottom: 10px;" class="hidden">
		<div class="row">
			<div class="col-lg-10 titulo"></div>
			<div class="col-lg-2 boton"><button class="btn btn-primary reanudarLote" type="button">{{ trans($theme.'-app.sheet_tr.continue') }}</button></div>
		</div>
	</div>

         <div id="modalJumpLot" class="modal-block mfp-hide" data-to="jump_lot">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.jump_to_lots') }}</p>

						<input id="jumpLot" autocomplete="off" class="form-control" type="text">
						<div style="margin-top:10px;">
						<input type="checkbox" id="openLot" name="open_lot" checked>
						<label for="openLot">
							Abrir el lote en el caso de estar cerrado
						</label>
					</div>
                    <br>

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>

        <div id="BajaClient" class="modal-block modal-lg mfp-hide" data-to="baja_client">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
                                            <div class='col-md-12'>
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.resume_licit_baja') }}</p>

						<input id="licit_baja" autocomplete="off" class="form-control" type="text">
						<br />

						<button class="btn btn-primary modal-confirm">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
                                            </div>
                                            <div class='col-md-12'>
                                                <hr>
                                                <span class='text-center'><strong>{{ trans($theme.'-app.sheet_tr.users_bloqued') }}</strong></span>
                                                 <table style="width:100%">
                                                    <thead>
                                                    <tr>
                                                      <th>{{ trans($theme.'-app.sheet_tr.cod_licit') }}</th>
                                                      <th>{{ trans($theme.'-app.sheet_tr.name_licit') }}</th>
                                                      <th>{{ trans($theme.'-app.sheet_tr.cod_cli_licit') }}</th>
                                                      <th>{{ trans($theme.'-app.sheet_tr.reactivar') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class='clientes_baja'></tbody>
                                                  </table>
                                                <div class="loader search-loader" style="display:hide;width: 25px;height: 25px;margin-top:10px"></div>
                                            </div>
					</div>
				</div>
			</div>
		</section>
	</div>

        <div id="AltaClient" class="modal-block mfp-hide" data-to="alta_client">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.resume_alta_client') }}</p>

						<button id="alta_client" class="btn btn-primary modal-confirm" cli_licit="">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>




@endif
	<div id="modalOrdenImporte" class="modal-block mfp-hide" data-to="orderAmount">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text text-center">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.order_amount') }}</p>
						<input id="order_amount" autocomplete="off" class="form-control bid_amount_gestor" type="text">
						<br />
						<button class="btn btn-primary modal-confirm add_order_amount" data-boton="1">{{ trans($theme.'-app.sheet_tr.confirm') }}</button>
						<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.sheet_tr.cancel') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>



	<div id="infoLot" class="modal-block modal-block-lg mfp-hide">
		<section class="panel">
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-text">
						<p class="insert_msg">{{ trans($theme.'-app.sheet_tr.lot') }} <span class="i_lot"></span></p>
						<div class="row">
							<div class="col-lg-4 img">
								<img class="img-responsive" src="">
							</div>
							<div class="col-lg-8 txt">
								<p class="i_title"></p>
								<p class="i_desc"></p>
								<p>
									<span>{{ trans($theme.'-app.sheet_tr.start_price') }}</span>
									<span class="i_imp"></span>
									<span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
								</p>
								<div class="checkbox">
								    <label>
								      <input type="checkbox" class="add_to_fav" data-from="modal"> {{ trans($theme.'-app.sheet_tr.add_to_fav') }}
								    </label>
								</div>
							</div>
						</div>
						<div class="text-center">
							<span class="lot-msg_adjudicado hidden"><b><i class="fa fa-exclamation" aria-hidden="true"></i>  {{ trans($theme.'-app.sheet_tr.awarded') }}</b> <span class="imp_adj"></span></span>
							<span class="lot-msg_ensubasta hidden"><b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans($theme.'-app.sheet_tr.in_auction') }}</b></span>

		                    <button data-from="modal" class="lot-action_comprar btn btn-primary pull-left hidden" type="button" ref="" codsub="{{ $data['subasta_info']->lote_siguiente->cod_sub }}">{{ trans($theme.'-app.sheet_tr.buy') }}</button>

		                <?php /*   <button data-from="modal" class="lot-order_importe btn btn-primary  hidden" type="button">{{ trans($theme.'-app.sheet_tr.import_order') }}</button>  */ ?>
						</div>
						<div >
							<button class="btn btn-primary modal-dismiss pull-right">{{ trans($theme.'-app.sheet_tr.close') }}</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>


	<audio id="new_bid">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/ding.mp3') }}" type="audio/mpeg">
	</audio>
        <audio id="new_ol">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/orden.mp3') }}" type="audio/mpeg">
	</audio>
         <audio id="end_lot">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/fin_lote.mp3') }}" type="audio/mpeg">
	</audio>
        <audio id="alarm_fav_lot">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_fav_lot.mp3') }}" type="audio/mpeg">
	</audio>
	<audio id="alarm_end_lot">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_end_lot.mp3') }}" type="audio/mpeg">
	</audio>
	<audio id="alarm_notification">
	   <source src="{{ URL::asset('vendor/tiempo-real/sounds/alarm_notification.mp3') }}" type="audio/mpeg">
	</audio>

</body>

</html>

<script>

		$('.icon-responsive-live').click(function(){
			$('.menu').addClass('active')
		})
		$('.close-menu').click(function(){
			$('.menu').removeClass('active')
		})

                function login_web(){
            $.ajax({
                    type: "POST",
                    url: '/login_post_ajax',
                    data: $('.ob_disp #accerder-user-form').serialize(),
                    success: function( response )
                    {
                        if(response.status == 'success'){
                              location.reload();
                        }else{
                            $( ".ob_disp #accerder-user-form .message-error-log" ).text('').append(messages.error[response.msg]);
                        }

                    }
                });
        }
	</script>
