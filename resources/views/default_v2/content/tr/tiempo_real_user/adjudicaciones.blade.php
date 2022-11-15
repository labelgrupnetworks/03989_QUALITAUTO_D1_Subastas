<div class="aside adjudicaciones">
    {{-- Model --}}
    <li class="list-group-item p-0 py-1 adjudicaciones_model d-flex justify-content-between hidden" id="type_adj_model">
        <p class="adj_ref">
            {{ trans("$theme-app.sheet_tr.lot") }}
            <span></span>
        </p>
        <p>
            <span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
        </p>
		<p>
			<a class="lb-text-primary opacity-75 adj-link" href="">
				@include('components.boostrap_icon', ['icon' => 'eye', 'size' => 24])
			</a>
		</p>
    </li>

    <div class="adjudicaciones_list list-group list-group-flush">
        @foreach ($data['js_item']['user']['adjudicaciones'] ?? [] as $award)
            <li class="list-group-item p-0 py-1 adjudicaciones_model d-flex justify-content-between">
                <p class="adj_ref">
                    {{ trans("$theme-app.sheet_tr.lot") }}
                    <span>
                        {{ str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $award->ref_asigl1) }}
                    </span>
                </p>
                <p>
                    <span class="adj_imp">{{ $award->imp_asigl1 }}</span>
                    <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                </p>
				<p>
					<a class="lb-text-primary opacity-75 adj-link" href="{{ $auctionLots->where('ref_asigl0', $award->ref_asigl1)->first()->url }}">
						@include('components.boostrap_icon', ['icon' => 'eye', 'size' => 24])
					</a>
				</p>
            </li>
        @endforeach
    </div>
</div>
