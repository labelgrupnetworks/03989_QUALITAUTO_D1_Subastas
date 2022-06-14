<div class="{{$class_square}} square">

    <div class="item_lot lot">
        @if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))

            @if($item->oferta_asigl0 == 2)
				@php
				($class_color = 'hot-sale')
				@endphp

            @else
                <?php
                $porcentaje = 0;
                if($item->imptas_asigl0>0){
                    $porcentaje = round(((($item->imptas_asigl0 -  $item->impsalhces_asigl0)/$item->imptas_asigl0) *100), 0);
                }
                if( $porcentaje> 19){
                    $class_color = 'd-50';
                }elseif($porcentaje<= 19 && $porcentaje> 0){
                    $class_color = 'd-10';
                }else{
                    $class_color = 'd-20';
                }

                ?>
            @endif

            <div class="discount {{$class_color}}">
                @if($item->oferta_asigl0 == 1)
                    <div>{{$porcentaje}}%</div>
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.discount') }}</div>
                @elseif($item->oferta_asigl0 == 2)
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.gran') }}</div>
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.hot_sale') }}</div>
                @endif
            </div>

        @endif

        <div class="lot-wrapper">
            <a title="{{ $titulo }}" <?= ($item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')?$url:'';?> ></a>
			{{--
			@if( Config::get('app.icon_multiple_images') && $numFotos > 1)
                    <span class="more-picture" href="#" data-toggle="tooltip" title="{{ trans(\Config::get('app.theme').'-app.lot_list.more_pictures') }}">
                        <span>{{ trans(\Config::get('app.theme').'-app.lot_list.more_pictures') }}</span>
                        <img src="/themes/{{\Config::get('app.theme')}}/assets/img/more-picture.png" />
                    </span>
			@endif
			--}}

                @if(!empty($item->contextra_hces1))
                    <div class="more-360">
                        <div class="sobreimg">{{ trans(\Config::get('app.theme').'-app.home.hover360') }}</div>
                        <img width="32px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE0AAABKCAYAAAD+DNkIAAAACXBIWXMAAAsSAAALEgHS3X78AAAJm0lEQVR4Ae1cTWgUVxz/t1Wr2MR0U2O2aburRElIgkloS7SgCVhY6aHJqXgojezJU+Oxp82eemx68rQY6UFKD0aKdUEhG6E2eMgHGiIa6C40GEXTxKRE04DlN/teupl5XzM7s7tCfrBEd2bfzPzm//3+773x6tUr2oY7vLnNl3tsk+YB26R5wI5yXTidStQQUTcRtbMP/h8loojg9GkiWiKiLPtkiGgqFk8uleHWqaSOIJ1KgJx+RtZRH4YEmcNENBKLJ7M+jGeEwEljEgWiBiRS5BdA4BAjMFAJDIw0RhYeopeI9gVyETGW2XWHgiLPd9IYWQPsU0qy7AB5A7F4ctjvgX0lLZ1KdDMbE6QausUYI2/KrwF9Iy2dSkAlvnX7u1A4StWhMO2pqqGq2nrhOWsrS7S2ukSLj/6klWcL9O/6C7eXgdQNxuLJIbc/FKFo0pg6Zky94Z53auhApJnqok0Uqo96uubK4gItPsrS/MNJev5swc1PLzGpK8rWFUUaCyFGTNQREhVtOUZ1kSbP1xMBBGbvjVsEGgJetrsY4jyTxgjL6Ix9dW09NXWd9ixVpoD6zk1kTMkrijhPpJkQtnPXbmrs7KFIS5d2vI31F/ScqZwI1aH6vM0LiW1eISB5d29dMVFbz8S5Jo3ZsKyKMEhXx6kzlv2SAQ83/2CKHudmLSkxAV4E1Lwu0kwHIk20Y9du6a/uj6cpO/OHblRPxLkizcToNxzuoLYTvdIx5h9O0dzEqDFRMoBAXCvS2iV9ObjW/fHrOm97KRZP9ru5tlvSEIN9IzsOdWzs6BYe84ss2XWjLV1CyYNE37l2UUfceTfhiDFp6VQC4nNFdrztRB81HG53fA+SYGNk9sovQNraTvYJHY4hcR2mAbBRPY2ppTQdgZqICHuSu0+3r1wInDBiLwfEzE1mHMfgQD794qxuCON0y7QIOSQz/DDMIhsGdZy4edlL9F4UYALu3hpxDAHioA0KHE2nEoMm19aSxvJJoR2DMe48dcbxPQiDSpYLiNVExEEbDqiD6wGmVUqYSJqU/Y7PzziMb7kJ+/8+xMRB2nbKQ5V9rDqjhJI0JmUnRcfwxuxGFwYXLr5SAOJyM+Nb7gYvmaspD8BPff1d4SlaadPNEUjjF6RGdkDCSm3DdJgdv27Z3cJsAvkvzArPgxcXtjgqLm1SDZNKGmNbaMvgLe0BJbyWy4pDySAyF5rCgTLYVamn9IcNR7aGF3D3uXvalKVsqAqFrfxWho2XjmMRFpcKoVJP49QC1YVKU0uTNIsDxQIBelnZywGhpDHVlOaXMPZ4cwhe4aFc1LJKClRGdIQpIM4HZWmULmV6nQAvD2+pqohM3rxMj3P3RYcOiuZTZTZNynIx4NVbPIgiVrLAy0D4iJAvmzdZIQPUUDYeyPgdqdyCPJVTmBYhDzKb5kwkiwAeCLlfoduHet/57aLU4yJwRhwIJzP28w9bjonKTxvrMZodTwtNBc9LZVUYhZMQ8iCTNF9JAwEgzPKyLNiEunQIUjAA0sgDZ3u4kFe3PGGQHoQ6+JsPWnutAqgMyEtvj1xwlKcUoZIr0nyb5IUacQLwthFscuKsYzb1y0fpeWnAefYKCaSFWFxoVTUmRq2/T5hNirQcU94PCELlBemeAYSkOdSTpU6+gashMTUhlt7wuQO7anCjnZ8oGXXcBlfx+Qdb1RBjImCFx9QBNgwS/CQ3S5FWJclC4Qm81Qo3yKWFz3lySYJaFaoGVI9H6jj35Ffnrd/OTY46VMiuYl7iRDgJiddUoqRNfQ1HOqipK7bp/jGxwrGzIJHmwHkg8XjvOakXDRrpVMJx4bJ2QkZbuzZDBagJSEKlBN4ynUpYf1dYtK4pIAZ6m/YvSkoabBTIgI2DLYN94jYFBULL/l27uKl6+Dtx47L1b5HTKBfKImmWnZrI1/I5ESAFOaDdNlmNL4LAVBcc+4VYPOmYdAicNBT4YvGkQ0rWVv92nCsjolows27vMIKDKRVEpEnzDcRIKN65ecvcc0Zt8RNXS16WQZwFdUW0X4jCvBFtVtzGFY4HKeVeF21ZQeOtwcGtBcrGzp6lucmMsGoZCh+kD5s+po+aP6F/lp9aHx1erq1aROyteY9C7x+kPVXvUnPXaarZ/4H1y5nbv1oqiLHCh9oofKiVqmvDtLdmv/WS+EQIgtmnf83R+tqqdR4fD/fE6/6widOjvyhrZy4x1tjZ45jak6nntGpsngKZSB0kDeUjPAgyA+R+PEDF91wSEYfBQYBASA3O45kEMgMe6D5m5SjrJdZHN+dbN1jA6vMMvnAwWXCbNWnSw8MhAMXNqoJEROuIvtG4gogdKoSuRlEOiDAD0lVVG5aeh/HwPezYjrd3W+dh/AAKocJcS0YaPMaX9i9FBplLHTzc3TH5m8YDmRYrTSJ1XMegK6hYOKfrFeopPBlvVQaoymd95xwGvxJh6shE4YaUNNYIknP7vJA6pEmiWfdyA0ThheLFwqQY4Kr0ORW/zajaqkSAMc7OjFfkzBTmaQubdGA3NSZAKGWkIc3Ri6aapIBNm7xR+oYXE6Awae9qgqPRkCbtIpJmBEyft6ioijTYNJNaVjkgSvbhfRW4qmop1aVRwu7ADcsTOr2xprmkLEAgbW9wRvah6ZlTdkXqSBtmqz0sgCxE5oilrO5GWyJt0ANWUliTxbbucjzDrLpJZ0zmNTmUpDER3WT95k/fW5E5t1uwYfaUpY7NM1YCYaJmQ8xYabIGbWOftsoRiycHZeEHyEO3o/OG28tKnIwwmBRNgK2VMnJRGpI2uvHc0g4Qh7nOUts4xGIiwviiDA2M+leMSIvFkyOqYA9vT+QY4FGP95Wmvs9bWRFc28G7uzVImi7pdtMSr12pAk8lW9YT5DoCSBdmuIpYRzAdiyeNJ8jdLr7A3JtzMrIApVyxUsemA4tcsYLooN3NxgFe1kZB75Wy/hqtjQJ6TIx/IbyuwlMu96HXYxUecNbLGvdi1ntqiSM22wTyKmy9J3kljHxYWWxEHAW4stgDWVQMYeTTGnatjSuEH2vYQRTyRw9r2GH0+1kI5Rm+7JbAvOqIlxYtN7slrDx7ZJHk0fNOM8KK3mrCzy0mahhxwhUuZcaPbIsJX4LEIHaA6Vet2isxcky6XIUUOgSy11AFbJ3j6+YldgS6q1UZyMsxKR8Ocmerku2fxtS2VzSfWiSWmS0dKdYrmqLku48W7NDHd+lz6ziW2cw37FTGb3tlgorYspW1aEbZFoeiagMnpmxbGhZie59bD9jefdQtiOg/hSAPI2czmr4AAAAASUVORK5CYII=" />
                    </div>
                @endif

                <div class="currency_price hidden">
                    <div class="sobreimg">price</div>
                    <img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjMycHgiIGhlaWdodD0iMzJweCIgdmlld0JveD0iMCAwIDQ3IDQ3IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NyA0NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJMYXllcl8xXzEyOV8iPgoJCTxnPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yMC43MSwxNS44MmMwLjIzMy0yLjUzMiwxLjEzMS00Ljg3LDIuNTE4LTYuODQ3QzIyLjEsMy44NDgsMTcuNTI3LDAsMTIuMDcsMEM1Ljc3LDAsMC42NDMsNS4xMjcsMC42NDMsMTEuNDI5ICAgICAgYzAsNS40ODQsMy44ODQsMTAuMDc1LDkuMDQ2LDExLjE3NEMxMi4yODYsMTkuMDY0LDE2LjIwMSwxNi41NjMsMjAuNzEsMTUuODJ6IE03LjQ5OSwxNC4xNDljMC0wLjQxNiwwLjMxOS0wLjczNywwLjczNi0wLjczNyAgICAgIGgyLjM0NXYtMC41NDhsLTAuMzIxLTAuNTQ4SDguMjM0Yy0wLjQxNywwLTAuNzM2LTAuMzIxLTAuNzM2LTAuNzM3czAuMzE5LTAuNzM3LDAuNzM2LTAuNzM3aDEuMTUzbC0yLjQ5NC00LjEgICAgICBjLTAuMTMyLTAuMjI3LTAuMjY0LTAuNTEtMC4yNjQtMC43NzVjMC0xLjAzOSwwLjk2NC0xLjM3OSwxLjYyNC0xLjM3OWMwLjc5NCwwLDEuMTUzLDAuNjI0LDEuMjg2LDAuODg4bDIuNTMyLDQuODE5bDIuNTMtNC44MTkgICAgICBjMC4xMzUtMC4yNjQsMC40OTItMC44ODgsMS4yODQtMC44ODhjMC42NjIsMCwxLjYyNSwwLjM0MSwxLjYyNSwxLjM3OWMwLDAuMjY1LTAuMTMyLDAuNTQ4LTAuMjYzLDAuNzc0bC0yLjQ5NSw0LjEwMWgxLjE1MiAgICAgIGMwLjQxNSwwLDAuNzM2LDAuMzIxLDAuNzM2LDAuNzM3cy0wLjMyMSwwLjczNy0wLjczNiwwLjczN2gtMi4wMjFsLTAuMzIxLDAuNTQ4djAuNTQ4aDIuMzQzYzAuNDE1LDAsMC43MzYsMC4zMjEsMC43MzYsMC43MzcgICAgICBzLTAuMzIxLDAuNzM3LTAuNzM2LDAuNzM3aC0yLjM0M3YxLjg3YzAsMC45MDctMC41NDksMS41MTItMS40OTEsMS41MTJjLTAuOTQ1LDAtMS40OTItMC42MDUtMS40OTItMS41MTJ2LTEuODdIOC4yMzQgICAgICBDNy44MTcsMTQuODg2LDcuNDk5LDE0LjU2NSw3LjQ5OSwxNC4xNDl6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQkJPGc+CgkJCQk8cGF0aCBkPSJNMzQuOTI5LDUuNzE0Yy01Ljc2OSwwLTEwLjU0MSw0LjMwMi0xMS4zMDksOS44NjRjMi4xODEsMC4wMTYsNC4yNTksMC40NDcsNi4xNzUsMS4yMDcgICAgICBjMC4xMjctMC4yOTIsMC4zODktMC40NzUsMC43OTctMC40NzVoMC4zOTZjLTAuMzk2LTAuNTY3LTAuNTY2LTEuMjg1LTAuNTY2LTIuMDIyYzAtMi4zNDIsMi4wNzgtMy45ODYsNC43NDItMy45ODYgICAgICBjMy4zMSwwLDQuOTE0LDEuNzc2LDQuOTE0LDMuMjVjMCwwLjg1MS0wLjU4NiwxLjMyMy0xLjQxOCwxLjMyM2MtMS42NjIsMC0wLjY0My0yLjMwNS0zLjE5My0yLjMwNSAgICAgIGMtMS4xMTQsMC0yLjE3MiwwLjY2Mi0yLjE3MiwxLjg4OWMwLDAuNjQzLDAuMzIsMS4yODUsMC42MjMsMS44NTJoMi4wNmMwLjc3NCwwLDEuMTcyLDAuMjg0LDEuMTcyLDAuOTA3ICAgICAgcy0wLjM5NywwLjkwNi0xLjE3MiwwLjkwNmgtMS40OTRjMC4wNTgsMC4xNTIsMC4wOTcsMC4yODQsMC4wOTcsMC40NTRjMCwwLjMwNS0wLjA3NiwwLjYwNy0wLjE5NCwwLjkwMiAgICAgIGMwLjYxOCwwLjUxLDEuMTg4LDEuMDc2LDEuNzI5LDEuNjY3YzAuNTI0LDAuMTA2LDAuOTkxLDAuMjI4LDEuNTQ1LDAuMjI4YzAuMzE5LDAsMS4wOTgtMC4yMDgsMS4zOTctMC4yMDggICAgICBjMC42OTgsMCwxLjA5NiwwLjUyOSwxLjA5NiwxLjIwOGMwLDEuMDY2LTAuOTU0LDEuNTI5LTEuOTM3LDEuNTkzYzAuNjUyLDEuMDk1LDEuMTk1LDIuMjU5LDEuNTk2LDMuNDkgICAgICBjMy44NjEtMS44MzUsNi41NDUtNS43NjMsNi41NDUtMTAuMzE3QzQ2LjM1NiwxMC44NDEsNDEuMjI5LDUuNzE0LDM0LjkyOSw1LjcxNHoiIGZpbGw9IiNiNzljN2UiLz4KCQkJPC9nPgoJCQk8Zz4KCQkJCTxwYXRoIGQ9Ik0yNC4yNTcsMzQuMDc2djQuNjc2YzEuNDE1LTAuMDk0LDIuOTA1LTAuNzU1LDIuOTA1LTIuMzE0QzI3LjE2MiwzNC44MzEsMjUuNTI5LDM0LjM1OSwyNC4yNTcsMzQuMDc2eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIwLjE5MywyOC40NzljMCwxLjE4MywwLjg3NSwxLjg2NSwyLjY0NSwyLjIyMXYtNC4yMjlDMjEuMjMsMjYuNTIsMjAuMTkzLDI3LjQ2MywyMC4xOTMsMjguNDc5eiIgZmlsbD0iI2I3OWM3ZSIvPgoJCQk8L2c+CgkJCTxnPgoJCQkJPHBhdGggZD0iTTIzLjUsMTguNDI5Yy03Ljg3NywwLTE0LjI4Nyw2LjQwOS0xNC4yODcsMTQuMjg2UzE1LjYyMyw0NywyMy41LDQ3YzcuODc3LDAsMTQuMjg4LTYuNDA3LDE0LjI4OC0xNC4yODUgICAgICBTMzEuMzc3LDE4LjQyOSwyMy41LDE4LjQyOXogTTI0LjI1Nyw0MS4xNjJ2MS40NjVjMCwwLjQwMi0wLjMxLDAuODAzLTAuNzExLDAuODAzYy0wLjQwMSwwLTAuNzA4LTAuNC0wLjcwOC0wLjgwM3YtMS40NjUgICAgICBjLTMuOTktMC4wOTQtNS45NzYtMi40OC01Ljk3Ni00LjM0OGMwLTAuOTQyLDAuNTY2LTEuNDg2LDEuNDY0LTEuNDg2YzIuNjQ1LDAsMC41ODksMy4yNiw0LjUxMiwzLjQyNXYtNC45MzcgICAgICBjLTMuNDk4LTAuNjM3LTUuNjItMi4xNzItNS42Mi00Ljc5NWMwLTMuMjExLDIuNjY4LTQuODY1LDUuNjItNC45NTl2LTEuMjU4YzAtMC40MDMsMC4zMDctMC44MDQsMC43MDgtMC44MDQgICAgICBjMC40MDEsMCwwLjcxMSwwLjQwMSwwLjcxMSwwLjgwNHYxLjI1OGMxLjgzOSwwLjA0OSw1LjYxOCwxLjIwMyw1LjYxOCwzLjUyYzAsMC45MjEtMC42ODYsMS40NjItMS40ODgsMS40NjIgICAgICBjLTEuNTM1LDAtMS41MTQtMi41MjQtNC4xMjktMi41NzN2NC40ODdjMy4xMTgsMC42NjIsNS44NzksMS41ODIsNS44NzksNS4yMjJDMzAuMTM3LDM5LjM0NCwyNy43NzMsNDAuOTUxLDI0LjI1Nyw0MS4xNjJ6IiBmaWxsPSIjYjc5YzdlIi8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />
                </div>

                @if($item->destacado_asigl0 == 'S')
                    <div class="destacado" role="button">
                        <img class="img-responsive" src="/themes/{{\Config::get('app.theme')}}/assets/img/destacado.png"  alt="{{(\Config::get( 'app.name' ))}}" style="width:85px; margin-bottom: 20px; position:relative"/>
                    </div>
				@elseif($item->transport_hces1 == 'S')
                    <div class="destacado" role="button">
                        <img class="img-responsive" src="/themes/{{\Config::get('app.theme')}}/assets/img/no_export_fee.png"  alt="{{(\Config::get( 'app.name' ))}}" style="width:100px; margin-bottom: 20px; position:relative; margin-top: -1px"/>
                    </div>
				@endif

                <div class="lot-title">
                    <a title="{{ $titulo }}" <?= ($item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')?$url:'';?>  >
                        <span>{{ trans(\Config::get('app.theme').'-app.lot.lot-name') }} {{ $item->ref_asigl0 }}</span>
                    </a>
                </div>
                <div class="lot-img">
                    <a title="{{ $titulo }}" <?= ($item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')?$url:'';?> >
                        <img class="img-responsive lazy" src="{{Tools::url_img('lote_medium_large',$item->num_hces1,$item->lin_hces1)}}" alt="{{ $titulo }}">
                    </a>
				</div>

				@if($rarity)
				<div class="rarity-lot">
					<p class="m-0">{{ $rarity }}</p>
				</div>
				@endif

                @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                    <div class="data-desc" @if($rarity) style="-webkit-line-clamp: 2; height: 33px;" @else style="-webkit-line-clamp: 3; height: 50px; margin-top: 18px" @endif>

						@if( \Config::get('app.descweb_hces1'))
							{!!$item->descweb_hces1!!}
						@elseif ( \Config::get('app.desc_hces1' ))
							{!!$item->desc_hces1!!}
                        @endif

                    </div>
                @endif
                <div class="data-content">

                    @if( $item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')

                        @if($item->tipo_sub != 'V')
                            <div class="price-content">

                                @if( \Config::get('app.estimacion'))

                                    <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
                                    <p class="price">{{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>

                                @elseif( \Config::get('app.impsalhces_asigl0'))

                                    <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.lot-price')}}</p>
                                    <p class="price">{{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>

                                @endif
                            </div>
                        @else

                        	<div class="price-content">
                            	@if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))
                                	<p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.price_salida_venta') }}</p>
                            	@else
                                	<p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.precio_estimado') }}</p>
                            	@endif
                            	<p class="price <?=!empty($item->oferta_asigl0) && $item->oferta_asigl0 == 1?'tachado':'';?> gold">{{$estimacion}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                        	</div>

                        	<div class="price-content">
                            	@if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))
                                	<p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</p>
                            	@else
	                                <p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
    	                        @endif
        	                        <p class="price gold">{{ $precio_salida }}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
            	            </div>
                    	@endif
                    	<?php  $sub_ref= $item->cod_sub.'-'.$item->ref_asigl0; ?>

                    	@if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' || ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P')) && $item->cerrado_asigl0 == 'N' && $hay_pujas)

                        	<div class="price-content">
								<p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            	<p class="{{$sub_ref}} {{$winner}} price">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
							</div>

                    	@elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' || ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P') )

                        	<div class="price-content remove-bid-tr-{{$sub_ref}}">
                            	<p class="puja {{$sub_ref}}">
                                	@if($item->cerrado_asigl0 == 'N')
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}
                                    @endif
                              	</p>
                        	</div>
                        	<div class="price-content update-bid-tr-{{$sub_ref}} hidden">
                            	<p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            	<p class="{{$sub_ref}} {{$winner}} price"> </p>
                        	</div>
                    		<?php /* 2018_04_10 lo quito por que la W abierta funcionará como la O
                    		@elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P' && $item->cerrado_asigl0 == 'N'  )
                        	<div class="price-content">
                            <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                            <p class="price gold">{{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                        	</div>
                    		@elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P' && $item->cerrado_asigl0 != 'N'  )<?php //ponemos el espacio para que no descuadre ?>
                        	<div class="price-content">
                            <p class="puja"></p>
							</div>
							*/?>
                    	@endif
                    	@if( \Config::get('app.awarded'))

	                        <div class="btn-pujar">

                            	@if($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) ||$item->desadju_asigl0 =='S') && $item->tipo_sub == 'V' )

                                	<a class="btn btn-custom" style="background: #bc9c7e;color: white; border-radius: 0" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.sold_lot') }}</a>

                            	@elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )

                                	@if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                    	@php($precio_venta = $item->impadj_asigl0)@endphp
                                	@endif

                                	<a class="btn btn-custom buy-to" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</a>

                            	@elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) ||$item->desadju_asigl0 =='S'))

                                	<a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</a>

                            	@elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta) && $item->compra_asigl0 == 'N')

                                	<a class="btn btn-custom " <?= $url?>> {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</a>

                            	@elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta))

                                	<a style="border-radius: 0px" class="btn btn-custom buy-especial" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }} <i class="fa fa-shopping-cart"></i></a>

                            	@elseif ($item->tipo_sub == 'V'  )

                                	<a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }} <i class="fa fa-shopping-cart"></i></a>

                            	@elseif(!empty($data['sub_data']) && $data['sub_data']->tipo_sub =='W' && strtotime($data['sub_data']->end) > time() && strtotime($data['sub_data']->start) < time() && $subasta_finalizada == false)

                                	<?php
                                    //en caso de que este el tiempo real pujando en ese momento, activamos un texto que le
                                    //avisa al cliente y lo dirige a pujar en vivo.
                                    $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['sub_data']->cod_sub."-".str_slug($data['sub_data']->name)."-".$data['sub_data']->id_auc_sessions;
                                	?>
                                	<a href="{{ $url_tiempo_real }}" target="_blank" class="btn btn-custom">{{ trans(\Config::get('app.theme').'-app.subastas.bid_online_now') }}</a>

                            	@else

                                	<a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.lot.pujar') }} <i class="fa fa-hand-paper-o"></i></a>

                            	@endif

							</div>

                    @endif

                    @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')

                        <div class="timeLeft">
                            <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            <i class="far fa-clock"></i>
                            <!--<span class="clock"></span>-->
                        </div>

                    @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O' || $item->tipo_sub == 'V' || $item->tipo_sub == 'W')

                        <div class="timeLeft d-flex align-items-center">

							@if ($item->tipo_sub != 'V')


								@if(!Session::has('user'))
									<a class="inline-flex valign btn add_fav-responsive"
									href="javascript:action_fav_lote('', null, null, null)">
									<i class="far fa-star"></i>
									</a>
								@elseif (!empty($item->id_web_favorites))
									<a class="inline-flex valign btn del_fav-responsive"
									<?php /* pasamos el código licitador a 0 por que ya se calcula en el controlador al guardarlo */ ?>
										href="javascript:action_fav_lote('remove', {{$item->ref_asigl0}}, '{{$item->sub_asigl0}}', 0)">
										<i class="fa fa-star star-{{$item->ref_asigl0}} heart"></i>
									</a>
								@else
									<a class="inline-flex valign btn  add_fav-responsive"
									<?php /* pasamos el código licitador a 0 por que ya se calcula en el controlador al guardarlo */ ?>
										href="javascript:action_fav_lote('add', {{$item->ref_asigl0}}, '{{$item->sub_asigl0}}', 0)">
										<i class="far fa-star star-{{$item->ref_asigl0}}"></i>
									</a>
								@endif

								@if(count($videos ?? []) > 0)

									<a href="javascript:loadVideoMobileGrid('{{$videos[0]}}', '{{$item->ref_asigl0}}', '{{$item->cod_sub}}')" class="d-flex align-items-center view-video js-video-mobile hidden-lg">
										<span class="btn-play">VIDEO</span>
									</a>

									<a href="javascript:moreImagesGrid('{{$item->ref_asigl0}}', '{{$item->cod_sub}}','{{$item->num_hces1}}', '{{$item->lin_hces1}}', '{{addslashes($titulo)}}', '{{addslashes($item->descweb_hces1)}}', '{{ $item->cerrado_asigl0 == 'S' ? trans(\Config::get('app.theme').'-app.lot.view_lot') : trans(\Config::get('app.theme').'-app.lot.pujar') }}', '{{$url_friendly}}')"
										class="d-flex align-items-center view-video more-images-grid-js align-items-center hidden-xs hidden-sm hidden-md">
										<span class="btn-play" style="font-size: 13px;">VIDEO</span>
									</a>

									{{--
									<a class="d-flex align-items-center btn view-video d-flex align-items-center hidden-xs hidden-sm hidden-md"
										href="javascript:modalVideo('{{$videos[0]}}', '{{$item->ref_asigl0}}', '{{$item->cod_sub}}', '{{$titulo}}', '{{$item->descweb_hces1}}', '{{ $item->cerrado_asigl0 == 'S' ? trans(\Config::get('app.theme').'-app.lot.view_lot') : trans(\Config::get('app.theme').'-app.lot.pujar') }}', '{{$url_friendly}}')">
										<span class="btn-play">VIDEO</span>
										<i class="fa fa-play" aria-hidden="true"><span></span></i>
										<p class="video-text">{{ trans(\Config::get('app.theme').'-app.lot_list.watch_video') }}</p>
									</a>
									--}}
								@elseif($numFotos > 1)

								{{-- desktop --}}
								<a href="javascript:moreImagesGrid('{{$item->ref_asigl0}}', '{{$item->cod_sub}}','{{$item->num_hces1}}', '{{$item->lin_hces1}}', '{{addslashes($titulo)}}', '{{addslashes($item->descweb_hces1)}}', '{{ $item->cerrado_asigl0 == 'S' ? trans(\Config::get('app.theme').'-app.lot.view_lot') : trans(\Config::get('app.theme').'-app.lot.pujar') }}', '{{$url_friendly}}')" class="d-flex align-items-center view-video more-images-grid-js hidden-xs">
									<span class="btn-play" style="font-size: 13px;">{{ trans(\Config::get('app.theme').'-app.lot_list.more_images') }}</span>
								</a>

								{{-- mobile --}}
								<a href="javascript:moreImagesGridMobile('{{$item->num_hces1}}', '{{$item->lin_hces1}}', 0)" class="d-flex align-items-center view-video more-images-grid-js hidden-sm hidden-md hidden-lg">
									<span class="btn-play" style="font-size: 13px;">{{ trans(\Config::get('app.theme').'-app.lot_list.more_images') }}</span>
								</a>

								@else
								<a class="d-flex align-items-center view-video"></a>
								@endif


                                <img src="/themes/{{\Config::get('app.theme')}}/assets/img/auction.png" width="16px" height="16px" style="margin-right: 5px;"/>{{ $bids }}
                                <img src="/themes/{{\Config::get('app.theme')}}/assets/img/man-user.png" width="16px" height="16px" style="margin-left: 10px; margin-right: 5px;" />{{ $licits }}
							@endif

                        </div>

                    @endif

                @else

                    <div class="price-content"></div>




                    @if( $item->retirado_asigl0 == 'S')

						<div class="price-content"></div>
                        <div class="btn-pujar">
                            <a class="btn btn-custom">
                                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                            </a>
                        </div>

					@elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')

						<div class="price-content"></div>
                        <div class="btn-pujar">
                            <a class="btn btn-custom">
                                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                            </a>
                        </div>
                    @else
                    <div class="price-content"></div>
                    @endif
                    <div class="timeLeft"></div>
                @endif
            </div>
        </div>
    </div>
</div>
