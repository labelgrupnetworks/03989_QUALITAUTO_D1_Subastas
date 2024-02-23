@php
    $reviews = [
        [
            'name' => 'jaime bene',
            'position' => 'Empresario e Inversor Inmobiliario',
            'image' => "/themes/$theme/assets/img/google_reviews/Jaime-Beneyto.webp",
            'comment' => 'Contacté con Cristina por una subasta que se celebraba en Madrid. No salió porque era una joya a la que no podía llegar económicamente, pero nació una bonita amistad que se ha prolongado en el tiempo y gracias a esa amistad, a la confianza que te da, a su profesionalidad y la transparencia que tiene seguimos trabajando y al poco tiempo nos hemos hecho con una joyita en la costa alicantina. Mi primera subasta ganada. <img draggable="false" role="img" class="emoji" alt="🙂" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f642.svg">  Tengo la sensación que rentabilizare mi inversión en un 90%. Y todo gracias a ella y a su equipo. Su trabajo es impecable. Con ganas de que me presente nuevos proyectos y seguir aumentando mi patrimonio.  Muy recomendable.',
        ],
        [
            'name' => 'Flor de lis Van Beetz Gil',
            'position' => 'Despacho de Abogados Estepona',
            'image' => "/themes/$theme/assets/img/google_reviews/placeholder.webp",
            'comment' => 'Cristina es un excelente profesional, muy eficiente y resolutiva. Hemos tenido una operación y cliente en común , y siempre fue comunicativa y me mantuvo al corriente en todo momento. Espero volver a coincidir con ella en otras transacciones. Altamente recomendable.',
        ],
        [
            'name' => 'Mónica Blanco',
            'position' => 'Homestager Profesional',
            'image' => "/themes/$theme/assets/img/google_reviews/placeholder.webp",
            'comment' => 'Uno de los punto más fuertes que tiene Cristina es la alta rentabilidad que ayuda a conseguir a los inversores que confían en ella. No solo en la adjudicación de inmuebles a través de subastas,  sino también su visión de convertir locales de uso comercial en viviendas y viviendas que precisan una reforma bien porque su estado de conservación no haya sido el correcto, o bien para explotarlo como alquiler turístico o vacacional . La técnica que utiliza en su equipo se llama Home Staging y consigue un aumento de precio entre un 5% y un 15% en la posterior comercialización ya sea de alquiler o venta incrementando la rentabilidad del inmueble de forma exponencial. Recomiendo su asesoramiento como experta en subastas e inversiones inmobiliarias 100% en toda España.',
        ],
        [
            'name' => 'Ana López-Serrano García',
            'position' => 'Inversora Inmobiliaria',
            'image' => "/themes/$theme/assets/img/google_reviews/placeholder.webp",
            'comment' => 'Hoy hemos hecho una consulta inmobiliaria a Cristina que nos ha respondido muy rápido, eficaz y de forma especialmente honesta. Muy contenta con sus servicios.',
        ],
        [
            'name' => 'Suellen Suellen',
            'position' => null,
            'image' => "/themes/$theme/assets/img/google_reviews/placeholder.webp",
            'comment' => 'Estoy encantada con el servicio ofrecido por Cristina como experta en subastas e inversiones inmobiliarias. Su propuesta de inversión en la compra de un chalet en Madrid ha sido inmaculada; una estrategia muy bien pensada y con mucho recorrido. Recomiendo 100% su asesoramiento y su representación en las subastas. Un trato muy cercano, sincero y transparente para repetir mil veces. Un 10 para ella y todo su equipo altamente cualificado. Sin duda, una de las mejores profesionales del sector inmobiliario como especialista en inversiones y Flipping House en España.',
        ],
        [
            'name' => 'Joaquín Gómez',
            'position' => null,
            'image' => "/themes/$theme/assets/img/google_reviews/placeholder.webp",
            'comment' => 'Cristina es una excelente profesional y una de las mejores asesoras de inversiones y subastas inmobiliarias. Ha sido un placer trabajar con ella y contar con su ayuda y experiencia durante todo el proceso de compra. Me gustaría destacar su cercanía, la ilusión que transmite desde el primer momento, así como la confianza que genera en sus clientes, explicándonos de forma clara y concisa todo lo que necesitábamos saber en cada paso. Sin duda volveremos a contar con ella en el futuro.',
        ],
    ];
@endphp

<div class="google-reviews">
    @foreach ($reviews as $review)
        <article class="card card-google">
            <div class="card-header">
                <div class="card-header-thumb">
                    <img class="rounded-circle" src="{{ $review['image'] }}" alt="{{ $review['name'] }}" width="36"
                        height="36">
                </div>
                <div class="card-header-user">
                    <p class="card-user-name">{{ $review['name'] }}</p>
                    <div class="card-user-rating">
                        @foreach (range(0, 4) as $star)
                            @include('components.boostrap_icon', [
                                'icon' => 'star-fill',
                                'color' => '#f0ad4e',
                            ])
                        @endforeach
                    </div>
                    <p class="card-user-position opacity-75 small">
                        {{ $review['position'] }}
                    </p>
                </div>
                <div class="card-header-google-brand">
                    @include('components.boostrap_icon', ['icon' => 'google'])
                </div>
            </div>
            <div class="card-body">
                <p class="card-text">
                    {!! $review['comment'] !!}
                </p>
            </div>
        </article>
    @endforeach
</div>

<div class="google-divider">
	<span class="google-divider-separator">
		<div class="google-divider-icon">
			@include('components.boostrap_icon', ['icon' => 'google', 'size' => '20'])
		</div>
	</span>
</div>

<script>
    $('.google-reviews').slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: true,
        dots: true
    });
</script>
