<div id="newsletter_secction" class="newsletter">
    <div class="container">
        <div class="row">
            <form class="form-inline" id="form-newsletter">


            <div class="col-xs-12 tit_newsletter text-center">{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</div>
            <div class="col-xs-12 newsletter-sub_title text-center">{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</div>
            <div class="col-xs-12  text-center">
                <div class="input-group">
                   <input class="form-control input newsletter-input" name="email" type="text" placeholder="{{trans(\Config::get('app.theme').'-app.emails.write_email')}}">
				   <input type="hidden" id="lang" value="<?=\App::getLocale()?>">
				   <input type="hidden" id="condiciones" name="condiciones" value="true">

                    <button id="newsletter-btn" type="button" class="btn btn-custom newsletter-button">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
                </div>
            </div>
            <div class="col-xs-10 col-xs-offset-1 col-md-8 col-md-offset-3 list_cat_home_container">

                <div class="list_cat_home_block">
                    <li><input type="checkbox" class="newsletter" name="families[]" value="1">  {{trans(\Config::get('app.theme').'-app.foot.metal')}}</li>
                    <li><input type="checkbox" class="newsletter" name="families[]" value="2">  {{trans(\Config::get('app.theme').'-app.foot.contruction')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="3"> {{trans(\Config::get('app.theme').'-app.foot.food')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="4"> {{trans(\Config::get('app.theme').'-app.foot.transport')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="5">  {{trans(\Config::get('app.theme').'-app.foot.wood')}}</li>

            </div>
            <div class="list_cat_home_block">
                <li><input type="checkbox" class="newsletter" name="families[]" value="6">  {{trans(\Config::get('app.theme').'-app.foot.chemical')}}</li>
                <li><input type="checkbox" class="newsletter" name="families[]" value="7">  {{trans(\Config::get('app.theme').'-app.foot.stocks')}}</li>
                <li><input type="checkbox" class="newsletter" name="families[]" value="8">  {{trans(\Config::get('app.theme').'-app.foot.arts')}}</li>
                <li><input type="checkbox" class="newsletter" name="families[]" value="9">  {{trans(\Config::get('app.theme').'-app.foot.habite')}}</li>
				<li><input type="checkbox" class="newsletter" name="families[]" value="10">  {{trans(\Config::get('app.theme').'-app.foot.others')}}</li>
            </div>
            </div>
            </form>
        </div>
    </div>
</div>


