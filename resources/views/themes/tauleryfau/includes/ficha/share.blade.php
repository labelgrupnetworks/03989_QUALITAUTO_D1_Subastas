<ul class="red inline-flex">
        <li class="flex valign">
        <a target="_blank" title="{{ trans($theme.'-app.lot.share_email') }}" href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
            <i class="fa fa-envelope"></i>
        </a>
    </li>
        <li class="flex valign">
        <a target="_blank" title="{{ trans($theme.'-app.lot.share_tiwtter') }}" href="http://twitter.com/share?text=<?= $data['subasta_info']->lote_actual->titulo_hces1.' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
            @include('components.x-icon', ['size' => '20'])
        </a>
    </li>
    <li class="flex valign">
        <a target="_blank" title="{{ trans($theme.'-app.lot.share_facebook') }}" href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
            <i class="fab fa-facebook"></i>
        </a>
    </li>
        <li class="flex valign">
        <a target="_blank" title="{{ trans($theme.'-app.lot.share_pinterest') }}"  href="http://pinterest.com/pin/create/button/?media=<?= $data['subasta_info']->lote_actual->titulo_hces1.' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
            <i class="fab fa-pinterest-p"></i>
        </a>
    </li>


</ul>
