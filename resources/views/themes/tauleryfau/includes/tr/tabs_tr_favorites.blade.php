


        <div class="col-xs-12 no-padding content-favorites d-flex align-items-center flex-wrap">
            <div class="col-xs-12 col-md-3 col-xs-12 no-padding content-favorites d-flex align-items-center justify-content-center">
                <img class="img-responsive" style="max-height: 100px;" src="/img/load/lote_medium/{{$lot->img}}">
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="mb-1 fs-20"><strong>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }} {{ $lot->ref_asigl0}} </strong></div>
                <div class="mb-3 tabs-tr-description"><?= $lot->desc_hces1 ?></div>
                <div class="d-flex justify-content-space-between tabs-tr-price">
                    <div>{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}: {{$lot->formatted_impsalhces_asigl0 }}€</div>
                    <a href="{{$lot->url_friendly }}" target="_blank" class="btn btn-primary  btn-xs">{{ trans(\Config::get('app.theme').'-app.sheet_tr.goToLot') }}</a>
                </div>
            </div>
        </div>

    
