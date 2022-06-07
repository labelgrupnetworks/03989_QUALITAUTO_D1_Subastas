

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!-- NAME: BALCLIS NEWSLETTER -->
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
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

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
    <!-- <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:100,300,400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:100,300,400,700');
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: 'Roboto', sans-serif;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            .email-container {
                min-width: 320px !important;
            }
        }
        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
            }
        }
        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            .email-container {
                min-width: 414px !important;
            }
        }

    </style>
    <!-- CSS Reset : END -->
<!-- Reset list spacing because Outlook ignores much of our inline CSS. -->
<!--[if mso]>
<style type="text/css">
 @import url('https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:100,300,400,700');

ul,
ol {
margin: 0 !important;
}
li {
margin-left: 30px !important;
}
li.list-item-first {
margin-top: 0 !important;
}
li.list-item-last {
margin-bottom: 10px !important;
}
</style>
<![endif]-->

    <!-- Progressive Enhancements : BEGIN -->
    <style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Roboto:100,300,400,700');
p{
margin:10px 0;
padding:0;
}
table{
border-collapse:collapse;
}
h1,h2,h3,h4,h5,h6{
display:block;
margin:0;
padding:0;
}
img,a img{
border:0;
height:auto;
outline:none;
text-decoration:none;
}
body,#bodyTable,#bodyCell{
height:100%;
margin:0;
padding:0;
width:100%;
}
.mcnPreviewText{
display:none !important;
}
#outlook a{
padding:0;
}
img{
-ms-interpolation-mode:bicubic;
}
table{
mso-table-lspace:0pt;
mso-table-rspace:0pt;
}
.ReadMsgBody{
width:100%;
}
.ExternalClass{
width:100%;
}
p,a,li,td,blockquote{
mso-line-height-rule:exactly;
}
a[href^=tel],a[href^=sms]{
color:inherit;
cursor:default;
text-decoration:none;
}
p,a,li,td,body,table,blockquote{
-ms-text-size-adjust:100%;
-webkit-text-size-adjust:100%;
}
.ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
line-height:100%;
}
a[x-apple-data-detectors]{
color:inherit !important;
text-decoration:none !important;
font-size:inherit !important;
font-family:inherit !important;
font-weight:inherit !important;
line-height:inherit !important;
}
.templateContainer{
max-width:660px !important;
}
a.mcnButton{
display:block;
}
.mcnImage,.mcnRetinaImage{
vertical-align:bottom;
}
.mcnTextContent{
word-break:break-word;
}
.mcnTextContent img{
height:auto !important;
}
.mcnDividerBlock{
table-layout:fixed !important;
}
@media only screen and (max-width: 480px){
.columnWrapper{
max-width:100% !important;
width:100% !important;
}

} @media only screen and (max-width: 480px){
body,table,td,p,a,li,blockquote{
-webkit-text-size-adjust:none !important;
}

} @media only screen and (max-width: 480px){
body{
width:100% !important;
min-width:100% !important;
}

} @media only screen and (max-width: 480px){
.mcnRetinaImage{
max-width:100% !important;
}

} @media only screen and (max-width: 480px){
.mcnImage{
width:100% !important;
}

} @media only screen and (max-width: 480px){
.mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
max-width:100% !important;
width:100% !important;
}

} @media only screen and (max-width: 480px){
.mcnBoxedTextContentContainer{
min-width:100% !important;
}

} @media only screen and (max-width: 480px){
.mcnImageGroupContent{
padding:9px !important;
}

} @media only screen and (max-width: 480px){
.mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
padding-top:9px !important;
}

} @media only screen and (max-width: 480px){
.mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
padding-top:18px !important;
}

} @media only screen and (max-width: 480px){
.mcnImageCardBottomImageContent{
padding-bottom:9px !important;
}

} @media only screen and (max-width: 480px){
.mcnImageGroupBlockInner{
padding-top:0 !important;
padding-bottom:0 !important;
}

} @media only screen and (max-width: 480px){
.mcnImageGroupBlockOuter{
padding-top:9px !important;
padding-bottom:9px !important;
}

} @media only screen and (max-width: 480px){
.mcnTextContent,.mcnBoxedTextContentColumn{
padding-right:18px !important;
padding-left:18px !important;
}

} @media only screen and (max-width: 480px){
.mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
padding-right:18px !important;
padding-bottom:0 !important;
padding-left:18px !important;
}

} @media only screen and (max-width: 480px){
.mcpreview-image-uploader{
display:none !important;
width:100% !important;
}

} 
    /* What it does: Hover styles for buttons */
    .button-td,
    .button-a {
        transition: all 100ms ease-in;
    }
    .button-td-primary:hover,
    .button-a-primary:hover {
        background: #555555 !important;
        border-color: #555555 !important;
    }

    /* Media Queries */
    @media screen and (max-width: 480px) {

        /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
        .fluid {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* What it does: Forces table cells into full-width rows. */
        .stack-column,
        .stack-column-center {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            direction: ltr !important;
        }
        /* And center justify these ones. */
        .stack-column-center {
            text-align: center !important;
        }

        /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
        .center-on-narrow {
            text-align: center !important;
            display: block !important;
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
        }
        table.center-on-narrow {
            display: inline-block !important;
        }

        /* What it does: Adjust typography on small screens to improve readability */
        .email-container p {
            font-size: 17px !important;
        }
    }

    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<!--
The email background color (#222222) is defined in three places:
1. body tag: for most email clients
2. center tag: for Gmail and Inbox mobile apps and web versions of Gmail, GSuite, Inbox, Yahoo, AOL, Libero, Comcast, freenet, Mail.ru, Orange.fr
3. mso conditional: For Windows 10 Mail
-->
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background: white !important;">  
    <center style="width: 100%; background: white;"> 
        <div style="max-width: 680px; margin: 0 auto;" class="email-container">
            <!--[if mso]>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="40">
            <tr>
            <td>
            <![endif]-->

        <!-- Email Body : BEGIN -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="90%" style="margin: 0 auto;">
            <!-- Email Header : BEGIN -->
                <tbody>
                    <tr>
                        <td style="padding: 20px 0; text-align: left; border-bottom: 1px solid #AE986D">
                    <img src="{{Config::get('app.url')}}/themes/balclis/assets/img/logo.png" width="100" alt="alt_text" border="0" style="height: auto; background: #fff; font-size: 15px; line-height: 15px; color: #555555;">
                </td>
            </tr>
                </tbody>
            </table>
        </div>
    </center>

    
    
    @yield('content')   
    
    @if(empty($emailOptions['hidden_footer']))
    <?php
    $subasta = new App\Models\Subasta;
    $bloques = new App\Models\Bloques;
    $key = "mas_reciente_email";
    $replace = array(
          'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,'emp' => Config::get('app.emp') ,
    );
    
    $grid_lotes = $bloques->getResultBlockByKeyname($key,$replace);
    if(empty($grid_lotes)){
        $grid_lotes = array();
    }
    ?>
    
    <center style="width: 100%; background-color: #fff;">   

        <div 
            style="max-width: 780px; margin: 0 auto;" 
            class="email-container">
             <!--[if mso]>
             <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="680">
             <tr>
                 <td>
                 <![endif]-->

             <!-- Email Body : BEGIN -->
             <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: white;margin: 0 auto;">
             <!-- Email Header : BEGIN -->
                 <tbody>             
                    <tr align="center">
                        <td style="padding: 20px;     font-family: 'Playfair Display',serif; font-size: 20pt; line-height: 20px; color: #555555; text-align: center; padding-top: 65px; padding-bottom: 0px;">
                            <h2 style="text-align: center; margin: 0 0 30px; font-size: 25px; line-height:20pt; color: #333333; font-weight: bold;">{{ trans(\Config::get('app.theme').'-app.mis_compras.mas_recient') }}</h2>
                        </td>
                    </tr>

                 </tbody>
             </table>
         </div>
     </center>
        
        
        <center style="width: 100%; background-color: #fff;">   
        
            <div class="editable-block clearfix ng-scope" editable-block="eb-16054758" data-eb-name="2 columnas Imagen/Texto" data-eb-classes="[[&quot;dgf-col-2img-2txt&quot;]]">
                <table class="outer" align="center" style="background: white;padding: 10px 0 40px;border-spacing: 0; font-family: 'Roboto', sans-serif; color: #333333; border-collapse: collapse; width: 100%; Margin: 0 auto; max-width: 780px;">
                    <tbody>
                        <tr class="plain-background" style="text-align: left;">
                            <td class="two-column inner" style="padding: 35px 15px; font-size: 0; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]>
                               <table cellpadding="0" cellspacing="0" border="0" style="mso-cellspacing: 0px; mso-padding-alt: 0px; max-width: 680px;">
                                  <tr>
                                 <![endif]-->
                                
                                @php($count = 0)

                                                                            @foreach($grid_lotes as $bann)
                                                                                <?php
                                                                                $url_friendly = !empty($bann->webfriend_hces1)? $bann->webfriend_hces1 :  str_slug($bann->titulo_hces1);
                                                                                $url_friendly = Config::get('app.url').\Routing::translateSeo('lote').$bann->sub_asigl0."-".$bann->id_auc_sessions.'-'.$bann->id_auc_sessions."/".$bann->ref_asigl0.'-'.$bann->num_hces1.'-'.$url_friendly.$emailOptions['UTM'];
                                                                                ?>
                                        <!--[if mso]>
                                     <td width="150" height="168" valign="top">
                                        <![endif]-->
                                
                                    
                                    <div class="column" style="width: 100%; max-width: 150px; display: inline-block; vertical-align: top; font-size: 13px;">
                                       <table width="100%" style="border-spacing: 0; font-family: 'Roboto', sans-serif;color: #333333; border-collapse: collapse; width: 100%;">
                                          <tbody>
                                             <tr style="text-align: left;">
                                                <td class="inner" style="padding: 0px; padding-left:  10px;">
                                                   <table style="border-spacing: 0; font-family: 'Roboto', sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                                      <tbody>
                                                         
                                                         <tr>
                                                             <td class="center" style="padding: 0; text-align: center;">
                                                                
                                                                 <a style="" href="<?= $url_friendly ?>">
                                                                <!--[if mso]>
                                                                <table style="width: 150px; height: 150px"><tr><td width="150" height="150" style="font-family: 'Roboto', sans-serif; overflow: hidden">
                                                                <img width="100%" height="150" src="https://www.balclis.com/img/{{$subasta->getloteImg($bann)}}" alt="ITEAMS" style="max-width: 100%;height: 150px;max-height: 150px;overflow: hidden;width: auto;position: relative;"></td></tr></table>
                                                                <div style="display:none">
                                                                <![endif]-->
                                                                <img src="{{Config::get('app.url')}}/img/load/lote_medium/{{$subasta->getloteImg($bann)}}"  width="100%" height="150" border="0" alt="alt_text" class="" 
                                                                style="max-width: 100%;height: 150px; max-height: 150px; overflow: hidden;width: auto;    position: relative;                                                                                                 
                                                                ">
                                                                <!--[if mso]>
                                                                </div>
                                                                <![endif]-->
                                                                </a>
                                                             </td>
                                                         </tr>
                                                          
                                                        <tr>
                                                            <td>
                                                                <!--[if mso]>
                                                                <table height= "10px" style="height: 18px;max-height: 18px;width: 100%; overflow: hidden"><tr><td height="18" style="font-family: 'Roboto', sans-serif; overflow: hidden"valign="top" >
                                                                    <span style="font-family: 'Roboto', sans-serif;font-size: 14px; text-align:left;overflow:hidden;width: 136px;display: inline-block;word-break: break-all;word-wrap:  normal;">{{$bann->titulo_hces1}}</span><span style="width: 14px;display: inline-block;float: right">...</span>    
                                                                </td></tr></table>
                                                                <![endif]-->
                                                                <!--[if mso]>                                        
                                                                    <div style="display:none">
                                                                <![endif]-->
                                                                <div style="font-family: 'Roboto', sans-serif;height: 15px ; overflow: hidden; margin-top: 10px;   text-align: left;">
                                                                    <span style="font-family: 'Roboto', sans-serif;text-align:left;overflow:hidden;width: 136px;display: inline-block;word-break: break-all;word-wrap:  normal;">{{$bann->titulo_hces1}}</span><span style="width: 14px;display: inline-block;float: right">...</span>
                                                                </div>
                                                                  <!--[if mso]>
                                                                    </div>
                                                                <![endif]-->
                                                                  
                                                            </td>
                                                            
                                                         </tr>
                                                          
                                                           

                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                                                           
                                        <!--[if (gte mso 9)|(IE)]>
                                     </td>
                                         <![endif]-->
                                      @php($count = $count + 1)
                                                                            @endforeach  
                                                                            <!--[if (gte mso 9)|(IE)]>
                                  </tr>
                               </table>
                               <![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </center>
    
    
    
    
    
    
    
        
        <center style="width: 100%; background-color: #fff;">   
        
            <div class="editable-block clearfix ng-scope" editable-block="eb-16054758" data-eb-name="2 columnas Imagen/Texto" data-eb-classes="[[&quot;dgf-col-2img-2txt&quot;]]">
                <table class="outer" align="center" style="background: white;padding: 10px 0 40px; border-top: 1px solid #ccc; border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%; Margin: 0 auto; max-width: 780px;">
                    <tbody>
                        <tr class="plain-background" style="text-align: left;">
                            <td class="two-column inner" style="padding: 35px 15px; font-size: 0; text-align: center;">
                                <!--[if (gte mso 9)|(IE)]>
                               <table width="100%" cellpadding="0" cellspacing="0" border="0" style="mso-cellspacing: 0px; mso-padding-alt: 0px;">
                                  <tr>
                                     <td width="285" valign="top" style="height: 10px;">
                                        <![endif]-->
                                <div class="column" style="width: 100%; max-width: 220px; display: inline-block; vertical-align: top; font-size: 13px;">
                                   <table width="100%" style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                        <tbody>
                                            <tr style="text-align: left;">
                                                <td class="inner" style="padding:5px">
                                                    
                                                    
                                                    <table style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="center" style="padding: 0; text-align: center;">
                                                                    <a style="position: relative;display: block"  href="{{Config::get('app.url')}}{{ \Routing::translateSeo('blog') }}<?=$emailOptions['UTM']?>" >                            

                                                                        <img src="{{Config::get('app.url')}}/themes/<?=\Config::get('app.theme')?>/img/email/img_email_blog_<?= strtoupper(Config::get('app.locale'))?>.jpg"  width="220" height="" border="0" alt="alt_text" class="center-on-narrow" style="max-width: 100%;max-height: 200px; position: relative; width: auto !important;">
                                                                            
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                   
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                    <!--[if (gte mso 9)|(IE)]>
                                 </td>
                                 <td width="285" valign="top">
                                    <![endif]-->
                                    <div class="column" style="width: 100%; max-width: 220px; display: inline-block; vertical-align: top; font-size: 13px;">
                                       <table width="100%" style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                          <tbody>
                                             <tr style="text-align: left;">
                                                <td class="inner" style="padding:5px">
                                                   <table style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                                      <tbody>
                                                         <tr>
                                                            <td class="center" style="padding: 0; text-align: center;">
                                                                                    <a style="position: relative;display: block" href="{{Config::get('app.url')}}{{ Routing::translateSeo('subastas').trans(\Config::get('app.theme').'-app.links.todas_categorias') }}<?=$emailOptions['UTM']?>&order=fecalta">                            

                                                                                        <img src="{{Config::get('app.url')}}/themes/<?=\Config::get('app.theme')?>/img/email/img_email_recientes_<?= strtoupper(Config::get('app.locale'))?>.jpg"  width="220" height="" border="0" alt="alt_text" class="center-on-narrow" style="max-width: 100%;max-height: 200px; position: relative; width: auto !important;">


                                                                                    </a>
                                                            </td>
                                                         </tr>

                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <!--[if (gte mso 9)|(IE)]>
                                 </td>
                                 <td width="285" valign="top">
                                    <![endif]-->
                                    <div class="column" style="width: 100%; max-width: 220px; display: inline-block; vertical-align: top; font-size: 13px;">
                                       <table width="100%" style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                          <tbody>
                                             <tr style="text-align: left;">
                                                <td class="inner" style="padding: 5px">
                                                   <table style="border-spacing: 0; font-family: sans-serif; color: #333333; border-collapse: collapse; width: 100%;">
                                                      <tbody>
                                                         <tr>
                                                             <td class="center" style="padding: 0; text-align: center;">
                                                                 <a style="position: relative;display: block"  href="{{Config::get('app.url')}}<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.valorar_producto').$emailOptions['UTM']?>">                            
                                                                                        <img src="{{Config::get('app.url')}}/themes/<?=\Config::get('app.theme')?>/img/email/img_email_tasar_<?= strtoupper(Config::get('app.locale'))?>.jpg"  width="220" border="0" alt="alt_text" class="center-on-narrow" style="max-width: 100%;max-height: 200px; position: relative; width: auto !important;">

                                                                                    </a>
                                                             </td>
                                                         </tr>

                                                      </tbody>
                                                   </table>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                        <!--[if (gte mso 9)|(IE)]>
                                     </td>
                                  </tr>
                               </table>
                               <![endif]-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </center>
    @endif
        <center style="width: 100%; background-color: #fff;">        
            <div style="max-width: 780px; margin: 0 auto;" class="email-container">
                <tr>
                    <td style="background-color: #ffffff;">                
                        <table bgcolor="#f4f4f4" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                

                                
                                
                                <tr>
                                    
                                    
                                    <th style="text-align:center;padding: 10px;padding-top: 20px;">
                                        <a style="margin-right:10px;" title="Instagram" href="<?= Config::get('app.instagram') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/Insta.png" alt="Instagram"></a>
                                        <a style="margin-right:10px;" title="Pinterest" href="<?= Config::get('app.pinterest') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/pinterest.png" alt="Pinterest"></a>
                                        <a style="margin-right:10px;" title="Facebook" href="<?= Config::get('app.facebook') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/faceb.png" alt="Facebook"></a>
                                        <a style="margin-right:10px;" title="Youtube" href="<?= Config::get('app.youtube') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/youtube.png" alt="Youtube"></a>
                                        <a style="margin-right:10px;" title="Twitter" href="<?= Config::get('app.twitter') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/twitter.png" alt="Twitter"></a>
                                        <a style="margin-right:10px;" title="Google Plus" href="<?= Config::get('app.googleplus') ?>"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/google.png" alt="Google Plus"></a> <br>
                                        <hr style="border-left:none;border-top:none;border-right:none;border-bottom:1px solid #a7966c;height:1px;width:70%;margin:0 auto;margin-top:15px;">
                                    </th>
                                </tr>
                                <tr>
                                    <th style="font-size:11px;font-family: 'Roboto', sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;"><?= trans(\Config::get('app.theme').'-app.emails.footer_text'); ?><br><hr style="border-left:none;border-top:none;border-right:none;border-bottom:1px solid #a7966c;height:1px;width:40%;margin:0 auto;margin-top:14px;"></th>
                                </tr>
                                <tr>
                                    <th style="font-size:11px; font-family: 'Roboto', sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;"><?= trans(\Config::get('app.theme').'-app.emails.footer_ubicacion'); ?> | <?= trans(\Config::get('app.theme').'-app.emails.footer_ubicacion_madrid'); ?><br> <hr style="border-left:none;border-top:none;border-right:none;border-bottom:1px solid #a7966c;height:1px;width:40%;margin:0 auto;padding-top:10px;"></th>
                                </tr>
                                <tr>
                                    <th style="font-size:11px; font-family: 'Roboto', sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;">
                                        <a title="{{ trans(\Config::get('app.theme').'-app.foot.faq') }}" style="color:#6e6c6d;text-decoration:none;" href="<?=Config::get('app.url')."/".\App::getLocale()."/".trans(\Config::get('app.theme').'-app.links.faq').$emailOptions['UTM'] ?>">{{ trans(\Config::get('app.theme').'-app.foot.faq') }}</a> |
                                        <a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" style="color:#6e6c6d;text-decoration:none;" href="<?php echo Config::get('app.url').Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy').$emailOptions['UTM']?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a> |
                                        <a title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" style="color:#6e6c6d;text-decoration:none;" href="<?php echo Config::get('app.url').Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition').$emailOptions['UTM']?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a> |
                                        <a title="Perfil" style="color:#6e6c6d;text-decoration:none;" href="{{ Config::get('app.url').\Routing::slug('login') }}<?=$emailOptions['UTM']?>">{{ trans(\Config::get('app.theme').'-app.foot.perfil') }}</a>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="color:#a7966c;font-size:11px;   font-family: 'Roboto', sans-serif;font-weight:normal;padding-top:16px;padding-bottom:16px;">
                                        &copy; <?= \Config::get('app.name')?> <?= date('Y'); ?>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </div>
        </center>  
    </body>
</html>



