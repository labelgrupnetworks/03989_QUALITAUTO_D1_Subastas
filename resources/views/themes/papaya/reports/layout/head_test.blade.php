<meta charset="utf-8" http-equiv="content-type">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="initial-scale=1,minimum-scale=0.15, maximum-scale=2, user-scalable=yes">
<meta name="author" content="{{ trans(\Config::get('app.theme').'-app.head.meta_author') }}">
<title>
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

<link href="{{ URL::asset('/themes/'.$theme.'/style.css') }}?a={{rand()}}" rel="stylesheet" type="text/css">
