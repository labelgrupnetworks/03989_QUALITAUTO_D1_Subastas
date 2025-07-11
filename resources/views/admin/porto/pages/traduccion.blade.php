@extends('admin::layouts.logged')
@section('content')
<?php $idiomes = \Config::get('app.locales');?>

<section role="main" class="content-body">
        <div class="row mb-3">
        @foreach($data as $trans)
            <div class="col-md-3">
                <section class="panel">
                    @if(is_object($trans))
                   <div class="panel-body">
                       <div class="col-md-12 text-center">
                           <h4>{{ $trans->key_header }}</h4>
                       </div>
                       @foreach($idiomes as $key => $lang)
                        <div class="col-md-6">
                            <a class="mb-1 mt-1 mr-1 btn btn-primary" href="/admin/traducciones/{{$trans->key_header}}/{{strtoupper($key)}}">{{$lang}}</a>
                        </div>
                       @endforeach
                   </div>
                    @else
                    <div class="panel-body">
                       <div class="col-md-12 text-center">
                           <h4>{{ $trans['key_header']}}</h4>
                       </div>
                       @foreach($idiomes as $key => $lang)
                        <div class="col-md-6">
                            <a class="mb-1 mt-1 mr-1 btn btn-primary" href="/admin/traducciones/{{$trans['key_header']}}/{{strtoupper($key)}}">{{$lang}}</a>
                        </div>
                       @endforeach
                   </div>
                    @endif
               </section>
            </div>
        @endforeach
        </div>
</section>

@stop
