<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0 "); // Proxies.
header("X-Frame-Options:     DENY");
?>
<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">
<title>

    @if( !empty($data['seo']->meta_title) )
        {{$data['seo']->meta_title}}
    @else
        {{ trans(\Config::get('app.theme').'-app.head.title_app') }}
    @endif


</title>
<script>
 //fecha servidor
var fecha_server = <?= getdate()[0] * 1000?> ;
//fecha del sistema del usuario
var fecha_js = new Date().getTime();
</script>
<link rel="shortcut icon" href="<?='/themes/'.$theme.'/img/favicon.ico'?>" />
@if( env('APP_DEBUG'))
    <meta name="robots" content="noindex">
@elseif( !empty($data['seo']->noindex_follow) && $data['seo']->noindex_follow == true )
    <meta name="robots" content="noindex,follow">
@endif
@if(!empty($data['seo']->meta_title))
<meta name="title" content="<?= $data['seo']->meta_title ?>">
@else
<meta name="title" content="{{ trans(\Config::get('app.theme').'-app.head.title_app') }}">
@endif
@if(!empty($data['seo']->meta_description))
    <meta name="description" content="<?= $data['seo']->meta_description ?>">
@else
    <meta name="description" content="{{ trans(\Config::get('app.theme').'-app.head.meta_description') }}">
@endif

@if(!empty($data['seo']->canonical))
    <?php  $var_http = isset($_SERVER['HTTPS'])? "https://" : "http://"; ?>
    <link rel="canonical" href="{{$var_http.$data['seo']->canonical}}" />
@endif
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/owl.carousel.min.css" >
<link rel="stylesheet" type="text/css" href="/css/owl.theme.default.min.css" >
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css" >
<link rel="stylesheet" type="text/css" href="/css/jquery.jqzoom.css" >
<link rel="stylesheet" type="text/css" href="/css/hint.css" >
<link rel="stylesheet" type="text/css" href="/css/jquery.ezdz.min.css" >
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.css') }}" />
<!-- Magnific popup -->
<link rel="stylesheet" href="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.css') }}" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
<!-- Common Javascript -->
<link href="{{ URL::asset('/themes/'.$theme.'/style.css') }}?a={{rand()}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ URL::asset('/themes/'.$theme.'/responsive.css') }}" type="text/css" >
<link href="https://fonts.googleapis.com/css?family=Rubik:400,500,700,900" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/themes/'.$theme.'/dist/slippry.css') }}" >

<!-- Common Javascript -->
<script>

    var traduction_large = {<?= trans(\Config::get('app.theme')."-app.global.month_large"); ?>};
    var traductions = {<?= trans(\Config::get('app.theme')."-app.global.month"); ?>};


        <?php
        //cargamos la moneda para que se puedan hacer lso calculos en js
            $curency_usd = new \App\libs\Currency;
            $curency_usd->currency(0,'USD',\Config::get('app.money'));
        ?>
        var exchanges = {'{{$curency_usd->getCod()}}': {{$curency_usd->getExchange()}}};

	var routing = {
		subastashome: 	"{{ \Routing::slug('subastashome') }}",
		login: 			"{{ \Routing::slug('login') }}",
		registro: 		"{{ \Routing::slug('registro') }}",
		subasta: 		"{{ \Routing::slug('subasta') }}",
	};

	var messages = {
		'error': {
                        @if(is_array(trans(\Config::get('app.theme')."-app.msg_error")))
                            <?php foreach (trans(\Config::get('app.theme')."-app.msg_error") as $key => $value) : ?>
                                    '<?php echo $key; ?>': '<?php echo $value;?>',
                            <?php endforeach; ?>
                       @endif
		},

		'success': {
                        @if(is_array(trans(\Config::get('app.theme')."-app.msg_success")))
                            <?php foreach (trans(\Config::get('app.theme')."-app.msg_success") as $key => $value) : ?>
                                    '<?php echo $key; ?>': '<?php echo $value;?>',
                            <?php endforeach; ?>
                        @endif
		},

		'neutral': {
                        @if(is_array(trans(\Config::get('app.theme')."-app.msg_neutral")))
                            <?php foreach (trans(\Config::get('app.theme')."-app.msg_neutral") as $key => $value) : ?>
                                    '<?php echo $key; ?>': '<?php echo $value;?>',
                            <?php endforeach; ?>
                        @endif
		}

	};
</script>





<script src="{{ URL::asset('vendor/jquery/jquery-2.2.2.min.js') }}"></script>
<script src="{{ URL::asset('js/common.js') }}?a=<?= rand(); ?>"></script>
<script src="{{ URL::asset('js/actions.js') }}?a=<?= rand(); ?>"></script>
<script src="{{ URL::asset('vendor/tiempo-real/popup/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/pnotify.custom.js') }}"></script>
<script src="{{ URL::asset('vendor/tiempo-real/pnotify/modernizr.js') }}"></script>
<script src="{{ URL::asset('vendor/jquery-countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ URL::asset('vendor/timeago/timeago.js') }}"></script>
<script src="{{ URL::asset('js/validator.js') }}"></script>
<script src="{{ URL::asset('js/jquery.cookie.js') }}"></script>
<script src="{{ URL::asset('js/jquery.ezdz.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap/3.3.6/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('vendor/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('/themes/'.$theme.'/customized_tr_main.js') }}?a=<?= rand(); ?>" ></script>
<script src="{{ URL::asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.jqzoom-core.js') }}"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ URL::asset('/themes/'.$theme.'/custom.js') }}?a=<?= rand(); ?>"></script>
<script src="{{ URL::asset('vendor/zoom/jquery.imageLens.js') }}"></script>
<script src="{{ URL::asset('/themes/'.$theme.'/dist/slippry.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('js/numeral.js') }}"></script>
<script src="{{ URL::asset('js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('js/forms.js') }}"></script>


<script>
     //si pasan la variable view_login = true se mostrara el login
    var view_login = <?= !empty(Request::input('view_login'))?Request::input('view_login') : 'false';  ?>   ;
    var resolution_web = $( window ).width();

    if(view_login == true){

        if(resolution_web >= 1200) {
            view_login= true;
        }else{
            view_login = false;
        }
    }
</script>



<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
