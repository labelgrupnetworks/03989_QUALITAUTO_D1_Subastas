@php
    $featuresRequest = request('features', []);
    $rangesIds = array_map('trim', explode(',', config('app.typeSelectorRange', '')));
    $radioIds = array_map('trim', explode(',', config('app.typeSelectorRadio', '')));
    $pillsIds = array_map('trim', explode(',', config('app.typeSelectorPills', '')));

	$minMaxRanges = [];
	if(!empty($rangesIds)) {
		$minMaxRanges = \App\Models\V5\FgCaracteristicas_Value::selectRaw('max(cast(value_caracteristicas_value as int)) as max, min(cast(value_caracteristicas_value as int)) as min')
			->addSelect('idcar_caracteristicas_value')
			->whereRaw("TRANSLATE(value_caracteristicas_value, 'T 0123456789', 'T') IS NULL")
			->whereIn('idcar_caracteristicas_value', $rangesIds)
			->groupBy('idcar_caracteristicas_value')
			->get()
			->keyBy('idcar_caracteristicas_value');
	}

	$featuresRequest = array_filter($featuresRequest, function($value) {
		if(is_array($value)) {
			return array_filter($value);
		}
		return $value;
	});
@endphp


@foreach ($features as $idFeature => $feature)

    @if (!empty($featuresRequest[$idFeature]))
        @if (is_array($featuresRequest[$idFeature]))
            @foreach ($featuresRequest[$idFeature] as $requestValue)
                <input id="feature_{{ $idFeature }}" name="features[{{ $idFeature }}][]" type="hidden" value="{{ $requestValue }}">
            @endforeach
        @else
            <input id="feature_{{ $idFeature }}" name="features[{{ $idFeature }}]" type="hidden" value="{{ $featuresRequest[$idFeature] }}">
        @endif
    @endif

    @if ((empty($featuresRequest[$idFeature]) && !empty($featuresCount[$idFeature])) || in_array($idFeature, $rangesIds))
        <div class="auction__filters-features">
            <div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between"
                data-bs-toggle="collapse" href="#auction_feature_{{ $idFeature }}" role="button"
                aria-expanded="true" aria-controls="auction_feature_{{ $idFeature }}">

                <div class="filter-title">
                    {{ $feature }}
                </div>

                <svg class="bi" width="16" height="16" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#caret-down-fill" />
                </svg>
            </div>

            <div class="auction__filters-type-list mt-2 collapse show" id="auction_feature_{{ $idFeature }}"
                aria-expanded="true">

                @if (in_array($idFeature, $radioIds))
                    <div class="radio-wrapper">
                        @foreach ($featuresCount[$idFeature] as $featureValue)
                            @php
                                $checked = !empty($featuresRequest[$idFeature]) && $featuresRequest[$idFeature] == $featureValue['id_caracteristicas_value'];
                            @endphp

                            <label class="radio-inline mb-1" style="gap: 5px">
                                <input class="select_lot_list_js"
                                    id="feature_{{ $featureValue['id_caracteristicas_value'] }}"
                                    name="features[{{ $idFeature }}]" type="radio"
                                    value="{{ $featureValue['id_caracteristicas_value'] }}">

                                {{ $featureValue['value_caracteristicas_value'] }}
                                <span class="grid-count">
                                    ({{ Tools::numberformat($featureValue['total']) }})
                                </span>
                            </label>
                        @endforeach
                    </div>
                @elseif(in_array($idFeature, $pillsIds))
                    @php
                        $maxValue = 3;
                        $values = array_column($featuresCount[$idFeature], 'value_caracteristicas_value');
                        $valuesMoreBiggerMax = array_filter($values, fn($value) => $value > $maxValue);
                    @endphp
                    <div class="pills-wrapper">
                        @foreach (range(1, $maxValue) as $pillNumber)
                            @php
                                $inValues = in_array($pillNumber, $values);
                            @endphp
                            <label @class([
                                'pill-input',
                                'pill-input--disabled' => !$inValues,
                            ])>

                                <input class="select_lot_list_js d-none"
                                    id="feature_{{ $idFeature }}_{{ $pillNumber }}"
                                    name="features[{{ $idFeature }}]" type="radio" value="{{ $pillNumber }}">

                                {{ $pillNumber }}
                            </label>
                        @endforeach

                        @if (count($valuesMoreBiggerMax))
                            <label @class(['pill-input', 'pill-input--active' => false])>

                                <input class="select_lot_list_js d-none"
                                    id="feature_{{ $idFeature }}_{{ $pillNumber + 1 }}"
                                    name="features[{{ $idFeature }}][]" type="radio"
                                    value="{{ $pillNumber + 1 }}">
                                {{ $maxValue + 1 }}+
                            </label>
                        @endif
                    </div>
                @elseif(in_array($idFeature, $rangesIds) && !empty($minMaxRanges[$idFeature]))
                    @php
                        $minSelect = $featuresRequest[$idFeature][0] ?? null;
                        $maxSelect = $featuresRequest[$idFeature][1] ?? null;
                    @endphp

                    {!! \FormLib::SelectRange(
                        "features[$idFeature]",
                        "feature_$idFeature",
                        'filter_range_js',
                        $minMaxRanges[$idFeature]->min,
                        $minSelect,
                        $minMaxRanges[$idFeature]->max,
                        $maxSelect,
                    ) !!}
                @elseif(!empty($featuresCount[$idFeature]))
                    <select class="select_lot_list_js form-select form-select-sm" name="features[{{ $idFeature }}]">
                        <option value=""> </option>
                        @foreach ($featuresCount[$idFeature] as $featureValue)
                            <?php
                            $selected = !empty($featuresRequest[$idFeature]) && $featuresRequest[$idFeature] == $featureValue['id_caracteristicas_value'] ? 'selected="selected"' : '';
                            ?>
                            <option id="feature_{{ $featureValue['id_caracteristicas_value'] }}"
                                value="{{ $featureValue['id_caracteristicas_value'] }}" {{ $selected }}>
                                {{ $featureValue['value_caracteristicas_value'] }}

                                @if (Config::get('app.gridfilters_with_count', false))
                                    ({{ Tools::numberformat($featureValue['total']) }})
                                @endif

                            </option>
                        @endforeach
                    </select>
                @endif

            </div>
        </div>
    @endif
@endforeach
