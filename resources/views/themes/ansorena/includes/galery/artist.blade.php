<article class="card auction-card">
    <img class="card-img-top" width="" src="/img/autores/{{ $artist->id_artist }}.jpg" alt="">
    <div class="card-body">
        <p class="ff-highlight card-title">
            {{ Tools::changePositionNamesWithComa($artist->name_artist) }}</p>
    </div>
    <a class="stretched-link" href="{{ route('artistaGaleria', ['id_artist' => $artist->id_artist]) }}"></a>
</article>
