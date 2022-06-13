@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-12 resultok titlePage">
                <h1 {{-- class="titlePage" --}}>{{ trans(\Config::get('app.theme').'-app.home.specialist-contact') }}</h1>
				<p class="mini-underline"></p>
        </div>
        <div class="col-lg-12">

            @foreach($data['especialista'] as $key_esp => $especialista)
                @if(!empty($especialista[0]->titulo_especial0))
                    <h2>{{$especialista[0]->titulo_especial0}}</h2>
                @endif
                <div class="row mb-50">
                    @foreach($especialista as $esp)
                    <?php
                    $name_archive = '/img/PER/'.Config::get('app.gemp').$esp->per_especial1.'.jpg';

                    if (file_exists($name_archive)) {
                       $name_archive = '/themes/'.\Config::get('app.theme').'/img/items/no_photo.png';
                    }
                    ?>

                        <div class="col-lg-4">
                            <div class="row">
                            <div class="col-lg-5">
                                 <img class="img-responsive" src="<?= $name_archive?>" width="128px">
                            </div>
                            <div class="col-lg-7 margin_30">
                                <p> <?= ucwords($esp->nom_especial1) ?></p>
                                 <a href="mailto:{{strtolower($esp->email_especial1)}}">{{strtolower($esp->email_especial1)}}</a>

                            </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop
