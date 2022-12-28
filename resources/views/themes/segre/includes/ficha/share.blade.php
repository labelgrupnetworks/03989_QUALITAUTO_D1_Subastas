
<?php
$titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;

?>
<div class="zone-share-social col-xs-12 no-padding d-flex justify-content-space-between">
        <p class="shared">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p>

        <ul class="red ul-format d-flex align-items-center">
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.lot.share_facebook') }}" href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-facebook-f"></i></a>
        </li>
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.lot.share_twitter') }}" href="http://twitter.com/share?text=<?= $titulo.' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-twitter"></i></a>
        </li>
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.lot.share_instagram') }}" href="https://www.instagram.com/subastassegre/"><i class="fab fa-instagram"></i></a>
        </li>
        <li>
            <a class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.lot.share_email') }}" href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fas fa-envelope-open"></i></a>
        </li>
        </ul>

    </div>
