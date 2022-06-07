<div class="newsletter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
                <div class="newsletter-tittle">{{ trans(\Config::get('app.theme').'-app.foot.newsletter_title') }}</div>
                <p class="newsletter-subtittle">{{ trans(\Config::get('app.theme').'-app.foot.newsletter_description') }}</p>
            </div>
            <div class="col-xs-12 col-sm-6  col-md-5 col-md-offset-1 col-lg-6 col-lg-offset-1 col-xs-offset-0 newsletter-control-input">
                <div class="newsletter-placeholder">
                    {{ trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}
                </div>
                <form>
                <input class="form-control input-lg newsletter-input" type="text" placeholder="">
                <input type="hidden" id="lang-newsletter" value="<?= strtoupper(\App::getLocale())?>" >
                <input type="hidden" class="newsletter" id="newsletter-input" name="families" value="1" >
                <button id="newsletter-btn" type="button" class="button-principal button-newsletter">{{ trans(\Config::get('app.theme').'-app.foot.newsletter_button') }}</button>

                <br><br><br>

                <small><i>“<b>RESPONSABLE:</b>  María de los Ángeles Benayas García / 02505153Q / 914461926 / C/ Donoso Cortés 38, 28015 Madrid | <b>FINALIDAD:</b> Gestionar su suscripción a nuestro boletín informativo | <b>DERECHOS:</b> Acceso, rectificación, supresión y portabilidad de sus datos, de limitación y oposición a su tratamiento, así como, a no ser objeto de decisiones basadas únicamente en el tratamiento automatizado de sus datos, cuando procedan. | <b>INFORMACIÓN ADICIONAL:</b> Puede consultar información adicional y detallada sobre nuestra Política de Privacidad en <a href="https://www.subastasgalileo.es/es/pagina/politica-de-privacidad">https://www.subastasgalileo.es/es/pagina/politica-de-privacidad</a>”</i></small>
                <br><br>

                <div class="check_term row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" name="condiciones" value="on" id="bool__1__condicionesA">
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                    </div>
                </div>

                <br>

                <div class="check_term row">
                    <div class="col-xs-2 col-md-1">
                        <input type="checkbox" name="condiciones2" value="on" id="bool__1__condicionesB">
                    </div>
                    <div class="col-xs-10 col-md-11">
                        <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions2') ?></label>
                    </div>
                </div>
</form>
                <br><br>

            </div>
        </div>
    </div>
</div>
