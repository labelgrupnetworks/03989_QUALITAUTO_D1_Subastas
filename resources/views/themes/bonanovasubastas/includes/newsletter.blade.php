<div class="newsletter js-newletter-block">
    <div class="container">
            <div class="row">
                <div class="newsletter-wrapper">
                <div class="section-title">
                    <i class="fa fa-5x fa-envelope"></i>
                            <div class="tit_newsletter">
                                    {{trans($theme.'-app.foot.newsletter_title')}}
                            </div>
                </div>
                <div class="section-input">
                    <div class="input-group">
                        <input class="form-control input-lg newsletter-input" type="text" placeholder="{{trans($theme.'-app.emails.write_email')}}">
                        <div class="input-group-btn">
                            <input type="hidden" id="lang-newsletter" name="lang" value="{{ config('app.locale') }}">
							<input type="hidden" class="newsletter" id="newsletter-input" name="families[]" value="1" >

                            <button id="newsletter-btn" type="button" class="btn btn-lg btn-custom newsletter-input">{{trans($theme.'-app.foot.newsletter_button')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

