@php
    $titulo = $relatedArticle->model_art0;

    #sumarle el iva al producto
    $imp = round($relatedArticle->pvp_art0 + $relatedArticle->pvp_art0 * $iva, 2);
    $impFormat = Tools::moneyFormat($imp, trans("$theme-app.subastas.euros"), 0);
    $url = route('article', ['idArticle' => $relatedArticle->id_art0, 'friendly' => Str::slug($titulo)]);

@endphp

<article class="article-card">
    <a href="{{ $url }}">
        <div class="article-card-image-wrapper"><img class="article-card-img"
                src="/articulos/{{ $relatedArticle->id_art0 }}.jpg" alt="{{ $titulo }}"></div>
    </a>
    <div class="article-card-content">
        <p class="article-card-title">{{ $titulo }}</p>
        @if ($imp > 0)
            <p class="article-card-price">{{ $impFormat }}</p>
        @endif
    </div>
</article>
