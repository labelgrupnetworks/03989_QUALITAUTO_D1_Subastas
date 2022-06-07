@extends('layouts.mail')

@section('content')        
         <?= trans(\Config::get('app.theme').'-app.emails.hello') ?> <?= $emailOptions['user'] ?><br><br>

        <p><?= trans(\Config::get('app.theme').'-app.emails.saved_success_orden') ?></p>      
        <table>
            <tr>
                <td width="120px">
                      <img src="<?= $emailOptions['foto'] ?>" width="100"> 
                </td>
                <td>
                    <strong><?= trans(\Config::get('app.theme').'-app.emails.auction') ?>: </strong><?= $emailOptions['subasta'] ?><br>
                    <strong><?= trans(\Config::get('app.theme').'-app.emails.lot') ?>: </strong> <?= $emailOptions['lote'] ?><br>
                    <strong><?= trans(\Config::get('app.theme').'-app.emails.description') ?>: </strong> <?= $emailOptions['descripcion'] ?><br>
                    <strong><?= trans(\Config::get('app.theme').'-app.emails.max_puja') ?>: </strong> <?= \Tools::moneyFormat($emailOptions['puja']) ?> €<br>
                </td>
            </tr>
        </table>
       
      <br>
        <p><?= trans(\Config::get('app.theme').'-app.emails.text_puja_escrito') ?></p>
@stop		