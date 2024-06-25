@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
		let yearsSelected = @JSON($yearsSelected);
    </script>

    <section class="summary-page">
        <div class="panel-title">
            <h1>{{ trans("$theme-app.user_panel.summary") }}</h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>

			<div class="dropdown sales-filter">
                <span>{{ trans("$theme-app.user_panel.filters") }}</span>
                <button class="custom-select" id="summary-filter-toogle" data-toggle="dropdown" type="button" aria-haspopup="true"
                    aria-expanded="false">
                    {{ trans("$theme-app.user_panel.year") }}
                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="summary-filter-toogle">
                    <form id="summary-form">
                        @foreach ($yearsAvailables as $year)
                            <li>
                                <div class="checkbox">
                                    <label>
                                        <input name="years[]" type="checkbox" value="{{ $year }}"
                                            @checked(in_array($year, $yearsSelected))>{{ $year }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                        <li class="divider" role="separator"></li>
                        <li>
                            <button class="btn btn-lb btn-lb-primary"
                                type="submit">{{ trans("$theme-app.global.filter") }}</button>
                        </li>
                    </form>
                </ul>
            </div>

        </div>

        <div class="summary_body">
            <div class="summary-allotments">
                <h4 class="summary-subtitle">
					<a href="{{ route('panel.allotment-bills', ['lang' => config('app.locale')]) }}">
						{{ trans("$theme-app.user_panel.my_pending_bills") }}
					</a>
				</h4>
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="summary-allotments_table"></div>
            </div>

            <div class="summary-sales">
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="summary-sales_header">
                    <h4 class="summary-subtitle">
						<a href="{{ route('panel.sales.active', ['lang' => config('app.locale')]) }}">
							{{ trans("$theme-app.user_panel.my_assignments") }}
						</a>
					</h4>
                    <div class="sales-menu">
                        <a class="btn btn-lb btn-lb-outline" onclick="getPendingSales(this)">
                            <span>{{ trans("$theme-app.user_panel.pendings") }}</span>
                        </a>
                        <a class="btn btn-lb btn-lb-primary" onclick="getSales(this)">
                            <span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.active_auctions") }}</span>
							<span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.active") }}</span>
                        </a>
                        <a class="btn btn-lb btn-lb-outline" onclick="getFinishSales(this)" data-refresh="true">
							<span class="visible-md visible-lg">{{ trans("$theme-app.user_panel.auctions_completed") }}</span>
							<span class="hidden-md hidden-lg">{{ trans("$theme-app.user_panel.finished") }}</span>
                        </a>
                    </div>
                </div>
                <div id="summary-sales"></div>
            </div>

            <div class="summary-orders">
                <h4 class="summary-subtitle">
					<a href="{{ route('panel.orders', ['lang' => config('app.locale')]) }}">
						{{ trans("$theme-app.user_panel.orders") }}
					</a> |
					<a href="{{ route('panel.orders', ['lang' => config('app.locale'), 'favorites' => '1']) }}">
						{{ trans("$theme-app.user_panel.favorites") }}
					</a>
				</h4>
                <div class="loader-box">
                    <div class="loading-wrapper">
                        <div class="loader-a">
                            <div class="loader-inner">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="summary-favorites"></div>
            </div>
        </div>

    </section>

    <script>
        getAllotmentsAndBills();
        getSales();
        getFavorites();
    </script>
@stop
