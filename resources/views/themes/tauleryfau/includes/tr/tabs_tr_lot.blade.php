   <div>
    <div class="col-md-2 col-lg-1 col-xs-2 adj_ref">
        <p> {{ $lot->ref_asigl0}}  </p>
    </div>
    <div class="col-xs-10 col-sm-8 col-xs-7 col-lg-9 tabs-tr-description"><?= $lot->desc_hces1 ?></div>
    <div class="col-lg-2 col-sm-2 col-xs-3 col-lg-2 text-right">
        <p> <span>
                @if(!empty($lot->himp_csub))
                {{ $lot->himp_csub}}  €
                @else
                -
                @endif
            </span></p>
    </div>
</div>
       
