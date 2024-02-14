 @foreach ($lots as $item)
     @php
         #transformo el array en variables para conservar los nombres antiguos
         # si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

         foreach ($item->bladeVars as $key => $value) {
             ${$key} = $value;
         }

         $titulo = trans($theme . '-app.lot.lot-name') . ' ' . $item->ref_asigl0;

         $class_square = '';

         $codeScrollBack = '';

     @endphp

     @include('includes.grid.lot')
 @endforeach
