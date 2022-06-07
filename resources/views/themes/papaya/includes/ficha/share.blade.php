
<div class="zone-share-social col-xs-12 no-padding d-flex align-items-center">
        <p class="shared m-0">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p>

        <ul class="red ul-format d-flex align-items-center">
        <li>
            <a target="_blank" class=" d-flex align-items-center justify-content-center color-letter" title="{{ trans(\Config::get('app.theme').'-app.lot.share_email') }}" href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fa fa-2x fa-envelope"></i></a>
        </li>
        </ul>

    </div>
