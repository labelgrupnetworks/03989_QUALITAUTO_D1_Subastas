@extends('layouts.pago')


@section('content')

@if($data['status'] == 'error')
    <h1>ERROR</h1>
@elseif($data['status'] == 'success')
    <?= $data['msg'] ?>
@endif

@stop

