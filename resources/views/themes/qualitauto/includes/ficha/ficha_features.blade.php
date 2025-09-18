@php
    $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot(
        $lote_actual->num_hces1,
        $lote_actual->lin_hces1,
    );
@endphp

@if (count($caracteristicas) !== 0)
    <section class="ficha-features">
        <div class="features">
            <h5>{{ trans('web.features.features') }}</h5>

            <div class="gird-features">
                @foreach ($caracteristicas as $caracteristica)
                    <div class="feature-item">
                        <p class="feature-name">{{ $caracteristica->name_caracteristicas }}</p>
                        <p class="feature-value">{{ Str::ucfirst($caracteristica->value_caracteristicas_hces1) }}</p>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif
