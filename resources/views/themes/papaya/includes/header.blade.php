<?php
$lang = Config::get('app.locale');

$empre = new \App\Models\Enterprise;
$empresa = $empre->getEmpre();
?>


<!-- login header -->
<header>
    <div class="navbar-top">



        <div class="container">

            <div class="row">
                <div class="col-xs-12">
                    <div class="navbar-top-wrapper d-flex justify-content-space-between">
                        <div class="navbar-info">
                            <span><i class="fa fa-phone"></i>
                                <a class="color-white font-ligther" title="<?= !empty($empresa->tel1_emp) ? $empresa->tel1_emp : ''; ?>" href="tel:{{$empresa->tel1_emp}}"><?= !empty($empresa->tel1_emp) ? $empresa->tel1_emp : ''; ?></a>
                            </span>
                            <span><i class="fa fa-envelope"></i></span><span><a class="color-white font-ligther" title="<?= !empty($empresa->email_emp) ? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp) ? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp) ? $empresa->email_emp : ''; ?></a>
                            </span>
                        </div>
                        <div class="search-component hidden-xs">
                            <form role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form formsearch">
                                <div class="form-group">
                                    <input class="form-control input-custom" placeholder="{{ trans
                                    (\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
                                </div>
                                <button type="submit" class="btn btn-custom-search d-flex alig-items-center justify-content-center"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/search.png" width="16px"   /><div class="loader search-loader" style="display:none;position: absolute;top: -62.50px;right:-1px;width: 25px;height: 25px;"></div></button>

                            </form>
                        </div>
                        <div class="navbar-user">
                            <ul>
                                @if(!Session::has('user'))
                                <li class="hidden-xs hidden-sm" style="margin-right: 5px"><a class="register-home button-principal" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/icon.register.png" width="25px"  /> {{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li>
                                <li class="hidden-xs hidden-sm m-0"><a class="btn_login secondary-button" title="<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>" href="javascript:;"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/icon.user.png" width="18px"  /> <?= trans(\Config::get('app.theme') . '-app.login_register.login') ?></a></li>
                                <li class="hidden-lg hidden-md sesion-responsive"><a title="Login" class="login" href="javascript:;"><i class="fa fa-user fa-lg"></i></a></li>
                                @else
                                <li class="hidden-xs hidden-sm"><a class="button-principal btn-logged" href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a></li>
                                <li class="hidden-lg hidden-md sesion-responsive "><a href="{{ \Routing::slug('user/panel/orders') }}" ><i class="fa fa-user fa-lg"></i></a></li>

                                @if(Session::get('user.admin'))
                                <li class="hidden-xs hidden-sm"><a class="button-principal btn-logged" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a> </li>
                                <li class="hidden-lg hidden-md sesion-responsive btn-logged"><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a></li>
                                @endif
                                <li class="hidden-xs hidden-sm"><a class="button-principal btn-logged" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a></li>
                                @endif
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">




        <div class="row mt-1">
			<div class="col-xs-12">
				<p class="text-center m-0">
					<span id="this-moment"></span>
				</p>
			</div>
			<script>reloadDate();</script>


        </div>
        <div class="row nav-header d-flex align-items-center">
            <!-- Nav Logo  -->


            <div class=" hidden-lg col-xs-6 d-flex align-items-center">
                <div class="menu-responsive">
                    <div role="button" class="menu-text color-letter ml-2 "><i class="fa fa-bars"></i></div>

                </div>
                <div class="hidden-md hidden-lg logo-header img-responsive d-flex alig-items-center justify-content-flex-start">
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                        <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.webp"  alt="{{(\Config::get( 'app.name' ))}}">
                    </a>
                </div>
            </div>

            <div class="col-xs-8 col-md-2 hidden-xs hidden-sm  ">
                <div class="logo-header img-responsive d-flex alig-items-center justify-content-flex-start">
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                        <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.webp"  alt="{{(\Config::get( 'app.name' ))}}">
                    </a>
                </div>
            </div>
            <div class="col-lg-10 col-xs-2 menu-header">


                <div class="menu-principal">
                    <ul class="menu-principal-content d-flex justify-content-between align-items-center" style="margin: 0">
                        <span role="button" class="close-menu-reponsive hidden-lg">{{ trans(\Config::get('app.theme').'-app.head.close') }}</span>
                        <?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>  ?>
                        <li class="flex-display">

                            <a class="flex-display link-header justify-center align-items-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
                                <span class="nav-item">{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
                            </a>
                        </li>
                        <li class="flex-display">
                            <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.about_us') ?>">
                                <span class="nav-item">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</span>
                            </a>
                        </li>
                        <?php
                        $subastaObj = new \App\Models\Subasta();

                        $has_subasta = $subastaObj->auctionList('S', 'O');
                        if (empty($has_subasta) && Session::get('user.admin')) {
                            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
                        }
                        ?>

                        @if(!empty($has_subasta))
                        <li>
                            <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-online') }}">
                                <span class="nav-item">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</span>
                            </a>
                        </li>
                        @endif

                        <?php
                        $has_subasta = $subastaObj->auctionList('S', 'V');
                        if (empty($has_subasta) && Session::get('user.admin')) {
                            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'V'));
                        }
                        ?>
                        @if(!empty($has_subasta))
                        <li><a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('venta-directa') }}">
                                <span class="nav-item">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo('administradores-concursales') ?>">
                                <span class="nav-item">{{trans(\Config::get('app.theme') . '-app.foot.bankruptcy_administrators')}}</span>
                            </a>
                        </li>
                        <li>
                            <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact2')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme') . '-app.links.contact')) ?>"><span class="nav-item">{{ trans(\Config::get('app.theme').'-app.foot.contact2')}}</span></a>
                        </li>
                        <li class="flex-display">
                            <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme') . '-app.links.faq')) ?>">
                                <span class="nav-item">{{trans(\Config::get('app.theme') . '-app.foot.help')}}</span>
                            </a>
                        </li>



                        @if(!Session::has('user'))
                        <li class="hidden-md hidden-lg"><a class="color-brandr flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/icon.register.png" width="20px" class="hidden-xs hidden-sm"  /> {{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li>
                        <li class="hidden-md hidden-lg"><a class=" color-brand  flex-display link-header justify-center align-items-center btn_login" title="<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>" href="javascript:;"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/icon.user.png" width="16px" class="hidden-xs hidden-sm"  /> <?= trans(\Config::get('app.theme') . '-app.login_register.login') ?></a></li>
                        @else
                        <li class="hidden-md hidden-lg"><a class="color-brand flex-display link-header justify-center align-items-center " href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a></li>

                        @if(Session::get('user.admin'))
                        <li class="hidden-lg hidden-md"><a class="color-brand flex-display link-header justify-center align-items-center" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a> </li>
                        @endif
                        <li class="hidden-lg hidden-md"><a class="text-danger flex-display link-header justify-center align-items-center " href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a></li>
                        @endif
                        <div class="col-xs-4 col-sm-2 col-md-1 p-0 f-right icons-container">
                            <div class="lenguaje icons-container">
                                <ul class="f-right">
                                    @foreach(Config::get('app.locales') as $key => $value)
                                    <li class="icons-language">
                                        <a title="<?= trans(\Config::get('app.theme') . '-app.head.language_es') ?>" href="/<?= $key; ?>">
                                            <img alt="<?= $key; ?>" class="img-responsive f-right" src="/themes/{{\Config::get('app.theme')}}/assets/img/flag_<?= $key; ?>.png" width="20px"/>
                                        </a>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>


                    </ul>
                </div>
            </div>


        </div>
    </div>

</header>



<div class="menu-principal-search d-flex align-items-center justify-content-center">
    <form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
        <div class="form-group">
            <input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
        </div>
        <button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
    </form>
</div>

<div class="login_desktop" style="display: none" >
    <div class="login_desktop_content">
        <div class="only-login white-background">
            <div class="login-content-form">
                <img class="closedd" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Close">
                <div class="login_desktop_title">
                    <?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>
                </div>
                <form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <div class="input-login-group">
                            <i class="fa fa-user"></i>
                            <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}" type="email" name="email" type="text">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="input-login-group">
                            <i class="fa fa-key"></i>
                            <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                            <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                        </div>
                    </div>
                    <span class="message-error-log text-danger seo_h5"></span></p>
                    <div class="pass-login-content">
                        <div class="text-center">
                            <button id="accerder-user" class="button-principal" type="button">
                                <div>{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</div>
                            </button>
                        </div>
                        <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a>

                    </div>
                </form>
                <div class="login-separator"></div>
                <p class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.not_account') }}</p>
                <div class="create-account-link">
                    <a class="" title="{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.registration') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    var u = window.location.pathname.split('/')
    var e = u[2]
    var menuItems = $('.menu-principal-content').find('li')

    menuItems.each(function () {

        $(this).find('a').attr('href')
        var link = $(this).find('a').attr('href').includes(e)
        if (link) {
            $(this).find('a').addClass('color-brand')
        }
    })
</script>
