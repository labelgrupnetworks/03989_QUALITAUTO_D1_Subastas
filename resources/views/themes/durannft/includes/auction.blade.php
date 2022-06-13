<div class=" square">


    <div class="item_expo_dmg">

        <div class="item_img_expo">
            <a title="{{ $subasta->des_sub }}" href="{!! $url_lotes !!}"><img class="img-responsive "
                    src="{{ \Tools::url_img_auction('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
                    alt="{{ $subasta->des_sub }}"></a>
        </div>

        <div class="data-container">

            {{-- Título y botones --}}
            <div class="title_expo_dmg d-flex">
                <p class="text-left  max-line-1" style="flex: 4">
                    <a title="{{ $subasta->des_sub }}" href="{!! $url_lotes !!}">{!! strip_tags($subasta->des_sub) !!}</a>
                </p>


            </div>
            <p> {{ $cuantos->cuantos }} {{ trans(\Config::get('app.theme') . '-app.subastas.obras') }}
        </div>

    </div>

</div>
