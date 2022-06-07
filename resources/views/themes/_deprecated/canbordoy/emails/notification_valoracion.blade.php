@extends('layouts.mail')

@section('content')
        <p><?= $emailOptions['content']['texto'] ?></p>
        <p><strong>{{ trans(\Config::get('app.theme').'-app.login_register.ph_user') }}:</strong> {{$emailOptions['content']['name']}}</p>
        <p><strong>{{ trans(\Config::get('app.theme').'-app.login_register.email') }}:</strong> {{$emailOptions['content']['email']}}</p>
        <p><strong>{{ trans(\Config::get('app.theme').'-app.login_register.phone') }}:</strong> {{$emailOptions['content']['telf']}}</p>
        <p>
                <?php echo $emailOptions['content']['camposHtml']; ?>
        </p>

    <?php $false=true;

    for ($i = 1; $false==true; $i++) {
        if(!empty($emailOptions['img']['imagen'.$i])){?>
        <tr>
            <td>
                
                <?php $message->attach($emailOptions['img']['imagen'.$i]); ?>
            </td>
        </tr>    
       <?php  }else{
            $false = false;
        }
    } ?>
@stop
