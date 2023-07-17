<div class="container medium-container">
    <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-5">
        @foreach ($lots as $lot)
            @include('includes.galery.lot')
        @endforeach
    </div>
</div>
