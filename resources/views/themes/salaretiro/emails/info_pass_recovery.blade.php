@extends('layouts.mail')

@section('content')

<p>{{ trans_choice($theme.'-app.emails.recover_advice', 1, ['app_name' => Config::get('app.name'), 'user_name' => $emailOptions['user']]) }}</p>

<p>{{trans($theme.'-app.emails.recover_sub_advice')}}<p>

{{trans($theme.'-app.emails.visit_link')}} <a href="<?= $emailOptions['url_params'] ?>">{{ trans($theme.'-app.emails.text_link') }}</a>						</p>
@stop