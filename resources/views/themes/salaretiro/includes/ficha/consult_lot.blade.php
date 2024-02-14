    <div class="col-xs-12 col-md-12 info_single">
        <div class="info_single_title col-xs-12">
            @if($lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->subc_sub == 'H')
                {{ trans($theme.'-app.subastas.consult_disp') }} 
            @else
                {{ trans($theme.'-app.lot.lot-price-consult') }}
            @endif
        </div>
         @if($lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->subc_sub == 'H')
            <div class="info_single_title col-xs-12">
                <p class="pre">{{  trans($theme.'-app.subastas.price_sale') }}</p>
                            <div class="pre">
                                    {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.subastas.euros') }}
                            </div>
            </div>
         @endif
        <form id="send-consult-lot">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">                    
            <input type="hidden" value="{{$lote_actual->ref_asigl0}}" required name="lot">
            <input type="hidden" value="{{$lote_actual->cod_sub}}" required name="subasta">
            <div class="col-xs-12 col-sm-6 form-group">
                <label>{{trans($theme.'-app.user_panel.name') }}</label>
                <input type="text" class="form-control" required="" name="name">
            </div>
            <div class="col-xs-12 col-sm-6 form-group" >
                <label>{{trans($theme.'-app.user_panel.phone') }}</label>
                <input type="text" class="form-control" required="" name="telf">
            </div>
            <div class="col-xs-12 form-group">
                <label>{{trans($theme.'-app.user_panel.email') }}</label>
                <input type="email" class="form-control" required="" name="email">
            </div>
            <div class="col-xs-12 form-group">
                <label>{{trans($theme.'-app.lot.commentary') }}</label>
            <textarea required class="form-control" rows='4' name="comentario"></textarea>
            </div>
            <div class="col-xs-12">
            <div id="html_element" ></div>
            </div>
            <div class="col-xs-12">
                <div class="checkbox">
                <input 
                    name="condiciones" 
                    required 
                    type="checkbox"
                    class="form-control" 
                    id="recibir-newletter"
                     style="margin-left: 0;"
                />
                <label for="recibir-newletter">
                    <?= trans($theme.'-app.login_register.read_conditions') ?>                                
                </label>
            </div>
            </div>
            <div class="col-xs-12 ol-sm-5 col-lg-4">
                
                <button id="buttonSend" disabled class="btn btn-contact" type="submit">{{trans($theme.'-app.login_register.send_email') }}</button>
            </div>
        </form>

        <p><strong id="respuesta-consult-lot" class="hidden" > </strong></p>

        
    </div>


    <script type="text/javascript">
        
    var verifyCallback = function(response) {
        $('#buttonSend').attr('disabled', false)

      };
        
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6LerYVYUAAAAAMN8H9Qh--lWvJVGhRk4mv1NcdW9',
          'callback' : verifyCallback
        });
      };
    </script>