@php
    $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot(
        $lote_actual->num_hces1,
        $lote_actual->lin_hces1,
    );
@endphp

<section class="ficha-countdown">
    @if ($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
        <p class="ficha-info-clock">
            <span class="timer" data-{{ $nameCountdown }}="{{ strtotime($timeCountdown) - getdate()[0] }}"
                data-format="<?= \Tools::down_timer($timeCountdown, 'complete') ?>">
            </span>
        </p>
    @endif
</section>

@if (count($caracteristicas) !== 0)
    <section class="ficha-features features">
        <h5>{{ trans('web.features.features') }}</h5>

        <div class="gird-features">
            @foreach ($caracteristicas as $caracteristica)
                <div class="d-flex flex-column">
                    <p class="feature-name">{{ $caracteristica->name_caracteristicas }}</p>
                    <p class="feature-value">{{ $caracteristica->value_caracteristicas_hces1 }}</p>
                </div>
            @endforeach
        </div>

    </section>
@endif
