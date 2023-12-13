<div class="newsletter js-newletter-block">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <div class="tit_newsletter">
                    <h3>{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</h3>
                </div>
                <div class="form-group">
                        <input class="form-control input-lg newsletter-input" type="email" placeholder="E-mail">
                        <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">
                        <input type="hidden" class="newsletter" name="families" value="1">
                        <button id="newsletter-btn" type="button" class="btn-custom btn">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
				</div>

				<div class="form-check mt-2">
					<input
						name="condiciones"
						type="checkbox"
						id="condiciones"
						type="checkbox" class="form-check-input">
				<label class="form-check-label" for="condiciones">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!} <a href="{{Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')}}">({{trans(\Config::get('app.theme').'-app.login_register.more_info')}})</a></label>
				</div>
				<div class="form-check mt-1">
					<input
						name="comercial"
						type="checkbox"
						id="comercial"
						type="checkbox" class="form-check-input">
					<label class="form-check-label" for="comercial">{{ trans(\Config::get('app.theme').'-app.login_register.recibir_newsletter') }}</label>
				</div>

                <ul class="redes d-flex align-items-center justify-content-space-between mt-3">
                    <li><a title="Facebook" href="<?= Config::get('app.facebook') ?>"><i class="fa fa-3x fa-facebook"></i></a></li>
                    <li><a title="Twitter" href="<?= Config::get('app.twitter') ?>">
						@include('components.x-icon', ['size' => '42'])
						</a></li>
                    <li><a title="Instagram" href="<?= Config::get('app.instagram') ?>"><i class="fa fa-3x fa-instagram"></i></a></li>
                    <li><a title="Linkedin" href="<?= Config::get('app.linkedin') ?>"><i class="fa fa-3x fa-linkedin"></i></a></li>
            </ul>
            </div>
        </div>
    </div>
</div>

