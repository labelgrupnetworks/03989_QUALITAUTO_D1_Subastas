<style>
    #centerimg img {
    display:block;
    margin:auto;
    margin-bottom: 25px;
}
</style>
<div class="container">
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div id="centerimg">
            <img class="logo-company" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
            </div>
            <form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <div class="input-login-group">
                            <i class="fa fa-user"></i>
                            <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="input-login-group">
                            <i class="fa fa-key"></i>
                            <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                            <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                        </div>
                    </div>
                    <span class="message-error-log text-danger seo_h5"></span></p>
                    <div class="pass-login-content flex-display align-items-end">
                        <div style="width: 132px">
                        <button id="accerder-user" class="button-principal" type="button">
                            <div>{{ trans($theme.'-app.login_register.acceder') }}</div>
                        </button>
                        </div>
                    </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
