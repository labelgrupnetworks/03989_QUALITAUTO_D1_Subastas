@extends('layouts.panel')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <script>
        var currency = @JSON($divisas);
        var divisa = @JSON($divisa);
    </script>

    <section class="summary-page">
        <div class="panel-title">
            <h1>Resumen</h1>

            <select id="actual_currency">
                @foreach ($divisas as $divisaOption)
                    <option value='{{ $divisaOption->cod_div }}' @selected($divisaOption->cod_div == $divisa)>
                        {{ $divisaOption->cod_div }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="summary_body">
            <div class="summary-allotments">
                <h4 class="summary-subtitle">Mis Compras</h4>
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
                    <h4 class="summary-subtitle">Mis ventas</h4>
                    <div class="sales-menu">
                        <a class="btn btn-lb btn-lb-outline" onclick="getPendingSales(this)">
                            <span>Pendientes</span>
                        </a>
                        <a class="btn btn-lb btn-lb-primary" onclick="getSales(this)">
                            <span>Subastas activas</span>
                        </a>
                        <a class="btn btn-lb btn-lb-outline" onclick="getFinishSales(this)">
                            <span>Subastas Finalizadas</span>
                        </a>
                    </div>
                </div>
                <div id="summary-sales"></div>
            </div>

            <div class="summary-orders">
                <h4 class="summary-subtitle">Mis Pujas | Favoritos</h4>
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

        function getAllotmentsAndBills() {
            $.ajax({
                url: '/es/user/panel/allotments-bills',
                type: 'GET',
                success: function(response) {
                    const $block = $('#summary-allotments_table');
                    const $loader = $block.parent().find('.loader-box');
                    $loader.hide();
                    $block.html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function getFavorites() {
            $.ajax({
                url: '/es/user/panel/summary/favorites',
                type: 'GET',
                success: function(response) {

                    const $block = $('#summary-favorites');
                    const $loader = $block.parent().find('.loader-box');
                    $loader.hide();
                    $block.html(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function getSales(anchorElement) {
            refreshAnchorActive(anchorElement);
			getSalesTab('/es/user/panel/summary/active-sales');
        }

        function getFinishSales(anchorElement) {
            refreshAnchorActive(anchorElement);
			getSalesTab('/es/user/panel/summary/finish-sales');
        }

        function getPendingSales(anchorElement) {
            refreshAnchorActive(anchorElement);
			getSalesTab('/es/user/panel/summary/pending-sales');
        }

        function getSalesTab(url) {

			const $block = $('#summary-sales');
            const $loader = $block.parent().find('.loader-box');

			$.ajax({
                url: url,
                type: 'GET',
                beforeSend: function() {
                    $loader.show();
                },
                success: function(response) {
                    $block.html(response);
                },
                error: function(error) {
                    console.log(error);
                },
                complete: function() {
                    $loader.hide();
                }
            });
        }

        function refreshAnchorActive(anchorElement) {
            if (typeof anchorElement === 'undefined') {
                return;
            }
            $('.sales-menu a').removeClass('btn-lb-primary').addClass('btn-lb-outline');
            $(anchorElement).addClass('btn-lb-primary').removeClass('btn-lb-outline');
        }
    </script>
@stop
