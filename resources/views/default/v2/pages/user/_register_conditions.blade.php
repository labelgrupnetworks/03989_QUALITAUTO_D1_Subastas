<div class="row mb-5">
    <div class="col-md-4">
        <h4>{{ trans('web.login_register.conditions') }}</h4>
    </div>
    <div class="col-md-8">
        <div class="row gy-1">
            <div class="col-12">
                <label class="form-check-label">
                    {!! $formulario->newsletter !!}
                    {{ trans('web.login_register.recibir_newsletter') }}
                </label>
            </div>
            <div class="col-12">
                <label class="form-check-label">
                    {!! $formulario->condiciones !!}
                    <span class="fw-bold text-danger">*</span>
                    <span>
                        {!! trans('web.login_register.read_conditions') !!}
                        (<a href='{{ Routing::translateSeo('pagina') . trans('web.links.term_condition') }}'
                            target="_blank">{{ trans('web.login_register.more_info') }}
                        </a>)
                    </span>
                </label>
            </div>

            <div class="col-12">
                <p class="captcha-terms">
                    {!! trans('web.global.captcha-terms') !!}
                </p>
            </div>

        </div>
    </div>
</div>
