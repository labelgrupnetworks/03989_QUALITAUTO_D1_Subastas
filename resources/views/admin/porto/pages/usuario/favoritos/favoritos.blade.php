@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

        <!-- contenedor -->
        <div id="CSSchildfav" class="row mb-3">

        <h1>Favoritos</h1>
        <br>

        @foreach($favorites as $cod_sub =>$auction)

              <!-- subasta -->
              <div class="col-md-12">

                <!-- titulo -->
                <h2 class="JSauction" onclick="javascript:$('#JSul{{$cod_sub}}').slideToggle('blind')" style="cursor:pointer"> {{$cod_sub}} -{{head(head($auction))['des_sub']}}</h2>
                <!-- contenedor lotes -->

                <ul id="JSul{{$cod_sub}}" class="nav nav-main navbar-dark orange contenedorLotes">
                @foreach($auction as $ref =>$favorites)

                    <!-- lotes -->
                   <li class="nav-parent" >
                      <a class="linkLotes" onclick="javascript:$('#{{$cod_sub}}-{{$ref}}').slideToggle('blind')" style="cursor:pointer">
                          <strong>Lote: {{$ref}}-{{head($favorites)["titulo_hces1"]}}  ({{count($favorites)}})</strong>
                          <span style="color:#0077b3;"> + Info</span>
                       </a>
                        <!-- contenedor usuarios -->
                        <ul  class="nav nav-children contenedorUsuarios" id='{{$cod_sub}}-{{$ref}}'>
                        @foreach($favorites as $favorite)

                            <!-- usuarios -->
                            <li style="margin-left:40px" >
                                {{$favorite["cod_cliweb"]}} - {{$favorite["nom_cliweb"]}}
                            </li>
                        @endforeach
                        </ul>
                   </li>

                @endforeach
                </ul>
            </div>
        @endforeach
        </div>

@stop
