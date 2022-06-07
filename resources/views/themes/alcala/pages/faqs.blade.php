@extends('layouts.default')

@section('title')
	{{ $data['name'] }}
@stop

@section('content')
<?php 
$bread[] = array("name" => trans(\Config::get('app.theme').'-app.foot.faq') );
?>
@include('includes.breadcrumb')



<div  class="contenido">
    <div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titleSingle_corp">{{trans(\Config::get('app.theme').'-app.foot.faq') }}</h1>
		</div>
	</div>  

        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <div class="faqs-web">
                    <ul class="list_prin">
                        @foreach($data['faqs'] as $key => $value)
                        <li class="primary-item">
                            <a role="button" id_class="balcis" title="<?= $key ?>">
                                <div class="text"><?= nl2br($key) ?></div>
                            </a>
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
            <?php 
                $x = 0;
                $y = 0;
            ?>
            <div class="col-xs-12 col-sm-9">
                <div class="secondary-list">
                    <div class="secondary-item">   
                        <ul class="list-content">
                            @foreach($data['faqs'] as $key => $value)
                            <ul class="lists" data-index="{{$x}}">
                                @foreach($value as $key_faq => $value_faq)
                                <ul class="" > 
                                    <div class="secondary-item-title"><?= $key_faq ?></div>

                                        @foreach($value_faq as $key_questions => $value_questions)  
                                            <li>
                                                <div class="secondary-item-sub" data-open="0"><span><?= $key_questions ?></span></div>                   
                                                <div class="secondary-item-dec"><?= nl2br($value_questions) ?></div>
                                            </li>
                                        @endforeach
                                </ul>

                                @endforeach
                                <?php $x ++; ?>
                            </ul>
                            @endforeach

                        </ul>

                    </div>
                </div>
            </div>
        </div>
	</div>            
</div>    


@stop

