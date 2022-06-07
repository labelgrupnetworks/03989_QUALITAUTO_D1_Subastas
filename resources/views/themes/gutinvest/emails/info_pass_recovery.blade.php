@extends('layouts.mail')

@section('content')
asdasd
<p>{{ trans_choice(\Config::get('app.theme').'-app.emails.recover_advice', 1, ['app_name' => Config::get('app.name'), 'user_name' => $emailOptions['user']]) }}</p>

<p>{{trans(\Config::get('app.theme').'-app.emails.recover_sub_advice')}}<p>

{{trans(\Config::get('app.theme').'-app.emails.visit_link')}} <a href="<?= $emailOptions['url_params'] ?>">{{ trans(\Config::get('app.theme').'-app.emails.text_link') }}</a>						</p>
@stop