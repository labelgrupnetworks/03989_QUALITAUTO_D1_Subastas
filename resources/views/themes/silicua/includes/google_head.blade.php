
@if(!empty($cookiesState['google']) || !empty($cookiesState['all']))
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-147754453-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-147754453-1');
</script>
@endif


