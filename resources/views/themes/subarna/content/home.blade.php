
{{-- {!! BannerLib::bannerWithView('home-slider', 'fluid') !!} --}}
{!! BannerLib::bannerWithView('home_banner', 'fluid') !!}

{{-- script para botón de mensaje --}}
@if((new App\Services\Content\CookieService)->isAnalysisAllowed())
<script type="text/javascript">
	(function(d, src, c) { var t=d.scripts[d.scripts.length - 1],s=d.createElement('script');s.id='la_x2s6df8d';s.async=true;s.src=src;s.onload=s.onreadystatechange=function(){var rs=this.readyState;if(rs&&(rs!='complete')&&(rs!='loaded')){return;}c(this);};t.parentElement.insertBefore(s,t.nextSibling);})(document,
	'https://subarna.ladesk.com/scripts/track.js',
	function(e){ LiveAgent.createButton('kfxo3hnj', e); });
</script>
@endif

