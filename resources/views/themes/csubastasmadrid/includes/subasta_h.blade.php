<div class="col-xs-12">
    <div class="item_subasta row">

        <div class="col-xs-12 col-sm-6 col-md-4">
            <a title="{{ $subasta->name }}">
                <div>
                    <img class="img-responsive"
						loading="lazy"
                        src="{{ "/img/thumbs/263/SESSION_{$subasta->company}_{$subasta->auction}_{$subasta->reference}.jpg" }}"
                        alt="{{ $subasta->name }}" />
                </div>
            </a>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-5">
            <div class="item_subasta_item">
                {{ $subasta->name }}
            </div>

            <div class="item_subasta_item_text">
                {!! $subasta->descdet_sub !!}
            </div>
        </div>

        <div class="col-xs-12 col-md-3">

            @if ($subasta->uppreciorealizado == 'S')
                <p class="text-center">
                    <a class="btn btn-2"
						href="{{ "/files/{$auction->company}_{$auction->auction}_{$auction->reference}_pre_es.pdf" }}"
                        title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
                        target="_blank">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
                </p>
            @endif

            @if ($subasta->upcatalogo == 'S')
                <p class="text-center">
                    <a class="btn btn-3"
						href="{{ "/files/{$auction->company}_{$auction->auction}_{$auction->reference}_cat_es.pdf" }}"
                        title="{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}"
                        target="_blank">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</a>
                </p>
            @endif
        </div>
    </div>
</div>
