<section class="share-auction-component">
    <p class="share-auction-title">{{ trans("$theme-app.subastas.shared_auctions") }}</p>

    <ul class="share-auction-list">
        <li class="btn-color"><a title="{{ trans(Config::get('app.theme') . '-app.lot.share_facebook') }}"
                href="http://www.facebook.com/sharer.php?u=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
                <i class="fa fa-facebook"></i>
            </a>
        </li>
        <li class="btn-color">
            <a title="{{ trans(\Config::get('app.theme') . '-app.lot.share_twitter') }}"
                href="http://twitter.com/share?url=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>&amp;text=<?= $data['auction']->des_sub ?>&url=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">

                @include('components.x-icon', ['size' => '14'])
            </a>
        </li>
        <li class="btn-color">
            <a title="{{ trans(\Config::get('app.theme') . '-app.lot.share_email') }}"
                href="mailto:?Subject={{ trans(\Config::get('app.theme') . '-app.head.title_app') }}&body=<?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>"><i
                    class="fa fa-envelope"></i></a>
        </li>
    </ul>
</section>
