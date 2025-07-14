 <div class="btn-group" id="{{ $id }}">
     <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button" aria-haspopup="true"
         aria-expanded="false">
         {{ $label }} <span class="caret"></span>
     </button>

     <ul class="dropdown-menu dropdown-menu-right">
         {{ $slot }}
     </ul>
 </div>
