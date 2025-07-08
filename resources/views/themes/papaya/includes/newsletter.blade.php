@php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

<div class="newsletter">

    <div class="container-fluid newsletter-container js-newletter-block">

        <div class="row">
            <div class="col-xs-12 text-center newsletter-title">
                <h1>{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</h1>
            </div>
        </div>

        <form id="form-newsletter">

            <div class="row">
                <div class="col-xs-12 col-md-12" >
                    <div style="max-width: 475px; margin: 0 auto">
                    <div class="newsletter-form">
                        <input  class="input newsletter-input" name="email" placeholder="Introduzca aquí su e-mail..."/>
                        <input id="newsletter-btn" type="button" class="submit" value="{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}"/>
                        <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>" >
                    </div>
                    <div>
                        <label for="accept_new" class="accept_new">
                            <input type="checkbox" name="families[1]" class="form-control" value="1" id="bool__0__comercial" autocomplete="off"/>
                            <span>{{ trans(\Config::get('app.theme').'-app.emails.accept_news') }}</span>
                        </label>
                    </div>
                    <div>
                        <label for="condiciones" class="condicines">
							<input name="condiciones" type="checkbox" id="condiciones" type="checkbox" class="form-control" value="on" autocomplete="off">
							<span><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></a></span>
                        </label>
                    </div>
                </div>
                </div>

            </div>

            <div class="row newsletter-familias">

				@foreach ($newsletters->chunk(3) as $chunk)
				<div class="newsletter-familias-content d-flex alig-items-center justify-content-center">
					<div class="new-news-block">
					@foreach ($chunk as $id_newsletters => $name_newsletters)
					<div class="">
						<label>
							<input type="checkbox" class="newsletter" name="families[{{$id_newsletters}}]" value="{{$id_newsletters}}">
							<div class="custom-check"></div>
							{{$name_newsletters}}
						</label>
					</div>
					@endforeach
					</div>
				</div>
				@endforeach

                {{-- <div class="newsletter-familias-content d-flex alig-items-center justify-content-center">
                    <div class="new-news-block">
                    <div>
						<label for="metalurgico">
							<input type="checkbox" id="metalurgico" name="families[]" value="2">
							<div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.metallurgical') }}
						</label>
					</div>
                    <div><label for="construccion"><input type="checkbox" class="newsletter" id="construccion" name="families[]" value="3"><div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.constuction') }}</label></div>
                    <div><label for="alimentacion"><input type="checkbox" class="newsletter" id="alimentacion" name="families[]" value="4"><div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.feeding') }}</label></div>
                    </div>
                </div>
                <div class="newsletter-familias-content d-flex alig-items-center justify-content-center">
                        <div class="new-news-block">
                    <div><label for="farmaceutico"><input type="checkbox" class="newsletter" id="farmaceutico" name="families[]" value="5"><div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.pharmaceutical') }}</label></div>
                    <div><label for="inmuebles"><input type="checkbox" class="newsletter" id="inmuebles" name="families[]" value="6"><div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.estate') }}</label></div>
                    <div><label for="arte"><input type="checkbox" class="newsletter" id="arte" name="families[]" value="7"><div class="custom-check"></div>{{ trans(\Config::get('app.theme').'-app.foot.art') }}</label></div>
                        </div>
                </div> --}}

            </div>
        </form>

    </div>

</div>
