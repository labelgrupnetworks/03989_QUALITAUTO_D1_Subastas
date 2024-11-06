@props([
	'headText' => trans("$theme-app.pages.how_buy_sell_text"),
	'highlightedText' => trans("$theme-app.pages.how_buy_sell_title"),
	'linkText' => trans("$theme-app.pages.how_buy_sell_link_text"),
	'linkAction' => Routing::translateSeo('pagina') . trans("$theme-app.links.how_to_buy"),
	'footText' => trans("$theme-app.pages.how_buy_sell_foot_text")
])

<div class="how-to-buy-section">
    <p>{{ $headText }}</p>
    <div class="how-to-buy-action">
        <h2 class="how-to-buy-text ff-highlight">{{ $highlightedText }}</h2>
        <a class="how-to-buy-link btn btn-outline-lb-primary rounded-5" href="{{ $linkAction }}">{{ $linkText }}</a>
    </div>
    <p>{{ $footText }}</p>
</div>
