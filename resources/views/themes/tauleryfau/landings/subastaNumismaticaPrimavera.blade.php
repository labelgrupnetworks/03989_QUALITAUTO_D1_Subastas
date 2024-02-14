@extends('layouts.tasacion')
@include('includes.google_head')
@section('title')
{{ trans($theme.'-app.head.title_app') }}

@stop


@section('content')

<body class="landing">

    <header>

        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="header-desktop inline-flex">
                        <div class="logo col-xs-12 col-md-12 text-center">

                            <a title="Tauler y Fau" href="/es">
                                <img class="img-responsive"
                                    src="/themes/<?= $theme ?>/img/comprar-monedas/logo.png"
                                    alt="Tauler y Fau">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container" style="padding-bottom: 30px;">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 col-xs-12 text-center">
                   <a href="https://www.tauleryfau.com/es/info-subasta/29052019-subasta-30-sala-29-mayo-2019">
                    <img class="img-responsive"
                        src="/themes/{{$theme}}/img/slider-S30-espan%CC%83ol.jpg"
                        alt="{{(\Config::get( 'app.name' ))}}" /></a>
                </div>
            </div>
        </div>


    </section>
    <section class="super-slogan">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="slogan-principal text-center">
                        <h1>Subasta 30 Sala</h1>
                        <h2 style="color: white; font-size: 18px">29 Mayo 2019 – 16:00 horas (CEST)</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-body">
        <div class="" style='position: relative'>

            <div class="row">
                <div class="col-md-6 col-xs-12 no-padding">
                    <div class="landing-body-header">
                        <h2>SUBASTA EXCLUSIVAMENTE ONLINE</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-12 list-content">
                            <div style="padding-right: 40px;">
                                <li>
                                <a title="Grecia Antigua" style="color: white" href="subastas/grecia-antigua">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Grecia Antigua</a></li>
                                <li>
                                        <a title="Hispania Antigua" style="color: white" href="subastas/hispania-antigua">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Hispania Antigua</a></li>
                                <li>
                                        <a  title="República Romana" style="color: white" href="subastas/republica-romana">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">República Romana </a></li>
                                <li>
                                        <a title="Imperio Romano" title="Imperio Romano" style="color: white" href="subastas/imperio-romano">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Imperio Romano</a></li>
                                <li>
                                        <a title="Monedas Visigodas" style="color: white" href="subastas/monedas-visigodas">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAAB+CAYAAAD/cVlWAAAACXBIWXMAAAsSAAALEgHS3X78AAAGMUlEQVR4nO2d/XHbRhDF15n8L6YC0RWEqcBUB3IFoiowVUGoCixWYKoDuwJLFZisIFQHZAXKYGYxg/EARwC3d7t7eL8CyAP58Pbjvj68v78TAF38gV8GhIBAQBAIBASBQEAQCAQEgUBAEAgEBIFAQBAIpDzmRLQjoqXEk0EgZbEhoj0R3Uk91Z9T/0ULYcmucS39OHAQ38yI6ImIfqYQB8FBXJPMNZpgNtcfM841vlwY+QeJJ4OD+GLBrvF3rlEjB/HDiohecoqD4CBu2EmWrkOAQGwz03CNJggxdqnyjaOmOAgCMUuVb/wioivtAUIg9qhK2G+RozpLPRVyEFtIJaN7qaeCg9igSka/a1UqIeAg+qhXKiHgILqYFgdBIKqYFwdBIGq4EAdBICq4EQdBINlxJQ7CepCs5BaHyHoQCCQf+8zOISIQhJg8ZF3kIwkEkh61tRwSQCBpWSuKYybxIRBIOqop+6+K37+Q+BAIJA0L3q/iHghEnrqc1V7sAwcxigVxEHIQm1gqZ+cSHwKByLEyVs6KCASdVBkWhkJLzVkizEAg8ViegItutyPExPNkuI0efcoQBBKHtbzjd6LzEAhkPHMHzTAIRJGdsaS0DYQYJardb58cjDO6m4oqZjgL3jfrhb+I6DR2rHCQ4eycjTcqzEAgw9g4XBkWFWYQYvrjLbScucp6igkx2JvbH0+hZctuN1oYNRBIP9ZOQssrN++OUh+IEHOZOW9ZsNzzOLMwvkt/MJLUyzwZF8eWRSwuDoKDXGTJ56Bb5K1xdmoyPDmIyBrLgVhNTJ8ba1CS4kEgS84BXqTWWfakqgKSHpQ/girX+MzOEV2h9MGyQGb8Bv/kCuIq4+zpnCsXSxzYNZLkGl1YFciaS7Xf11rcSa21vMDGWGK6bRysmxVrAqnDydfAH7TJMAYri4CqkHKv6WZWqpi+d6DUfEz4Nr0YmcqvqpRbyTNPx2DBQerOX19xUMJcZGlEHHW+oSoOUnaQev/q2D/kJkGZdzRQuTzzS2MCDQepL+L7Ffm2SuciKwPieLQkDlJwkJVw61rSRbTd495iYy6Xg9Rdv2/C5aOUi2i6R938Mtm1Te0gQ6uTMUi8eVrucW6U9iZJKRDpcNLFW2TzbCVwP8sYzIuDEoWYVOGki+vIxC51460NF+IgYYFIVSdjGPsna+QebsRBggIZ0+yS5HpkOzq3e7gSBwnkILHNLknOnIv0nQbPnXu4EwdFOIhmOOniaqCL5GxIuRQHjXSQXNXJGPq6SM6lhG7FQSMc5DZjdTKGvi6S0z1WXsVBIx1kzsmd5YNTQssBqvH/l2kcJtvnQxiTgxz5rbjhjToWCVUnudzj0bs4SKiTajUn6XKRU4axmpqyj0GiD7Jj297qPUYrbS6yyiCO11LEQYKNshMnhze8GsoCdy17aW4Tj+uQ4TuykmqybsOC0Q47r40DVFInp67L2S5SrQfZ8NurncR+aggkte27Lme7SLlg6Mh/zgO/XVrUuUhKgTzk3tCUi1xLDuvd51pnbDwkvP2pmIqljdxrUqu3+d+cX5iYA7tkln2yGmhse6j3l1rbGD2Us9Z2yJxobHvY8w/7Q++xRbgtXRykuLPuxD/wvXICO5aHHGdzWMDC3lxvIedHac2wEBb25noKOYeSK5Y2rBz/UIecRwNj6eKc82QfK1g7H2TDeYlF1iV2Si9h9ZRDa5cEFt0MC2H5GEwrIjkonbBoAsuH2NXJq+bygfOUKpY2rB+DWU/4aYlE9Nxzj3g4J/WkJJIq7yhyhnYIno7iznmBcfGTcH3xdBR3LieZZL+jC2+3PeQQyWaK/Y4uvN72MOPkUboEntQ8Sx88Xwci3ScZejrAJPB8odCew43UcgHkHS14v3FqL9QC36KkbaeUG6diDoN543AF92ihlDvrdhFLBRBaApR0qeGGu59D2E5l6eBYSrzUcN+z24rQ0oMSr0Vd8p9/CYSWHpQokHr5Yqj8fUZo6UfJ9+Z2VTZoiA2g5Ju3uyqbNcTRnyncvN28g655XgjowRQEMuPKptqY9Q9maodRcoipqZPWZ4hjOFNwEBDBFBwERACBgCAQCAgCgYAgEAgIAoGAIBAICAKBgG6I6H+XYX81bhJJaQAAAABJRU5ErkJggg=="
                                        width='25' style="margin-left: 10px">Monedas Visigodas</a></li>
                            </div>
                            <div>
                                <li>
                                        <a title="Época Medieval" style="color: white" href="subastas/epoca-medieval">
                                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAAB+CAYAAAD/cVlWAAAACXBIWXMAAAsSAAALEgHS3X78AAAGMUlEQVR4nO2d/XHbRhDF15n8L6YC0RWEqcBUB3IFoiowVUGoCixWYKoDuwJLFZisIFQHZAXKYGYxg/EARwC3d7t7eL8CyAP58Pbjvj68v78TAF38gV8GhIBAQBAIBASBQEAQCAQEgUBAEAgEBIFAQBAIpDzmRLQjoqXEk0EgZbEhoj0R3Uk91Z9T/0ULYcmucS39OHAQ38yI6ImIfqYQB8FBXJPMNZpgNtcfM841vlwY+QeJJ4OD+GLBrvF3rlEjB/HDiohecoqD4CBu2EmWrkOAQGwz03CNJggxdqnyjaOmOAgCMUuVb/wioivtAUIg9qhK2G+RozpLPRVyEFtIJaN7qaeCg9igSka/a1UqIeAg+qhXKiHgILqYFgdBIKqYFwdBIGq4EAdBICq4EQdBINlxJQ7CepCs5BaHyHoQCCQf+8zOISIQhJg8ZF3kIwkEkh61tRwSQCBpWSuKYybxIRBIOqop+6+K37+Q+BAIJA0L3q/iHghEnrqc1V7sAwcxigVxEHIQm1gqZ+cSHwKByLEyVs6KCASdVBkWhkJLzVkizEAg8ViegItutyPExPNkuI0efcoQBBKHtbzjd6LzEAhkPHMHzTAIRJGdsaS0DYQYJardb58cjDO6m4oqZjgL3jfrhb+I6DR2rHCQ4eycjTcqzEAgw9g4XBkWFWYQYvrjLbScucp6igkx2JvbH0+hZctuN1oYNRBIP9ZOQssrN++OUh+IEHOZOW9ZsNzzOLMwvkt/MJLUyzwZF8eWRSwuDoKDXGTJ56Bb5K1xdmoyPDmIyBrLgVhNTJ8ba1CS4kEgS84BXqTWWfakqgKSHpQ/girX+MzOEV2h9MGyQGb8Bv/kCuIq4+zpnCsXSxzYNZLkGl1YFciaS7Xf11rcSa21vMDGWGK6bRysmxVrAqnDydfAH7TJMAYri4CqkHKv6WZWqpi+d6DUfEz4Nr0YmcqvqpRbyTNPx2DBQerOX19xUMJcZGlEHHW+oSoOUnaQev/q2D/kJkGZdzRQuTzzS2MCDQepL+L7Ffm2SuciKwPieLQkDlJwkJVw61rSRbTd495iYy6Xg9Rdv2/C5aOUi2i6R938Mtm1Te0gQ6uTMUi8eVrucW6U9iZJKRDpcNLFW2TzbCVwP8sYzIuDEoWYVOGki+vIxC51460NF+IgYYFIVSdjGPsna+QebsRBggIZ0+yS5HpkOzq3e7gSBwnkILHNLknOnIv0nQbPnXu4EwdFOIhmOOniaqCL5GxIuRQHjXSQXNXJGPq6SM6lhG7FQSMc5DZjdTKGvi6S0z1WXsVBIx1kzsmd5YNTQssBqvH/l2kcJtvnQxiTgxz5rbjhjToWCVUnudzj0bs4SKiTajUn6XKRU4axmpqyj0GiD7Jj297qPUYrbS6yyiCO11LEQYKNshMnhze8GsoCdy17aW4Tj+uQ4TuykmqybsOC0Q47r40DVFInp67L2S5SrQfZ8NurncR+aggkte27Lme7SLlg6Mh/zgO/XVrUuUhKgTzk3tCUi1xLDuvd51pnbDwkvP2pmIqljdxrUqu3+d+cX5iYA7tkln2yGmhse6j3l1rbGD2Us9Z2yJxobHvY8w/7Q++xRbgtXRykuLPuxD/wvXICO5aHHGdzWMDC3lxvIedHac2wEBb25noKOYeSK5Y2rBz/UIecRwNj6eKc82QfK1g7H2TDeYlF1iV2Si9h9ZRDa5cEFt0MC2H5GEwrIjkonbBoAsuH2NXJq+bygfOUKpY2rB+DWU/4aYlE9Nxzj3g4J/WkJJIq7yhyhnYIno7iznmBcfGTcH3xdBR3LieZZL+jC2+3PeQQyWaK/Y4uvN72MOPkUboEntQ8Sx88Xwci3ScZejrAJPB8odCew43UcgHkHS14v3FqL9QC36KkbaeUG6diDoN543AF92ihlDvrdhFLBRBaApR0qeGGu59D2E5l6eBYSrzUcN+z24rQ0oMSr0Vd8p9/CYSWHpQokHr5Yqj8fUZo6UfJ9+Z2VTZoiA2g5Ju3uyqbNcTRnyncvN28g655XgjowRQEMuPKptqY9Q9maodRcoipqZPWZ4hjOFNwEBDBFBwERACBgCAQCAgCgYAgEAgIAoGAIBAICAKBgG6I6H+XYX81bhJJaQAAAABJRU5ErkJggg=="
                                        width='25' style="margin-left: 10px">Época Medieval</a></li>
                                <li>
                                        <a title="Monarquía Española" style="color: white" href="subastas/monarquia-espanola">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Monarquía Española</a></li>
                                <li>
                                        <a title="M. Contemporáneas" style="color: white" href="subastas/monedas-contemporaneas">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">M. Contemporáneas</a></li>
                                <li>
                                        <a title="Monedas Extranjeras" style="color: white" href="subastas/monedas-extranjeras">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Monedas Extranjeras</a></li>
                                <li>
                                        <a title="Billetes" style="color: white" href="subastas/billetes">
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px">Billetes</a></li>

                            </div>
                        </ul>
                    </div>



                </div>
                <div class="col-md-6 col-xs-12 no-padding">
                    <div class="landing-body-header">
                        <h2>EXPOSICIÓN DE LOTES</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-7 col-md-offset-3 ul-list-landing list-content">
                            <div>
                                <li>
                                    <p>Podrá examinar los lotes en nuestras instalaciones hasta el día 29 de mayo</p>
                                </li>
                                <li>
                                    <p>De Lunes a Jueves de 9:30 – 14:00 / 16:00 – 18:30 horas</p>
                                </li>
                                <li>Los Viernes de 9:30 a 14:30 horas.</li>

                            </div>

                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <section class="carousel-fake">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-xs-12 col-sm-6 col-md-4 col-lg-3 owl-carousel-custom owl-carousel owl-theme">
                    <div class="col-xs-12">
                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="121  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/121-580-imperio-romano"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="121  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/121-580-imperio-romano">
                                            <span>Lote 121</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="121  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/121-580-imperio-romano">
                                            <img class="img-responsive lazy" alt="121  -  Imperio Romano" src="/img/load/lote_medium/001-580-4.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Casio</span>. <span class="subperiod_2">C. Cassius Longinus</span>. <span class="value">Áureo</span>. <span class="year">43-42 a.C</span>. <span class="ceca">Ceca volante</span>. (Craw-<span class="catalog_number_1">498/1</span>). (<span class="catalog_2">Babelon</span>-<span class="catalog_number_2">12</span>). (Cal-<span class="catalog_number_3">63</span>). Anv.: <span class="obverse">M AQVINVS LEG LIBERTAS. Cabeza diademada de la Libertad a derecha</span>. Rev.: <span class="reverse">C CASSI PR COS. Trípode surmontado por caldera</span>. <span class="metal">Au</span>. <span class="weight">7,92</span> g. <span class="technical_description">Precioso ejemplar con restos de brillo original. Muy rara</span>. <span class="conservation_1">EBC+</span>. Est...<span class="IMPTAS_HCES1">18000,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">12.000 €</p>


                                                </div>





                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/121-580-imperio-romano">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="32  -  Grecia Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/32-593-grecia-antigua"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="32  -  Grecia Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/32-593-grecia-antigua">
                                            <span>Lote 32</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="32  -  Grecia Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/32-593-grecia-antigua">
                                            <img class="img-responsive lazy" alt="32  -  Grecia Antigua" src="/img/load/lote_medium/001-593-26.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Reino de Tracia</span>. <span class="subperiod_2">Mitridates VI Euphator A nombre de Lisímaco</span>. <span class="value">Estátera</span>. <span class="year">120-63 a.C</span>. (Gc-<span class="catalog_number_1">1661</span>). Anv.: <span class="obverse">Cabeza masculina a derecha con cuerno de Amón</span>. Rev.: <span class="reverse">Atenea sentada a izquierda con Victoria, lanza y escudo, debajo tridente flanquedado por delfines</span>. <span class="metal">Au</span>. <span class="weight">8,27</span> g. <span class="conservation_1">EBC+</span>. Est...<span class="IMPTAS_HCES1">2300,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">1.800 €</p>


                                                </div>



                                            <div class="price-content update-bid-tr-29052019-32 hidden">
                                                <p class="puja">Precio actual</p>
                                                <p class="29052019-32 gold price"> </p>
                                            </div>


                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/32-593-grecia-antigua">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="41  -  Hispania Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/41-598-hispania-antigua"></a>




                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="41  -  Hispania Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/41-598-hispania-antigua">
                                            <span>Lote 41</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="41  -  Hispania Antigua" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/41-598-hispania-antigua">
                                            <img class="img-responsive lazy" alt="41  -  Hispania Antigua" src="/img/load/lote_medium/001-598-24.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Cartagonova</span>. <span class="value">Siclo-shekel</span>. <span class="year">235-220 a.C</span>. <span class="ceca">Incierta</span>. (Abh-<span class="catalog_number_1">490</span>). (<span class="catalog_2">Acip</span>-<span class="catalog_number_2">557</span>). (C-<span class="catalog_number_3">17</span>). Anv.: <span class="obverse">&nbsp;Cabeza masculina de ¿Eshmun-Apolo? a izquierda</span>. Rev.: <span class="reverse">&nbsp;Caballo parado a derecha</span>. <span class="metal">Ag</span>. <span class="weight">7,29</span> g. <span class="technical_description">Solamente 5 ejemplares conocidos según Villaronga. Extremadamente rara</span>. <span class="conservation_1">MBC+</span>. Est...<span class="IMPTAS_HCES1">3000,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">1.500 €</p>


                                                </div>






                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/41-598-hispania-antigua">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="99  -  República Romana" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/99-619-republica-romana"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="99  -  República Romana" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/99-619-republica-romana">
                                            <span>Lote 99</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="99  -  República Romana" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/99-619-republica-romana">
                                            <img class="img-responsive lazy" alt="99  -  República Romana" src="/img/load/lote_medium/001-619-3.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Memmia</span>. <span class="value">Denario</span>. <span class="year">106 a.C</span>. <span class="ceca">Roma</span>. (Ffc-<span class="catalog_number_1">907</span>). (<span class="catalog_2">Craw</span>-<span class="catalog_number_2">313/16</span>). (Cal-<span class="catalog_number_3">981</span>). Anv.: <span class="obverse">Cabeza laureada de Saturno a izquierda, delante S sobre punto, detrás guadaña y leyenda ROMA</span>. Rev.: <span class="reverse">Venus con cetro, en biga a derecha, coronada por Victoria encima de los caballos, en exergo L MEMMI / GAL</span>. <span class="metal">Ag</span>. <span class="weight">3,94</span> g. <span class="technical_description">Magnífico ejemplar. Brillo original</span>. <span class="conservation_1">SC-</span>/EBC+. Est...<span class="IMPTAS_HCES1">500,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">300 €</p>


                                                </div>



                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/99-619-republica-romana">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="115  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/115-563-imperio-romano"></a>




                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="115  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/115-563-imperio-romano">
                                            <span>Lote 115</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="115  -  Imperio Romano" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/115-563-imperio-romano">
                                            <img class="img-responsive lazy" alt="115  -  Imperio Romano" src="/img/load/lote_medium/001-563-7.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Julio César</span>. <span class="value">Áureo</span>. <span class="year">46-44 a.C</span>. <span class="ceca">Roma</span>. (Sydenham-<span class="catalog_number_1">1018</span>). (<span class="catalog_2">Craw</span>-<span class="catalog_number_2">466/1</span>). (Cal-<span class="catalog_number_3">37b</span>). Anv.: <span class="obverse">C CAESAR COS TER. Cabeza velada de Pietas, con rasgos parecidos a los de Julio César</span>. Rev.: <span class="reverse">A HIRTIVS PR. Atrubutos sacerdotales</span>. <span class="metal">Au</span>. <span class="weight">7,90</span> g. <span class="technical_description">Buen centraje. Rara</span>. <span class="conservation_1">EBC-</span>/MBC+. Est...<span class="IMPTAS_HCES1">5000,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">3.000 €</p>


                                                </div>




                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/115-563-imperio-romano">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="240  -  Imperio Bizantino" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/240-402-imperio-bizantino"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="240  -  Imperio Bizantino" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/240-402-imperio-bizantino">
                                            <span>Lote 240</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="240  -  Imperio Bizantino" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/240-402-imperio-bizantino">
                                            <img class="img-responsive lazy" alt="240  -  Imperio Bizantino" src="/img/load/lote_medium/001-402-15.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Anastasio</span>. <span class="value">Sólido</span>. <span class="year">491-518 d.C</span>. <span class="ceca">Constantinopla</span>. (S-<span class="catalog_number_1">5</span>). (<span class="catalog_2">Ratto</span>-<span class="catalog_number_2">321</span>). Rev.: <span class="reverse">VICTORIA AVGGG. Victoria en pie a izquierda con cruz larga, estrella en el campo</span>. <span class="metal">Au</span>. <span class="weight">4,38</span> g. <span class="technical_description">Brillo original. Magnífico ejemplar</span>. <span class="conservation_1">SC</span>. Est...<span class="IMPTAS_HCES1">1000,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">750 €</p>


                                                </div>


                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/240-402-imperio-bizantino">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="247  -  Monedas Visigodas" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/247-618-monedas-visigodas"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>

                                                        <div class="destacado" role="button">
                                            <img class="img-responsive" src="/themes/tauleryfau/assets/img/destacado.png" alt="Tauler y Fau" style="width:85px; margin-bottom: 20px; position:relative">
                                        </div>

                                    <div class="lot-title">
                                        <a title="247  -  Monedas Visigodas" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/247-618-monedas-visigodas">
                                            <span>Lote 247</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="247  -  Monedas Visigodas" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/247-618-monedas-visigodas">
                                            <img class="img-responsive lazy" alt="247  -  Monedas Visigodas" src="/img/load/lote_medium/001-618-3.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Suinthila (621-631)</span>. <span class="value">Tremissis</span>. <span class="ceca">Valentia (Valencia)</span>. (Cnv-<span class="catalog_number_1">300.1 variante</span>). Anv.: <span class="obverse">+SVINTHILAREX</span>. Rev.: <span class="reverse">+VALENTIAPIVS</span>. <span class="metal">Au</span>. <span class="weight">1,37</span> g. <span class="technical_description">Las "S" retrógradas en anverso y reverso. Con la L invertida en reverso. Rarísima</span>. <span class="conservation_1">MBC+</span>. Est...<span class="IMPTAS_HCES1">20000,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">12.000 €</p>


                                                </div>





                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/247-618-monedas-visigodas">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="281  -  Epoca Medieval" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/281-507-epoca-medieval"></a>



                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="281  -  Epoca Medieval" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/281-507-epoca-medieval">
                                            <span>Lote 281</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="281  -  Epoca Medieval" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/281-507-epoca-medieval">
                                            <img class="img-responsive lazy" alt="281  -  Epoca Medieval" src="/img/load/lote_medium/001-507-61.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Reino de Castilla y León</span>. <span class="subperiod_2">Alfonso VII (1126-1157)</span>. <span class="value">Dinero</span>. <span class="ceca">¿León?</span>. (Abm-<span class="catalog_number_1">115 variante</span>). (<span class="catalog_2">Bautista</span>-<span class="catalog_number_2">212 variante</span>). Anv.: <span class="obverse">IMPERATOR. Cruz patada con adornos lobulares</span>. Rev.: <span class="reverse">ADEFONSUS. Tres cruces en trebolillo</span>. <span class="metal">Ve</span>. <span class="weight">0,82</span> g. <span class="technical_description">Álvarez Burgos y Bautista la clasifican como Alfonso IX. Muy rara</span>. <span class="conservation_1">MBC+</span>/MBC. Est...<span class="IMPTAS_HCES1">1600,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">1.200 €</p>


                                                </div>





                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/281-507-epoca-medieval">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">

                        <div class="item_lot lot">

                            <div class="lot-wrapper">
                                <a title="304  -  Monarquía Española" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/304-564-monarquia-espanola"></a>


                                    <div class="currency_price hidden">
                                        <div class="sobreimg">price</div>
                                        <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==">
                                    </div>


                                    <div class="lot-title">
                                        <a title="304  -  Monarquía Española" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/304-564-monarquia-espanola">
                                            <span>Lote 304</span>
                                        </a>
                                    </div>
                                    <div class="lot-img">
                                        <a title="304  -  Monarquía Española" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/304-564-monarquia-espanola">
                                            <img class="img-responsive lazy" alt="304  -  Monarquía Española" src="/img/load/lote_medium/001-564-4.jpg" style="display: block;">
                                        </a>
                                    </div>

                                                        <div class="data-desc">

                                                                        <p><span class="subperiod_1">Fernando e Isabel (1474-1504)</span>. <span class="value">1/2 excelente</span>. <span class="ceca">Sevilla</span>. (Cal-<span class="catalog_number_1">172 similar</span>). (<span class="catalog_2">Tauler</span>-<span class="catalog_number_2">44</span>). Anv.: <span class="obverse">FERNANDVS:ET:HELISABET:D:G. Con F coronada entre S-S</span>. Rev.: <span class="reverse">REX:ET:REGINA:CAST:LEGI. Con Y coronada entre estrellas</span>. <span class="metal">Au</span>. <span class="weight">1,71</span> g. <span class="technical_description">Las leyendas del anverso y reverso empiezan con hoja de perjil. Estuvo encapsulada por NGC como AU 55. Rara. Ex colección Huntington</span>. <span class="conservation_1">MBC+</span>/EBC-. Est...<span class="IMPTAS_HCES1">2500,00</span>. </p>
                                        </div>
                                                    <div class="data-content">



                                                <div class="price-content">



                                                        <p class="puja">Precio salida</p>
                                                        <p class="price">1.700 €</p>


                                                </div>


                                            <div class="btn-pujar">


                                                    <a class="btn btn-custom" href="/es/lote/29052019-subasta-30-sala-29-mayo-2019-1481/304-564-monarquia-espanola">Pujar <i class="fa fa-hand-paper-o"></i></a>


                                            </div>




                                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <div class="landing-slogan">
        <div class="container">
            <div class="row">
                {{-- <div class="col-xs-12">
                    <p>"Combinamos la experiencia de los profesionales con la pasión de los coleccionistas"</p>
                </div> --}}
                <div class="col-xs-12 aditional-text" style="    margin: 60px 0;padding: 0 20px;text-align: justify;">
                    <p style="margin-bottom: 20px;"><a style="color: #337ab7">Tauler&Fau-Herrero</a> le da la
                        bienvenida a su portal
                        de subastas numismáticas online. Ahora podrá participar en las subastas a través de nuestro
                        sistema de pujas online. Dispone de multitud de ventajas:
                    </p>

                    <ul style="    list-style: unset; padding-left: 70px">
                        <li>Aviso de sobrepuja.</li>
                        <li>Precio actual.</li>
                        <li>Histórico de pujas.
                        </li>
                        <li>Cambio a las divisas más utilizadas: USD, GBP, CHF…
                        </li>
                        <li>Fotografías de alta calidad, fotografías de canto e imágenes 360º.
                        </li>
                        <li>Lotes recomendados.
                        </li>
                        <li>Innovador sistema de pujas en tiempo real, dónde podrá vivir la adrenalina de la subasta
                            desde la comodidad de su casa.
                        </li>


                    </ul>
                </div>
            </div>
            <div class="col-xs-12">
                <p class="second-slogan">Subastas numismáticas mensuales que abarcan los distintos periodos de la
                    numismática, desde Grecia Antigua hasta la actualidad.

                </p>
                <p class="second-slogan">Visite ya nuestra web y disfrute de la <a style="color: #337ab7"
                        href="/es/info-subasta/29052019-subasta-30-sala-29-mayo-2019">Subasta 30 Sala</a> que se celebrará el día 29 de mayo de 2019 a las 16:00 horas (CEST), en Hotel VP Plaza España Design

                </p>

                <h3>¡Puje en vivo
                        en las
                        subastas numismáticas de Tauler&Fau- Herrero!</h3>

            </div>
        </div>
    </div>


    <style>
    .bid-large-title h4,
    h2 {

        border-bottom: 0px solid #a37a4c;

    }


    .list-content {
        display: flex;
        justify-content: center;
        font-size: 17px;
    }

    .second-slogan {
        color: #283847;
        font-size: 16px;
        font-weight: 100;
    }

    .aditional-text {
        font-size: 14px;
        font-weight: 100
    }


    .landing-body .row {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }

    .lots {
        margin-bottom: 35px;
    }

    .landing-slogan {
        padding: 40px 0;
        color: #283847;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
    }

    .destacado {
        position: absolute;
        /* background: #f1ece6; */
        border-radius: 50%;
        opacity: 1;
        left: 0px;
        top: 0px;
        /* border: 4px solid #a37a4c; */
        z-index: 2;
        /* box-shadow: 0px 0px 6px rgba(0,0,0,.5); */
    }

    .carousel-fake-cat,
    .carousel-fake-desc {
        color: #5C5C5C !important;
        font-style: italic !important;
        font-size: 14px;
        padding: 0 15px;
    }

    .carousel-fake-desc {
        color: #5C5C5C !important;
        font-style: italic !important;
        font-size: 14px;
        display: block;
        display: -webkit-box;
        height: 32px;
        font-size: 14px;
        line-height: 1.2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        padding: 0 15px;
        font-style: italic;
    }

    .lot-img {
        position: relative;
        padding: 0 5px;
        text-align: center;
        cursor: pointer;
        text-align: center;
        height: 145px;
        transition: .2s all linear;
        cursor: pointer;
        padding: 0 5px;
    }

    .owl-carousel-home, .owl-stage-outer, .owl-stage, .owl-item, .item {
    height: auto !important;
}

    .lot-img img {
        margin: 0 auto;
        max-height: 145px;
    }

    .color-letter {
        color: #283847;
    }

    .carouse-fake-title {
        font-size: 20px;
        color: #283847;
        text-align: center;
    }

    .carousel-fake {
        padding: 100px 0;
        padding-bottom: 0px;
    }

    .landing-body-list-content {
        background-color: #283847;
        color: white;
        height: 290px;
        font-size: 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;



    }

    .ul-list-landing {
        padding-left: 30px;
    }

    .landing-body-list-content li i {
        margin-right: 5px;



    }

    .landing-body-list-content li {
        margin-bottom: 10px;



    }

    .landing-body-header {
        height: 60px;
        font-size: 20px;
        font-weight: 900;
        color: #283847;
        text-align: center;
        padding-top: 17px;
        background: white;
    }

    .landing-body-header h2 {
        font-size: 24px;
        font-weight: 900;
        color: #283847;
        margin: 0;
    }

    body.landing header {
        min-height: 80px;
    }

    body.landing .slogan-principal {
        font-weight: 900;
        font-size: 25px;
    }

    body.landing .super-slogan {
        background-color: #283847;
    }

    body.landing .super-slogan .slogan-principal {
        font-weight: 900;
        font-size: 32px;
        margin: 15px 0;
        color: white;
    }

    .slogan-principal h1 {
        font-weight: 600;
        margin-top: 10px;
        font-size: 32px;
    }

    body.landing .header-desktop {
        margin: 0;
    }

    .ventajas-landing .ventajas-title {
        height: 60px;
        font-size: 20px;
        font-weight: 900;
        color: #283847;
        text-align: center;
        padding-top: 17px;
        background: white;
    }

    .ventajas-landing .ventajas-title h2 {
        font-size: 24px;
        font-weight: 900;
        color: #283847;
        margin: 0;
    }

    .href-cell {
        color: white;
        text-decoration: underline;

    }

    .box-align {
        width: 315px;
        margin: 0 auto;
    }

    body.landing header .logo a img {
        width: 370px;
    }

    .ventajas-landing {
        min-height: 375px;
        background-color: #283847;
        position: relative;
    }

    .banner-landing {
        padding: 18px 20px;
        background: #a88c69;
        width: 35%;
        position: absolute;
        bottom: -45px;
        color: white;
        padding-right: 30px;
        padding-top: 40px;
        padding-bottom: 40px;
        z-index: 10;
    }

    .banner-landing-title,
    .banner-landing-cell {
        font-size: 30px;
        font-weight: 900;
    }

    @media screen and (max-width: 1200px) {
        .destacado {

            left: 0;

        }

        .banner-landing-title,
        .banner-landing-cell {
            font-size: 25px;
            font-weight: 900;
        }

        .banner-landing {
            padding: 18px 20px;
            background: #a88c69;
            width: 35%;
            position: absolute;
            bottom: -45px;
            color: white;
            padding-right: 30px;
            padding-top: 20px;
            padding-bottom: 20px;
            z-index: 10;
        }
    }

    @media screen and (max-width: 1200px) {}

    @media screen and (max-width: 991px) {
        .landing-body-list-content {

            text-align: center;
            font-size: 20px;

        }

        .banner-landing {
            padding: 18px 20px;
            width: 598px;
            padding-top: 40px;
            padding-bottom: 40px;
            z-index: 10;
        }
    }

    @media screen and (max-width: 768px) {

        .logo {
            margin-top: 10px;
        }

        .banner-landing {

            width: 400px;

        }

        .landing-body-list-content {

            height: 267px;

        }

        .landing-body-list-content {
            font-size: 20px;
            text-align: center;
        }
    }

    @media screen and (max-width: 576px) {



        .slogan-principal h1 {
            font-weight: 600;
            margin-top: 10px;
            font-size: 20px;
        }

        .landing-body-header h2 {
            font-size: 18px;
            font-weight: 900;
            color: #283847;
            margin: 0;
        }

        .landing-body-list-content {
            font-size: 16px;
        }

        .banner-landing {

            width: 235px;
        }

        .banner-landing-title,
        .banner-landing-cell {
            font-size: 15px;
        }

        .landing-slogan {
            padding: 0px 0;

        }

        .destacado {
            left: 35px;
        }

        .landing-body .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .landing-body-list-content {
            height: auto;
            padding: 15px 5px;
        }

        .banner-landing {
            position: fixed;
            bottom: 43px;
            left: 0;
        }

        .list-content {
            flex-direction: column;

            text-align: left;
            padding-left: 20px;
            font-size: 16px;

        }

        .list-content div {
            padding-right: 0 !important;


        }

        .carousel-fake {
            padding: 30px 0;
            width: 100%;
        }
    }
    </style>
    <script>
    $(".owl-carousel").owlCarousel({

        items: 4,
        loop: true,
        autoplay: true,
        margin: 20,
        dots: true,
        nav: false,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 4
            },
            1200: {
                items: 4
            },
        }
    });
    </script>
</body>
@stop
