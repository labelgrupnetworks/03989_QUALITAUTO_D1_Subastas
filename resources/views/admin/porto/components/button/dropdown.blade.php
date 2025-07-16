
{{-- file components/button/dropdown.blade.php --}}
@props(['id' => null, 'label' => ''])

 <div class="btn-group" {{ $attributes->merge(['id' => $id]) }}>
     <button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" type="button" aria-haspopup="true"
         aria-expanded="false">
         {{ $label }} <span class="caret"></span>
     </button>

     <ul class="dropdown-menu dropdown-menu-right">
         {{ $slot }}
     </ul>
 </div>
