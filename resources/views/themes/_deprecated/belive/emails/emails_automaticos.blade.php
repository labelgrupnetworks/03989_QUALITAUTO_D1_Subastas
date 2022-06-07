@extends('layouts.new_mail')

@section('content')


<div style="font-size: 16px;font-weight: 500;color: #666;text-align:center">
    <big><b>{{$emailOptions['content']->title}}</b></big>
    <br><br><br>
    {!! trans(\Config::get('app.theme').'-app.emails.hello')!!} {!! ucwords(mb_strtolower ($emailOptions['user']))!!},
    <br><br>
    <?= $emailOptions['content']->text ?>



                
        @if(!empty($emailOptions['lot']))
            
            @foreach($emailOptions['lot'] as $lot)

                @if(!empty($lot->img))
                <br><br>
                <center>
                    <img alt="" src="<?= $lot->img ?>" style="max-width:400px;">
                </center>
                @endif
                                                                  
                @if(!empty($lot->ref))
                    <?= trans(\Config::get('app.theme').'-app.emails.lot'); ?>: <?= $lot->ref ?>
                    <br>
                    <br>
                @endif
                                                                                                            
                @if(!empty($lot->desc))
                    <?= $lot->desc ?>
                    <br>
                @endif

            
                                                                                        
            @endforeach        
            
        @endif
                
                
</div>
            

@stop