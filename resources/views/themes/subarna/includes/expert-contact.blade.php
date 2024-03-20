@php
    //$name_archive = '/img/PER/' . Config::get('app.gemp') . $esp->per_especial1 . '.jpg';
    $title = $title ?? '¿NECESITAS CONTACTAR CON UN EXPERTO?';
    $specialist =
        $specialist ??
        (object) [
            'nom_especial1' => 'Nombre del experto',
            'pos_especial1' => 'Posición del experto',
            'email_especial1' => 'email@email.es',
            'phone_especial1' => '+34 666 77 88 99',
            'desc_especial1' =>
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ut quia aliquid accusantium. Laboriosam soluta laudantium nulla quas doloribus. Dolorem nesciunt cupiditate minus officia inventore reprehenderit dolores aspernatur autem perferendis reiciendis?',
            'per_especial1' => '1',
        ];
@endphp

<section class="container-fluid expert-section">

    <h1>{{ $title }}</h1>

    <div class="expert-section_body">
        <div class="">
            <img class="expert-section_generic" src="/themes/subarna/assets/img/tasacion.png"
                alt="muestra un ejemplo de tasación" loading="lazy">
        </div>

        <div class="expert-card">
            <img src="/themes/subarna/assets/img/placeholder_round.svg" alt="">
            <div class="expert-card_name">
                <h2>{{ $specialist->nom_especial1 }} </h2>
                <p>{{ $specialist->pos_especial1 }}</p>
            </div>
            <div class="expert-card_desc">
                <p>
                    {{ $specialist->desc_especial1 }}
                </p>
            </div>
            <p class="expert-card_contact">
                <a href="mailto:{{ $specialist->email_especial1 }}">{{ $specialist->email_especial1 }}</a>
                <br>
                <a href="tel:{{ $specialist->phone_especial1 }}">{{ $specialist->phone_especial1 }}</a>
            </p>
        </div>
    </div>

    <div class="text-center p-2">
        <a class="btn btn-xl btn-lb-primary" href="{{ Routing::translateSeo('valoracion-articulos') }}">
            Tasación online
        </a>
    </div>

</section>
