@php
    $featuresRequest = request('features');
    $rangesIds = array_map('trim', explode(',', config('app.typeSelectorRange', '')));
    $radioIds = array_map('trim', explode(',', config('app.typeSelectorRadio', '')));

    $minMaxRanges = \App\Models\V5\FgCaracteristicas_Value::selectRaw('max(cast(value_caracteristicas_value as int)) as max, min(cast(value_caracteristicas_value as int)) as min')
        ->addSelect('idcar_caracteristicas_value')
        ->whereRaw("TRANSLATE(value_caracteristicas_value, 'T 0123456789', 'T') IS NULL")
        ->whereIn('idcar_caracteristicas_value', $rangesIds)
        ->groupBy('idcar_caracteristicas_value')
        ->get()
        ->keyBy('idcar_caracteristicas_value');
@endphp


@foreach ($features as $idFeature => $feature)


    @if (!empty($featuresCount[$idFeature]) || in_array($idFeature, $rangesIds))
        <div class="auction__filters-categories">
            <div class="auction__filters-collapse filter-parent-collapse d-flex align-items-center justify-content-between"
                data-bs-toggle="collapse" href="#auction_feature_{{ $idFeature }}" role="button" aria-expanded="true"
                aria-controls="auction_feature_{{ $idFeature }}">

                <div class="filter-title">
					{{ trans("$theme-app.features.$feature") }}
                </div>

                <svg class="bi" width="16" height="16" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#caret-down-fill" />
                </svg>
            </div>

            <div class="auction__filters-type-list mt-2 collapse show" id="auction_feature_{{ $idFeature }}"
                aria-expanded="true">
                {{-- Si no han seleccionado esta caracteristica mostramos el combo --}}
                @if (empty($featuresRequest[$idFeature]))
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
                                    @php
                                        #debemos quitar los espacion en blanco y las barras
                                        $imgFeature = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], str_replace([' ', '/', '+'], ['', '', ''], mb_strtolower($featureValue['value_caracteristicas_value'])));
                                    @endphp
                                    @if (in_array($idFeature, [13, 35]))
                                        <img src="/themes/{{ $theme }}/assets/features/{{ $idFeature }}/{{ $imgFeature }}.png"
                                            alt=""
                                            style="max-width: {{ in_array($idFeature, [13]) ? '45' : '25' }}px">
                                    @endif
                                    {{ $featureValue['value_caracteristicas_value'] }}
                                    ({{ Tools::numberformat($featureValue['total']) }})
                                </label>
                            @endforeach
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
                        <select class="select_lot_list_js form-select form-select-sm"
                            name="features[{{ $idFeature }}]">
                            <option value=""> </option>
                            @foreach ($featuresCount[$idFeature] as $featureValue)
                                <?php
                                $selected = !empty($featuresRequest[$idFeature]) && $featuresRequest[$idFeature] == $featureValue['id_caracteristicas_value'] ? 'selected="selected"' : '';
                                ?>
                                <option id="feature_{{ $featureValue['id_caracteristicas_value'] }}"
                                    value="{{ $featureValue['id_caracteristicas_value'] }}" {{ $selected }}>
                                    {{ $featureValue['value_caracteristicas_value'] }}
                                    ({{ Tools::numberformat($featureValue['total']) }})
                                </option>
                            @endforeach
                        </select>
                    @endif
                    {{-- si han seleccionado esta caracteristica solo mostramos el valor seleccionado y permitimos eliminarlo --}}
                @else
                    @if (!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$featuresRequest[$idFeature]]))
                        <input id="feature_{{ $featuresRequest[$idFeature] }}" name="features[{{ $idFeature }}]"
                            type="hidden" value="{{ $featuresRequest[$idFeature] }}">
                        <span class="del_filter_js del_filter filt-act cursor"
                            data-del_filter="#feature_{{ $featuresRequest[$idFeature] }}"><i class="fas fa-times"></i>
                            {{ $featuresCount[$idFeature][$featuresRequest[$idFeature]]['value_caracteristicas_value'] }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
    @endif
@endforeach
