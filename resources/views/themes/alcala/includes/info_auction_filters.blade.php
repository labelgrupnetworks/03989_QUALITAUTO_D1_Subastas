 <div class="sidebar_lot info-buy-filters">
        @if( (!empty($ficha_subasta->expofechas_sub)) ||  (!empty($ficha_subasta->expohorario_sub)) || (!empty($ficha_subasta->expolocal_sub)) || (!empty($ficha_subasta->expomaps_sub)))
            <h4>{{ trans($theme.'-app.subastas.inf_subasta_exposicion') }}</h4>
        @endif
        @if(!empty($ficha_subasta->expofechas_sub))
            <p><?= $ficha_subasta->expofechas_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->expohorario_sub))
            <p>{{ trans($theme.'-app.subastas.inf_subasta_horario') }}: <?= $ficha_subasta->expohorario_sub ?> </p>
        @endif
        @if(!empty($ficha_subasta->expolocal_sub))
            <p>{{ trans($theme.'-app.subastas.inf_subasta_location') }}: <?= $ficha_subasta->expolocal_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->expomaps_sub))
            <p><a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $ficha_subasta->expomaps_sub ?>">{{ trans($theme.'-app.subastas.how_to_get') }}</a></p>
        @endif
        @if((!empty($ficha_subasta->sesfechas_sub)) || (!empty($ficha_subasta->seshorario_sub)) || (!empty($ficha_subasta->seslocal_sub)) || (!empty($ficha_subasta->sesmaps_sub)))
            <h4>{{ trans($theme.'-app.subastas.inf_subasta_subasta') }}</h4>
        @endif
        @if(!empty($ficha_subasta->sesfechas_sub))
           <p> <?= $ficha_subasta->sesfechas_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->seshorario_sub))
            <p>{{ trans($theme.'-app.subastas.inf_subasta_horario') }}: <?= $ficha_subasta->seshorario_sub ?> </p>
        @endif
        @if(!empty($ficha_subasta->seslocal_sub))
            <p>{{ trans($theme.'-app.subastas.inf_subasta_location') }}: <?= $ficha_subasta->seslocal_sub ?></p>
        @endif
        @if(!empty($ficha_subasta->sesmaps_sub))
            <p><a target="_blank" title="{{ trans($theme.'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $ficha_subasta->sesmaps_sub ?>">{{ trans($theme.'-app.subastas.how_to_get') }}</a></p>
        @endif
    </div>
