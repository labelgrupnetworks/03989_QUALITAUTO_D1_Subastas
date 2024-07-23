
@if(!Session::has('user'))
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-register" role="document">
        <div class="signin d-flex">
			<div class="signin-content signin-no-account">
				<div class="banner-signin">
					{!! \BannerLib::bannersPorKey('signin-banner', 'login-banner', ['arrows' => false, 'dots' => false, 'autoplay' => true, 'autoplaySpeed' => 4000]) !!}
				</div>
				<div class="no-account-wrapper d-flex align-items-center justify-content-center h-100 w-100">
					<div>
                        <div class="signin-title">
                            <div class="seo_h1"><?= trans($theme.'-app.login_register.no_account') ?></div>
                        </div>
						<div class="text-center">
							<a title="{{ trans($theme.'-app.login_register.register') }}" class="btn-signup flex valign" href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a>
						</div>
					</div>
				</div>
            </div>
            <div class="signin-content">

				<div class="login-close-wrapper text-right custom-close-modal">
					<a type="button" data-dismiss="modal" aria-label="Close"><i style="font-size: 23px; cursor: pointer" class="far fa-window-close"></i></a>
				</div>

				<div class="login-wrapper">

					<div class="signin-title">
						{{ trans("$theme-app.login_register.welcome") }}
					</div>

					<div class="signin-subtitle">
						{{ trans($theme.'-app.login_register.generic_name') }}
					</div>

					<form data-toggle="validator" id="accerder-user-form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="input-group">
							<input required class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
							<input required class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
						</div>
						<p><span class="message-error-log text-danger"></span></p>
						<button class="btn-signin btn-color btn-login-desktop" id="accerder-user"  type="button"><div class="loader mini" style="display:none;"></div><span>{{ trans($theme.'-app.login_register.acceder') }}</span></button>
						<div class="options_login">

							<?php /* Activar cuenta. Desactivado hasta que se defina un comportamiento concreto
							<small data-dismiss="modal" aria-label="Close" class="activate_account c_bordered" data-ref="{{ \Routing::slug('activate_account') }}" id="activate_account" data-title="{{ trans($theme.'-app.login_register.activate_account')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.activate_account')}}</small>
							*/ ?>
							<div class="remenber-pass">
							<p data-dismiss="modal" aria-label="Close" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</p>
							</div>
						</div>
					</form>
				</div>

            </div>
        </div>
    </div>
</div>
@endif

<?php /*
<div id="loginResponsive">
    <div class="contentLogin">
        <a class="closeBtn" id="closeResponsive" title="Cerrar" href="javascript:;">
            <img src="/themes/{{$theme}}/assets/img/shape.png" alt="Cerrar">
        </a>
        <div class="title_login">
            <?= trans($theme.'-app.login_register.login') ?>
        </div>
        <form id="accerder-user-form-responsive">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" maxlength="20" >
            </div>
            <p>
                <a onclick="close_modal_session();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a><
            </p>
            <h5 class="message-error-log text-danger"></h5></p>
            <button id="accerder-user-responsive" class="btn btn-registro" type="button">Enviar</button>
        </form>
        <div class="title_login mt30">
            {{ trans($theme.'-app.login_register.no_account') }}
        </div>
        <a href="{{ \Routing::slug('login') }}" class="btn btn-registro-responsive">{{ trans($theme.'-app.login_register.register') }}</a>
    </div>
</div>
 */ ?>



 <div class="modal fade " id="modalAjax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-title">Modal Header</div>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="newsletterModal" class="modal-block mfp-hide">
	<section class="panel">
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-text text-center maxw">
					<p class="insert_msg"></p>
					<button class="btn btn-default modal-dismiss">{{ trans($theme.'-app.home.confirm') }}</button>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
    <div class="modal-body">
      <!-- content dynamically inserted -->
    </div>
  </div>
</div>
</div>
<div id="modalMensajeWeb" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title">X</p></span><br/>
                                       <p id="insert_msgweb"></p><br/>

                                            <button  class=" btn btn-color button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>

<div id="modalFotosGrid" class="container modal-block mfp-hide ">
	<div class="modal-sub-w">
		<section class="panel">
			<div class="panel-body">
				<div class="text-center modal-wrapper">
					<div class="single_item_content_">
						<p id="insert_msgweb" class="text-left"></p>
						<button
							class=" btn btn-color button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div id="modalMensaje" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>
                                       <p id="insert_msg"></p><br/>

                                            <button  class=" btn btn-color button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>
<div id="modalPujarFicha" class="container modal-block mfp-hide ">
            <div   data-to="pujarLoteFicha" class="modal-sub-w">
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content_">
                                                <p class="class_h1">{{ trans($theme.'-app.lot.confirm_bid') }}</p><br/>
                                                <span for="bid" class='desc_auc'>{{ trans($theme.'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                <span class="ref_orden hidden"></span>
                                                </br>
                                                    <button id="confirm_orden_lotlist" class="btn btn-color button_modal_confirm btn-custom">{{ trans($theme.'-app.lot.confirm') }}</button>
                                                    <div class='mb-10'></div>
                                                     <div class='mb-10'></div>
                                                    <ul class="items_list">
                                                        <li><?=trans($theme.'-app.lot.tax_not_included')?> </li>

                                                    </ul>
                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>
<div id="modalDisconnected" class="modal-block mfp-hide">
        <section class="panel">
                <div class="panel-body">
                        <div class="modal-wrapper">
                                <div class="modal-text text-center">
                                        <p class="txt_loading"> {{ trans($theme.'-app.lot.connect_to_serv') }}</p>
                                        <div class="loader"></div>
                                </div>
                        </div>
                </div>
        </section>
</div>

<div id="modalActivateAccount" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>
                                       <p id="insert_msg">{{ trans($theme.'-app.msg_error.email_exist') }}</p><br/>

                                        <button  class=" btn btn-color button_modal_confirm modal-dismiss btn-custom" onclick="javascript:sendRecoveryPassword();">
                                            {{ trans($theme.'-app.msg_neutral.activar_cuenta') }}
                                        </button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>
