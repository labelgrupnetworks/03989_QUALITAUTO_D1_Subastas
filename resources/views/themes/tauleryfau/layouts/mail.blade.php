<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Tauler & Fau Mail</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   </head>
<style>
	body {margin: 0; padding: 0; min-width: 100%!important;}
        .content {width: 100%; max-width: 600px;}
        .header {padding: 40px 30px 20px 30px;}
        .innerpadding {padding: 30px 30px 30px 30px;}
    .borderbottom {border-bottom: 1px solid #f2eeed;}
        .h2 {padding: 0 0 15px 0; font-size: 20px; line-height: 28px; font-weight: bold; color: gray;}
    .bodycopy {font-size: 16px; line-height: 22px;}
    .footer {padding: 20px 30px 15px 30px;}
.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
.footercopy a {color: #ffffff; text-decoration: underline;}
@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
body[yahoo] .buttonwrapper {background-color: transparent!important;}
body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important; display: block!important;}
}

        @media only screen and (min-device-width: 601px) {
            .content {width: 600px !important;}
}
a{
  text-decoration: none  ;
}
</style>
<body yahoo bgcolor="white">
    <!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <![endif]-->
            <table class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="header" style="text-align: center;" bgcolor="#f1ece6">
                        <a href="{{Config::get('app.url')}}">
                            <img class="img-responsive" src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/assets/img/logo_email.png"  alt="{{(\Config::get( 'app.name' ))}}"  style="max-width: 60%;"/>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="innerpadding borderbottom">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    @yield('content')
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="footer" bgcolor="#283747">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" class="footercopy">
                                    &copy; <?= \Config::get('app.name')?> <?= date('Y'); ?><br/>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding: 20px 0 0 0;">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="37" style="text-align: center; padding: 0 10px 0 10px;">
                                                <a href="<?= \Config::get('app.facebook') ?>">
                                                    <img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/assets/img/fb.png"  alt="{{(\Config::get( 'app.name' ))}}"  style="max-width: 100%;" width="37" height="37" alt="Facebook" border="0" />
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <!--[if (gte mso 9)|(IE)]>
        </td>
    </tr>
</table>
<![endif]-->
</body>
</html>







