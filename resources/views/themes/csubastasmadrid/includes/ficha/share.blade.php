<ul class="red">
    <li>
        <a href="http://www.facebook.com/sharer.php?u=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
            title="{{ trans('web.lot.share_email') }}">
            <x-icon.fontawesome type="brands" icon="facebook-f" version="5" />
        </a>
    </li>
    <li>
        <a href="http://twitter.com/share?text=<?= $data['subasta_info']->lote_actual->titulo_hces1 . ' ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&url=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
            title="{{ trans('web.lot.share_email') }}">
            <x-icon.fontawesome type="brands" icon="x-twitter" version="6" />
        </a>
    </li>
    <li>
        <a href="mailto:?Subject=<?= \Config::get('app.name') ?>&body=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"
            title="{{ trans('web.lot.share_email') }}">
            <x-icon.fontawesome type="solid" icon="envelope" version="5" />
        </a>
    </li>
</ul>
