<div class="newsletter">
    <div class="container">
        <div class="row">
        <div class="col-xs-12">
            <div class="newsletter-wrapper">
                <div class="newsletter-block col-xs-6">
                    <div class="icon img-circle">
                        <img src="/themes/{{\Config::get('app.theme')}}/assets/img/envelope.jpg">
                    </div>
                    <div class="tit_newsletter ">
                        <p>{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</p>
                        <span>Reciba las ultimas....</span>
                    </div>
                </div>
                <div class="newsletter-block col-xs-6">
                                <div class="input-group">
                            <input class="form-control input-lg newsletter-input" type="text">
                            <div class="input-group-btn">
                                    <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">                                                
                                     <input type="hidden" class="newsletter input-lg" name="families" value="1">
                                    <button id="newsletter-btn" type="button" class="btn btn-lg btn-custom newsletter-input btn-secondary">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>

                        </div>
                    </div>
                </div>
            </div> 
        </div>
        </div>
    </div>
    
    
</div>