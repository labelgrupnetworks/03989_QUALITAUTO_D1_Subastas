
<div class="zone-share-social col-xs-12 justify-content-space-between">
        <p class="shared">{{ trans($theme.'-app.lot.share_lot') }}</p>

        <ul class="red ul-format d-flex align-items-center">
        <li>
            <a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_facebook') }}" href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-facebook-f"></i></a>
		</li>
		<li >
			<a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" href="https://instagram.com/duransubastas/" >
				<i class="fab fa-instagram"></i>
			</a>
		</li>


		<li>
            <a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_twitter') }}" href="http://twitter.com/share?text=<?= $data['subasta_info']->lote_actual->titulo_hces1 ?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
				@include('components.x-icon', ['size' => '17'])
			</a>
		</li>
		<?php /*
		<li>
            <a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_whatsapp') }}" href="whatsapp://send?text=<?= $data['subasta_info']->lote_actual->titulo_hces1." ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-whatsapp"></i></a>
		</li>


        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_pinterest') }}" href="https://pinterest.com/pin/create/link?description=<?= $data['subasta_info']->lote_actual->titulo_hces1.' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-pinterest"></i></a>
		</li>
		<li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_google_plus') }}" href="https://plus.google.com/share?url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-google-plus"></i></a>
		</li>
		*/
		?>

        <li>
            <a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans($theme.'-app.lot.share_email') }}" href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fas fa-envelope-open"></i></a>
        </li>
        </ul>

    </div>
