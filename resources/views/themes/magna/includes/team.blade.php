@php
    use App\Models\V5\FgEspecial1;
    $specialists = FgEspecial1::getSpecialists();
@endphp

<section class="container container-short py-5">

    banner


    <div class="text-md-end">
        <p class="seo-block-subtitle">Tenemos el mejor equipo</p>
        <h2 class="seo-block-title">Un gran equipo de profesionales</h2>
        <div class="seo-block-content ms-auto">
            <p class="mb-5">Folupta ipsus dolor rerchil mo torentiam que porunt renihil is dolenisquiaeAbo.Giti
                iumquas aut abo. Ducim es amendis vendebisti tem voluptatur alitatius.
            </p>
            <p class="text-start ms-auto w-md-75">
                Valentino Cortés, un consejero delegado joven y dinámico, del mundo
                de la empresa y José Miguel Carrillo de Albornoz, vizconde de Torre
                Hidalgo, un director de larga trayectoria en los diferentes segmentos
                del mercado del arte, aúnan fuerza y experiencia para conseguir un
                equilibrio de dinamismo y calidad en las subastas que comienzan el 3 de
                diciembre de 2024, apoyados por un gran equipo profesional.
            </p>
        </div>
    </div>

    <div class="team-members row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($specialists as $specialist)
            <div class="team-member">
                <div class="team-member-image">
                    <img src="{{ $specialist->image }}.jpg" alt="{{ $specialist->nom_especial1 }}">
                </div>
                <div class="team-member-info">
                    <p>{{ $specialist->specialty?->title }}</p>
                    <h3 class="ff-highlight">{{ $specialist->nom_especial1 }}</h3>
                    <p>{{ $specialist->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <p class="ms-auto w-md-75">
        Además, contamos con la colaboración externa de numerosos expertos en arte antiguo y
        contemporáneo para afinar las catalogaciones.
    </p>

</section>

<section class="container container-short py-4 mb-4">
    <x-contact-section>
        <x-slot:topAddress>
            <h2 class="contact-address-subtitle">Contactar</h2>
            <h3 class="contact-address-title">¿En que podemos ayudarte?</h3>
        </x-slot:topAddress>
    </x-contact-section>
</section>
