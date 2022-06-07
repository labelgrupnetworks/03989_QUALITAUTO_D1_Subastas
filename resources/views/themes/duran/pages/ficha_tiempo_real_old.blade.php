@if(!empty($data['js_item']['user']['is_gestor']))
    @include('pages.tiempo_real.ficha_tiempo_real_admin')
@else
    @include('pages.tiempo_real.ficha_tiempo_real_user')
@endif