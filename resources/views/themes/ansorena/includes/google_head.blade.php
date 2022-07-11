@if(\Config::get("app.emp") == '001' )
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-67931-45"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-67931-45');
	</script>
	<!-- Global site tag (gtag.js) - Google Ads: 628918960 -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-628918960"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());  gtag('config', 'AW-628918960');
	</script>

	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-T39XDLQ');</script>
	<!-- End Google Tag Manager -->


	<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '275479850371204');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=275479850371204&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->

@else
<script>
function ga() {
	console.log(arguments.callee.name, arguments);
	return false;
}
</script>
@endif
<?php


/*
<a title="Real Time Web Analytics" href=http://clicky.com/101310093><img src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>

<script>var clicky_site_ids = clicky_site_ids || []; clicky_site_ids.push(101310093);</script>

<script async src="//static.getclicky.com/js"></script>

<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/101310093ns.gif" /></p></noscript>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-67931-45"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-67931-45');
</script>


<!-- Global site tag (gtag.js) - Google Ads: 628918960 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-628918960"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());  gtag('config', 'AW-628918960');
</script>


<!-- Facebook Pixel Code -->
<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '275479850371204');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=275479850371204&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->

	*/
	?>
