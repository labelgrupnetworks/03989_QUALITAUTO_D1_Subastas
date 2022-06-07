@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<script src="/vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
<script src="/vendor/tiempo-real/tr_main_test_connection.js"></script>
<script>
    url_node_test 	 = 'https://www.tauleryfau.com:29345';
</script>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 resultok">
            <h1 class="titlePage">TEST conection </h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="onlyHistoric col-xs-12 col-sm-4"> 
           
            <button type="button" id="test_conection" > Test Conection </button>
           
        </div>
        <div id="textos">
            
        </div>
    </div>
</div>
		


@stop
