<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
        
    </head>
    <body>
<style>
	body {
		margin: 0;
		padding: 0;
	}
</style>
 
<table border="0" width="600">
  <tr>
      <th height="67" colspan="5"><a href="{{Config::get('app.url')}}"><img src="{{Config::get('app.url')}}/themes/{{Config::get('app.theme')}}/img/email/header.jpg" alt="Header"></a></th>
  </tr>
  <tr>
    <td colspan="5" style="padding:20px 10px;font-family:'Helvetica',Arial,sans-serif;font-size:14px;color: #6e6d6d;" bgcolor="#fff">      
        Hello <?= $emailOptions['user'] ?><br><br>

        We are pleased to announce our NEW WEBSITE – <a href="https://www.subarna.net/">www.subarna.net</a><br>

        Enclosed find your new web password in order to bid, consult and process all operations that you can do, both in our new online auctions and in our traditional hammer auction. <br>
       
        <br>
        <strong>USER: </strong><?= $emailOptions['email'] ?><br>

        <strong>PASSOWORD: </strong> <?= $emailOptions['pwd'] ?><br>
        <br>
        To change the password, just enter to your <a href="https://www.subarna.net?view_login=true">SUBARNA ACCOUNT</a> and make all the modifications that you think are convenient. <br>
        <br>
        We are working hard to complete the website in English. Soon you will be able to navigate. Meanwhile, you can translate with google translate on the top site.
        <br><br>
        If you wish to delete the account or have any questions, please contact us at <a href="mailto:subarna@subarna.net">subarna@subarna.net</a> or call us at <a href="tel:+34932175607">932 15 65 18</a>. 
        <br><br>
        Thank you for your trust,<br><br>

        Subarna 
</td>
  </tr>
  <tr>
    <td colspan="5">
		<table border="0" width="600" bgcolor="#f4f4f4">
			<tr>
				<th style="text-align:center;padding-top: 20px;">
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
                            <th style="font-size:11px;font-family:'Helvetica',Arial,sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;"> Any questions? Get in touch <a style="color:#6e6c6d;" href="https://www.subarna.net/es/pagina/contacto"> here</a> with our <br>customer service team.<br><hr style="border-left:none;border-top:none;border-right:none;border-bottom:1px solid #a7966c;height:1px;width:40%;margin:0 auto;margin-top:14px;"></th>
			</tr>
			<tr>
                            <th style="font-size:11px;font-family:'Helvetica',Arial,sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;"><?= trans(\Config::get('app.theme').'-app.emails.footer_ubicacion'); ?><br> <hr style="border-left:none;border-top:none;border-right:none;border-bottom:1px solid #a7966c;height:1px;width:40%;margin:0 auto;padding-top:10px;"></th>
			</tr>
			<tr>
				<th style="font-size:11px;font-family:'Helvetica',Arial,sans-serif;color:#6e6c6d;font-weight:normal;padding-top:10px;">
					<a title="{{ trans(\Config::get('app.theme').'-app.foot.faq') }}" style="color:#6e6c6d;text-decoration:none;" href="<?=Config::get('app.url')."/".\App::getLocale()."/".trans(\Config::get('app.theme').'-app.links.faq') ?>">FAQ</a> |
					<a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" style="color:#6e6c6d;text-decoration:none;" href="<?php echo Config::get('app.url').Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">Privacy Policy</a> |
					<a title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" style="color:#6e6c6d;text-decoration:none;" href="<?php echo Config::get('app.url').Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">Terms and conditions</a> |
					<a title="Perfil" style="color:#6e6c6d;text-decoration:none;" href="{{ Config::get('app.url').\Routing::slug('login') }}">Profile</a>
				</th>
			</tr>
			<tr>
				<th style="color:#a7966c;font-size:11px;font-family:'Helvetica',Arial,sans-serif;font-weight:normal;padding-top:16px;padding-bottom:16px;">
					&copy; Subarna <?= date('Y'); ?>
				</th>
			</tr>
		</table>
    </td>
  </tr>
</table>
    </body>
</html>
	