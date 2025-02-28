<div id="clock" style="display: block" class="clock-page">
    <div style="margin-top: 50px">
        <img style="display: block;max-width: 300px; margin: 0 auto" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
    </div>
    <div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"  data-format="%D {{trans("web.msg_neutral.days")}} <br> %H:%M:%S  {{trans("web.msg_neutral.hours")}}" data-txtend ="{{trans("web.msg_neutral.auction_coming_soon")}}" class="tiempo wait-time text-center h2"></div>
</div>
