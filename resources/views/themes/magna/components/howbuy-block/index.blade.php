@props([
	'headText' => 'Compra o vende pintura, joyas, relojes, vintage, diseño y más cuando quieras',
	'highlightedText' => 'Compra ahora',
	'linkText' => 'Ir a venta directa',
	'linkAction' => Routing::translateSeo('pagina') . trans("$theme-app.links.how_to_buy"),
	'footText' => 'Envíenos la información y en 48 horas nuestros especialistas se pondrán en contacto con usted'
])

<div class="how-to-buy-section">
    <p>{{ $headText }}</p>
    <div class="how-to-buy-action">
        <h2 class="how-to-buy-text ff-highlight">{{ $highlightedText }}</h2>
        <a class="how-to-buy-link btn btn-outline-lb-primary rounded-5" href="{{ $linkAction }}">{{ $linkText }}</a>
    </div>
    <p>{{ $footText }}</p>
</div>
