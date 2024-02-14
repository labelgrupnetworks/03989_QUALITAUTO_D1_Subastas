@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop




@section('content')
<div class="create-account color-letter">
    <div class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-8 col-md-9 col-xs-12 general-container">
                <div id="register-button-back" class="button-back" style="display: none"></div>
                <div class="create-account-container">
                    <div class="title-register">
                        <p class="text-center">{{ trans($theme.'-app.login_register.crear_cuenta') }}</p>
                    </div>
                    <form method="post" class="frmLogin" id="frmRegister-adv" data-toggle="validator">
                        <input class="form-control" type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="create-account-title d-flex justify-content-center">
                            <div class="inputs-type-person d-flex justify-content-space-between">
                                <div class="input-group-addon-custom form-group">
                                    <input 
                                        class="form-control  change_job" 
                                        style="display: none;" 
                                        id="inlineCheckbox1" 
                                        name="pri_emp" 
                                        value="F" 
                                        checked="checked" 
                                        type="radio" 
                                    /> 
                                    <label class="input-check-custom" for="inlineCheckbox1">{{ trans($theme.'-app.login_register.particular') }}</label>
                                </div>
                                <div class="form-group input-group-addon-custom ">
                                    <input class="form-control change_job" style="display: none;" id="inlineCheckbox2" name="pri_emp" value="J" type="radio"> 
                                    <label class="input-check-custom" for="inlineCheckbox2">{{ trans($theme.'-app.login_register.empresa') }}</label>                 
                                </div>
                            </div>
                        </div>
                        <div class="subtitle-register">
                            <p>{{ trans($theme.'-app.login_register.info_personal') }}</p>
                        </div>
                        <div class="create-account-wrapper" style="position:relative">
                            <div class="create-account-personal-info personal-info">

                            <div class="inputs-custom-group d-flex justify-content-space-between flex-wrap">
                                 <div class="hidden">
                                    <label>{{ trans($theme.'-app.login_register.language') }}</label>
                                    <input type="hidden" name="language" value="{{ strtoupper(\App::getLocale()) }}">
                                </div>
                                <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                    <label class="" for="nombre">{{ trans($theme.'-app.login_register.nombre') }}</label>
                                    <input
                                    data-checking= 'true't class="form-control" id="nombre" name="usuario" placeholder="{{ trans($theme.'-app.login_register.nombre') }}" required="" type="text">
                                </div>
                                 <div class="form-group input-group name_client col-xs-12 col-sm-6">
                                     <label class="" for="nombre">{{ trans($theme.'-app.login_register.apellidos') }}</label>
                                    <input
                                    data-checking= 'true' class="form-control" id="last_name" name="last_name" placeholder="{{ trans($theme.'-app.login_register.apellidos') }}" required="" type="text">
                                </div>
                                <div class="form-group input-group hidden rsoc_cli col-xs-12 col-sm-6">
                                    <label class="" for="">{{ trans($theme.'-app.login_register.company') }}</label>
                                    <input
                                    class="form-control" name="rsoc_cli" placeholder="{{ trans($theme.'-app.login_register.company') }}" type="text">
                                </div>
                                <div class="form-group input-group hidden rsoc_cli col-xs-12 col-sm-6">
                                    <label class="" for="">{{ trans($theme.'-app.login_register.contact') }}</label>
                                    <input
                                     class="form-control" name="contact" placeholder="{{ trans($theme.'-app.login_register.contact') }}" type="text">
                                </div>

 

                                <div class="form-group input-group col-xs-12 col-sm-3">
                                    <label class="" for="telefono">{{ trans($theme.'-app.login_register.genre') }}</label>
                                    <select id="sexo" name="sexo" class="form-control">                                   
                                        <option value="H">{{ trans($theme.'-app.login_register.hombre') }}</option>
                                        <option value="M">{{ trans($theme.'-app.login_register.mujer') }}</option>
                        
        
                            </select>
                                </div>      
                                <div class="form-group input-group col-xs-12 col-sm-3">
                                    <label class="" for="telefono">{{ trans($theme.'-app.login_register.phone') }}</label>
                                    <input
                                    data-checking= 'true' 
                                    name="telefono" 
                                    class="form-control" 
                                    placeholder="{{ trans($theme.'-app.login_register.phone') }}" 
                                    required="" 
                                    maxlength="40" 
                                    type="text"
                                    />
                                </div>      
                                <div  class="form-group input-group col-xs-12 col-sm-3">
                                    <label
                                    data-checking= 'true' for="nif" class="dni_txt ">{{ trans($theme.'-app.login_register.dni') }}</label>
                                    <label for="nif" class="cif_txt hidden">{{ trans($theme.'-app.login_register.cif') }}</label>

                                    <input
                                    data-checking= 'true' 
                                        id="dni" 
                                        placeholder="{{ trans($theme.'-app.login_register.dni') }}" 
                                        class="form-control dni" 
                                        size="10" 
                                        name="nif" 
                                        title="Formato del NIF/NIE(12345678A/X1234567A)" 
                                        required="" 
                                        type="text" 
                                    />
                                    </div>
                                    <div class="col-xs-12 col-sm-3 input-group form-group fech_nac" data-provide="datepicker">
                                        <label class="label-date" for="nombre">{{ trans($theme.'-app.user_panel.date_birthday') }}</label>
                                        <input name="date" class="form-control input-custom-group" type="date" required="" id="datetimepicker">
                                    </div> 
                                </div>                                
                                <div class="subtitle-register">
                                    <p>Dirección</p>
                                </div>
                                <div class="create-account-address-container" style="position: relative">
                                    <div class="create-account-principal-address">
                                        <div class="inputs-custom-group  d-flex justify-content-space-between flex-wrap">
                                            <div class="form-group input-group col-xs-12 col-xs-6 col-sm-6 col-md-6">
                                                <label class="">{{ trans($theme.'-app.login_register.pais') }}</label>
                                                <select id="country" name="pais" class="form-control notranslate" required>
                                                    <option value="">{{ trans($theme.'-app.login_register.pais') }}</option>
                                                    @if (!empty($data) && !empty($data["countries"]) )
                                                        @foreach ($data["countries"] as $country)
                                                            <option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group input-group col-xs-12 col-sm-6 col-md-6">
                                                <label class="" for="cpostal">{{ trans($theme.'-app.login_register.cod_postal') }}</label>
                                                <input
                                                data-checking= 'true' 
                                                    id="cpostal" 
                                                    name    ="cpostal" 
                                                    class="form-control" 
                                                    placeholder="{{ trans($theme.'-app.login_register.cod_postal') }}" 
                                                    required="" 
                                                    maxlength="10" 
                                                    type="text" 
                                                />
                                            </div>
                                            <div class="form-group input-group col-xs-12 col-sm-6">
                                                <label class=""  for="Ciudad">{{ trans($theme.'-app.login_register.ciudad') }}</label>
                                                <input
                                                data-checking= 'true' 
                                                    name="poblacion" 
                                                    class="form-control" 
                                                    id="Ciudad" 
                                                    placeholder="{{ trans($theme.'-app.login_register.ciudad') }}" 
                                                    required="" 
                                                    maxlength="30" 
                                                    type="text" 
                                                />
                                                </div>
                                                <div class="form-group input-group col-xs-12 col-sm-6">
                                                    <label class="" for="provincia">{{ trans($theme.'-app.login_register.provincia') }}</label>
                                                    <input
                                                    data-checking= 'true' 
                                                        name="provincia" 
                                                        class="form-control" 
                                                        id="provincia" 
                                                        placeholder="{{ trans($theme.'-app.login_register.provincia') }}" 
                                                        maxlength="30" 
                                                        type="text" 
                                                        />
                                                </div>
                                                <div class="form-group input-group col-xs-12 col-sm-3">
                                                    <label class="" for="provincia">{{ trans($theme.'-app.login_register.via') }}</label>
                                                    <select id="codigoVia" name="codigoVia" class="form-control">
                                                        <option value="">{{ trans($theme.'-app.login_register.via') }}</option>
                                                        @if (!empty($data) && !empty($data["via"]) )
                                                            @foreach ($data["via"] as $via)
                                                                <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group input-group col-xs-12 col-sm-9">
                                                    <label class="" for="direccion">{{ trans($theme.'-app.login_register.direccion') }}</label>
                                                <input
                                                data-checking= 'true' 
                                                    name="direccion" 
                                                    class="form-control" 
                                                    id="direccion" 
                                                    placeholder="{{ trans($theme.'-app.login_register.direccion') }}" 
                                                    required="" 
                                                    maxlength="60" 
                                                    type="text" 
                                                />
                                                </div> 
                                                    @if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
                                                        <label for="second-address" class="d-flex hidden aling-item-center second-address col-xs-12">
                                                        <input
                                                        data-checking= 'true' 
                                                            required 
                                                            type="checkbox"
                                                            id="second-address"
                                                            checked
                                                        />
                                                        <span>{{ trans($theme.'-app.login_register.utilizar_direcc_direccenv') }}</span>
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!empty(Config::get('app.delivery_address')) && Config::get('app.delivery_address') == 1)
                                        <div id="second-address-content" class="create-account-second-address" style="display: none; position: relative">
                                            <div class="inputs-custom-group  d-flex justify-content-space-between flex-wrap">
                                                <div class="col-xs-12 no-padding title-second-address">Direccion de envio</div>
                                                <div class="form-group input-group col-xs-8 col-md-10">
                                                    <label for="country_envio">{{ trans($theme.'-app.login_register.pais') }}</label>
                                                    <select 
                                                        data-like="#country"
                                                        id="country_envio" 
                                                        name="clid_pais" 
                                                        onchange="" 
                                                        class="form-control" 
                                                        required 
                                                    >
                                                    <option value="">{{ trans($theme.'-app.login_register.pais') }}</option>
                                                        @if (!empty($data) && !empty($data["countries"]) )
                                                            @foreach ($data["countries"] as $country)
                                                                <option value="{{ $country->cod_paises }}">{{ $country->des_paises }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group input-group col-xs-4 col-md-2">
                                                    <input
                                                        data-like="#cpostal" 
                                                        id="codigo_postal" 
                                                        name="clid_cpostal" 
                                                        class="form-control" 
                                                        placeholder="{{ trans($theme.'-app.login_register.cod_postal') }}" 
                                                        required="" 
                                                        maxlength="10" 
                                                        type="text" 
                                                    />
                                                    <label for="cpostal_envio">{{ trans($theme.'-app.login_register.cod_postal') }}</label>
                                                </div>
                                                <div class="form-group input-group col-xs-6">
                                                    <input
                                                        data-like="#Ciudad" 
                                                        name="clid_poblacion" 
                                                        class="form-control" 
                                                        id="clid_poblacion" 
                                                        placeholder="{{ trans($theme.'-app.login_register.ciudad') }}" 
                                                        required="" 
                                                        maxlength="30" 
                                                        type="text" 
                                                    />
                                                    <label for="Ciudad_envio">{{ trans($theme.'-app.login_register.ciudad') }}</label>
                                                </div>
                                                <div class="form-group input-group col-xs-6">
                                                    <label >{{ trans($theme.'-app.login_register.provincia') }}</label>            
                                                        <input id="clid_provincia"
                                                            data-like='#provincia'
                                                            name="clid_provincia" 
                                                            class="form-control" 
                                                            id="provincia" 
                                                            maxlength="30" 
                                                            type="text" 
                                                            placeholder="{{ trans($theme.'-app.login_register.provincia') }}"
                                                        />
                                                    </div>
                                                    <div class="form-group input-group col-xs-3">
                                                        <select data-like="#codigoVia" id="clid_codigoVia" name="clid_codigoVia" class="form-control">
                                                            <option value="">---</option>
                                                            @if (!empty($data) && !empty($data["via"]) )
                                                                @foreach ($data["via"] as $via)
                                                                    <option value="{{ $via->cod_sg }}">{{ $via->des_sg }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <label for="codigoVia">{{ trans($theme.'-app.login_register.via') }}</label>
                                                    </div>
                                                    <div class="form-group input-group col-xs-9">
                                                        <input 
                                                            data-like="#direccion"
                                                            name="clid_direccion" 
                                                            class="form-control" 
                                                            id="direccion_envio" 
                                                            placeholder="{{ trans($theme.'-app.login_register.direccion') }}" 
                                                            required="" 
                                                            maxlength="60" 
                                                            type="text" 
                                                        />
                                                        <label for="direccion_envio">{{ trans($theme.'-app.login_register.direccion') }}</label>
                                                    </div>
                                                    <small id="error-address text-danger" style="display:none"></small>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    <?php // ocultamos divisas
                                    /*
                                        <div class="inputs-custom-group  d-flex justify-content-space-between flex-wrap">
                                            <div class="form-group input-group col-xs-6">
                                                <label>{{ trans($theme.'-app.login_register.currency') }}</label>
                                                <select name="divisa" class="form-control notranslate" required>
                                                    @foreach( $data['divisa'] as $key => $value)
                                                        <option value="{{$value->cod_div}}">{{$value->cod_div}}</option>
                                                    @endforeach
                                                </select>
                                            </div></div>
                                    */
                                    ?>
                                    <div class="subtitle-register">
                                        <p>{{ trans($theme.'-app.login_register.credentials') }}</p>
                                    </div>
                                    <div class="inputs-custom-group inputs-account personal-data-inputs flex-wrap">
                                         <label id="erroremail" class="erroremail text-danger col-xs-12 hidden" ></label>
                                         <label id="error-confirm-email" class="error-confirm text-danger col-xs-12" style="display: none">{{ trans($theme.'-app.msg_error.email_confirm') }}</label>
                                    <div class="form-group form-group-custom col-xs-12 col-sm-6">
                                        <label class="" for="email">{{ trans($theme.'-app.login_register.email') }}</label>
                                        <input 
                                        data-checking= 'true'
                                            class="form-control" 
                                            id="email" 
                                            placeholder="{{ trans($theme.'-app.login_register.email') }}" 
                                            name="email" 
                                            required="" 
                                            type="text"
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="emailconfirm">{{ trans($theme.'-app.login_register.email_confirmacion') }}</label>
                                        <input data-match="#email"
                                        data-checking= 'true'
                                            class="form-control" 
                                            id="emailconfirm" 
                                            placeholder="{{ trans($theme.'-app.login_register.email_confirmacion') }}" 
                                            required="" 
                                            type="text" 
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="contrasena">{{ trans($theme.'-app.login_register.password') }}</label>
                                        <input 
                                        data-checking= 'true'
                                        maxlength="20" 
                                        id="password" 
                                        name="password" 
                                        class="form-control" 
                                        placeholder="{{ trans($theme.'-app.login_register.password') }}" 
                                        data-minlength="5" 
                                        required="" 
                                        type="password" 
                                        />
                                    </div>
                                    <div class="form-group form-group-custom col-xs-12 col-xs-6">
                                        <label class="" for="confirmcontrasena">{{ trans($theme.'-app.login_register.confirm_password') }}</label>
                                        <input 
                                        data-checking= 'true'
                                        maxlength="20" 
                                        name="confirm_password" 
                                        class="form-control" 
                                        data-match="#password" 
                                        id="confirmcontrasena" 
                                        placeholder="{{ trans($theme.'-app.login_register.confirm_password') }}" 
                                        required="" 
                                        type="password"
                                        >
                                    </div>
                                    <small id="error-confirm-pass" class="error-confirm text-danger" style="display: none">{{ trans($theme.'-app.msg_error.pass_confirm') }}</small>
                                 </div>
                            </div>
                            <div class="create-account-personal-info create-account-address" style="display: none">

                                            </div>
                                            <div class="buttons-login-content-form-back principal-button col-xs-12 no-padding">
                                                <div class="create-account-accept-condition">
                                                    <label for="i-want-news" class="input-accept col-xs-12">
                                                <input 
                                                data-checking= 'true'
                                                    checked="checked" 
                                                        name="newsletter" 
                                                        type="checkbox"
                                                        class="form-control"
                                                        id="i-want-news"
                                                /> 
                                                <span>{{ trans($theme.'-app.login_register.recibir_newsletter') }}</span>

                                            </label>
                                        <label for="condiciones" class="input-accept col-xs-12">
                                            <input 
                                            data-checking= 'true'
                                                name="condiciones" 
                                                required 
                                                type="checkbox"
                                                class="form-control" 
                                                id="condiciones"
                                            />
                                        
                        <span>{{ trans($theme.'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition') ?>" target="_blank">{{ trans($theme.'-app.login_register.more_info') }}</a>)</span>                                
                    </label>
            </div>
                <div class="buttons col-xs-12">
                    <button id="create-user" class="secondary-button button-create-user">{{ trans($theme.'-app.login_register.finalizar') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div>
                       
    </form>
    <p class="error-form-validation hidden">{{ trans($theme.'-app.login_register.all_fields_are_required') }}</p>



    <script>


        function checkInputText(){
            isVal= false
            $('input').each(function(){
                if($(this).attr('data-checking') === 'true'){
                    if($(this).attr('type') === 'text' || $(this).attr('type') === 'password' || $(this).attr('type') === 'date'){
                        if($(this).val().length === 0){
                            console.log($(this).val().length,$(this).attr('id') )
                            isVal = false
                            $('.button-create-user').removeClass('active')
                            return false
                        }
                        isVal = true
                        
                        
                    }

                }

                
            })

            if(isVal && $('#condiciones').prop('checked')){
                $('.button-create-user').addClass('active')
            }
        }

        $('input').blur(function(){
            checkInputText()
        })

        $( ".change_job" ).click(function() {
            checkInputText()

        })

        $('#condiciones').click(function(){
            checkInputText()
        })
    </script>
@stop



