<ul class="red">
    <li><a title="{{ trans($theme . '-app.lot.share_email') }}"
            href="http://www.facebook.com/sharer.php?u=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"><i
                class="fa fa-facebook"></i></a></li>
    <li><a title="{{ trans($theme . '-app.lot.share_email') }}"
            href="http://twitter.com/share?text=<?= $data['subasta_info']->lote_actual->titulo_hces1 . ' ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&url=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
			@include('components.x-icon', ['size' => '14'])
			</a>
		</li>
    <li><a title="{{ trans($theme . '-app.lot.share_email') }}"
            href="mailto:?Subject=<?= \Config::get('app.name') ?>&body=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"><i
                class="fa fa-envelope"></i></a></li>
</ul>
