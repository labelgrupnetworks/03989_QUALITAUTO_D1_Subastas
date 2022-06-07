<div class="newsletter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6 text-right">
				<img class="img" src="/themes/{{\Config::get('app.theme')}}/assets/img/mail.png" alt="E-mail">
				<div class="tit_newsletter">
					{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 text-center">
				<div class="input-group">
					<input class="form-control input-lg newsletter-input" type="text">
					<div class="input-group-btn">
                                                <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">                                                
                                                 <input type="hidden" class="newsletter" name="families" value="1">
						<button id="newsletter-btn" type="button" class="btn btn-lg btn-custom newsletter-input">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>

					</div>
				</div>
			</div>
			<div class="hidden-xs hidden-sm col-sm-1">

			</div>
		</div>
	</div>
</div>