@php
	$newsletters = (new \App\Models\Newsletter())->getNewslettersNames();
@endphp

<div id="newsletter_secction" class="newsletter js-newletter-block">
    <div class="container">
        <div class="row">
            <form class="form-inline" id="form-newsletter">


            <div class="col-xs-12 tit_newsletter text-center">{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</div>
            <div class="col-xs-12 newsletter-sub_title text-center">{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</div>
            <div class="col-xs-12  text-center">
                <div class="input-group">
                   <input class="form-control input newsletter-input" name="email" type="text" placeholder="{{trans(\Config::get('app.theme').'-app.emails.write_email')}}">
				   <input type="hidden" name="lang" id="lang" value="<?=\App::getLocale()?>">
				   <input type="hidden" id="condiciones" name="condiciones" value="true">

                    <button id="newsletter-btn" type="button" class="btn btn-custom newsletter-button">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
                </div>
            </div>
            <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-3 list_cat_home_container">

				@foreach ($newsletters->chunk(5) as $chunk)
				<div class="list_cat_home_block">
					@foreach ($chunk as $id_newsletters => $name_newsletters)
                    <li>
						<input type="checkbox" class="newsletter" name="families[{{$id_newsletters}}]" value="{{$id_newsletters}}">
						{{ $name_newsletters }}
					</li>
					@endforeach
				</div>
				@endforeach
            </div>
            </form>
        </div>
    </div>
</div>


