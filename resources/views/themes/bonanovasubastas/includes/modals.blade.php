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
				<input type="text" name="email" class="form-control" autocomplete="username" placeholder="{{ trans($theme.'-app.login_register.user') }}">
			</div>
			<div class="form-group">
				<input maxlength="20" name="password" type="password" class="form-control" autocomplete="current-password" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}">
			</div>
                        <p><a onclick="close_modal_session();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a></p>
			<h5 class="message-error-log text-danger"></h5></p>
                        <button id="accerder-user-responsive" class="btn btn-registro btn-color" type="button"><span>{{ trans($theme.'-app.login_register.acceder') }}</span><div style="display: none;" class="loader mini"></div></button>
                        @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
                        <br>
                        <p style="margin-top:1rem;"><a class="subalia-button" href="/{{\Config::get('app.locale')}}/login/subalia">{{ trans($theme.'-app.login_register.register_subalia') }} {{ trans($theme.'-app.login_register.here') }}</a></p>
                        <br>
                        @endif
		</form>
		<div class="title_login mt30">
                        {{ trans($theme.'-app.login_register.no_account') }}
		</div>
                <a href="{{ \Routing::slug('register') }}" class="btn btn-registro-responsive btn-color">{{ trans($theme.'-app.login_register.register') }}</a>
	</div>
</div>
<div class="modal fade " id="modalAjax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
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
                                        <span class="class_h1"><p id="insert_msg_title"></p></span><br/>
                                       <p id="insert_msgweb"></p><br/>

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>

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

                                            <button  class=" btn button_modal_confirm modal-dismiss btn-custom">{{ trans($theme.'-app.lot.accept') }}</button>

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
                                                    <button id="confirm_orden_lotlist" class="btn button_modal_confirm btn-custom">{{ trans($theme.'-app.lot.confirm') }}</button>
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
