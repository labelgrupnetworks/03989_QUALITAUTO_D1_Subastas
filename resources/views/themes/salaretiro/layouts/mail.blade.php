<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
        <!--[if gte mso 15]>
        <xml>
            <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
        <![endif]-->
        <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:300,400,700" rel="stylesheet">
    <title>Sala retiro</title> <!-- The title tag shows in email notifications, like Android 4.4. -->
<!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: Roboto !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:100,300,400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>
	body {
		margin: 0;
		padding: 0;
	}
        p.user{
            font-weight: 900;
            font-style: italic;
        }
        p.title_adj{
            font-size: 20px;
            text-align: center;
            font-weight: 900;
        }
        .img-email{
            max-height: 100%;
        }
        
</style>
    </head>

 <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #fff;">  
   <!--[if mso]>
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                            <td valign="top" width="600">
                            <![endif]-->

    <table border="0" width="100%" style="min-width: 20px;max-width: 600px; margin: 0 auto">
  <tr style="background-color: white;">
      <th 
          height="67" 
          colspan="5" 
          style="padding-top:30px;padding-bottom:15px;"
        >
          <a href="{{Config::get('app.url')}}">
              <img 
                  src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/assets/img/logo.png" 
                  alt="Header"
                  class="img-responsive img-email">
          </a>
      </th>
  </tr>
   <!--[if mso]>
                                     </td>
                                     </th>
                                     </tr>
                                     <![endif]-->
  <tr>
    <td colspan="5" style="padding:20px 10px;font-family:'Helvetica',Arial,sans-serif;font-size:14px;color: #6e6d6d;" bgcolor="#fff">
		 @yield('content')
    </td>
  </tr>
  <tr>
       <!--[if mso]>
                                     </td>
                                     </tr>
                                     <![endif]-->
    <td colspan="5">
         <!--[if mso]>
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                            <td valign="top" width="600">
                            <![endif]-->
        <table border="0" width="600" bgcolor="white">
                <tr>
                        <th style="color:grey;font-size:11px;font-family:'Helvetica',Arial,sans-serif;font-weight:normal;padding-top:16px;padding-bottom:16px;">
                                &copy; <?= \Config::get('app.name')?> <?= date('Y'); ?>
                        </th>
                </tr>
        </table>
    </td>
  </tr>
</table>
    <!--[if mso]>
                                     </td>
                                     </tr>
                                     </table>
                                     <![endif]-->
    </body>
</html>
