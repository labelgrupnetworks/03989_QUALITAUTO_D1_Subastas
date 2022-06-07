<form class="" id="form-newsletter" method="POST">
<div class="newsletter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
				<div class="newsletter-wrapper">
					<div class="newsletter-icon-title">
						<i class="fa hide fa-2x fa-envelope"></i>
						<div class="tit_newsletter">
							{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}
						</div>
					</div>
					<div class="input-group ">
                                                <div style="display: -webkit-box;display: -ms-flexbox;display: flex">
                                                    <input class="form-control newsletter-input" name="email" type="text">
                                                    <input type="hidden" id="lang-newsletter" name="lang" value="<?=\App::getLocale()?>">
                                                    <button id="newsletter-btn" type="button" class="btn btn-custom newsletter-input">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
                                                </div>

                                                <div class="group-check-categories">

                                                        <label for="joyas"><input type="checkbox" class="newsletter" id="joyas" name="families[]" value="1">{{trans(\Config::get('app.theme').'-app.home.jewelry')}}</label>
                                                        <label for="arte"><input type="checkbox" class="newsletter" id="arte" name="families[]" value="2">{{trans(\Config::get('app.theme').'-app.home.art')}}</label>

                                                </div>

					</div>
                        <div class="check_term box">
                            <label for="accept_new">
				<input
				name="accept_news"
				required
				type="checkbox"
				class="form-control"
				id="accept_new"
                                /><span style="color: #555">{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</span>
                            </label>
                        </div>

			<div class="check_term box">
                            <label for="condiciones">
				<input
				name="condiciones"
				required
				type="checkbox"
				class="form-control"
				id="accept_new_condiciones"
                                /><span style="color: #555"><?= trans(\Config::get('app.theme').'-app.emails.privacy_conditions') ?></a></span>
                            </label>
                        </div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
