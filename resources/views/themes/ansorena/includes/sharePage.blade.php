
<div class="zone-share-social col-xs-12 no-padding d-flex justify-content-space-between">
        <p class="shared"></p>

        <ul class="red ul-format d-flex align-items-center">
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_facebook') }}" href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-facebook-f"></i></a>
        </li>
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_twitter') }}" href="http://twitter.com/share?text=<?= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-twitter"></i></a>
		</li>
		<li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_pintarest') }}" href="http://www.pinterest.com/pin/create/button/?&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&description=<?= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>"><i class="fab fa-pinterest"></i></a>
		</li>
		<li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_instagram') }}" href="https://www.instagram.com/ansorena1845/" target="_blank"><i class="fab fa-instagram"></i></a>
        </li>
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_email') }}" href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fas fa-envelope-open"></i></a>
		</li>
		{{-- https://www.instagram.com/?url=https://auctions-ansorena.labelgrup.com/es/lote/SUBTEST-1641-1641/1-6-pruebas-labelgroup-active --}}

		<li>
            <a   class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_whatsapp') }}" href="whatsapp://send?text=https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-whatsapp"></i></a>
        </li>
	</ul>



    </div>
