@php
    $category = App\Models\V5\FgOrtsec1::select('lin_ortsec1', 'des_ortsec0', 'key_ortsec0')
        ->JoinFgOrtsec0()
        ->where('sec_ortsec1', $lote_actual->sec_hces1)
        ->first();
    $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
@endphp

@if (in_array($lote_actual->cod_sub, ['CEREMATE', 'COMDEUDA', 'REOS']))
    <section class="ficha-login">
        <p class="mb-2">
            Si quieres tener una VALORACIÓN REAL de este activo a fecha de hoy por el que muestras interés de forma
            TOTALMENTE GRATUITA, ve a <button class="btn btn-link btn_login p-0">login</button> completa los datos y
            solicita más información. Uno de nuestros expertos
            valorará el activo con el máximo detalle y precisión y se pondrá en contacto contigo para transmitirte el
            resultado de la valoración.
        </p>
        @if (Session::has('user'))
            <button class="btn btn-lb-primary w-100">
                Solicitar valoración
            </button>
        @endif
    </section>
@endif

<section class="ficha-contact">
    <a class="btn btn-success"
        href="https://wa.me/34602252061?text=Estoy%20interesado%20es%20el%20siguiente%20activo%20{{ URL::current() }}"
        style="--lb-border-radius:.375rem" target="_blank">
        <svg class="bi" width="24" height="24" fill="currentColor">
            <use xlink:href="/bootstrap-icons.svg#whatsapp"></use>
        </svg>
        Contacta con nosotros
    </a>
</section>


<section class="ficha-features">

    @if (count($caracteristicas) !== 0)
        <div class="features mb-3">
            <h5>{{ trans("$theme-app.features.features") }}</h5>

            <div class="gird-features">
                @foreach ($caracteristicas as $caracteristica)
                    <p class="feature-name">
                        {{ trans("$theme-app.features.$caracteristica->name_caracteristicas") }}
                    </p>
                    <p class="feature-value">{{ $caracteristica->value_caracteristicas_hces1 }}</p>
                @endforeach
            </div>

        </div>
    @endif

    @if (!empty($category))
        <div class="categories">
            <h5>{{ trans(\Config::get('app.theme') . '-app.lot.categories') }}</h5>

            <a class="no-decoration" href="{{ route('category', ['keycategory' => $category->key_ortsec0]) }}"
                alt="{{ $category->des_ortsec0 }}">
                <span class="badge badge-custom-primary">{{ $category->des_ortsec0 }}</span>
            </a>
        </div>
    @endif
</section>
