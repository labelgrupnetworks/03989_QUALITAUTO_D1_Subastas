<div id="clock" style="display: block" class="clock-page">
    <div style="margin-top: 50px">
        <img style="display: block;max-width: 300px; margin: 0 auto" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
    </div>
	<div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"  data-format="%D {{trans($theme."-app.msg_neutral.days")}} <br> %H:%M:%S  {{trans($theme."-app.msg_neutral.hours")}}" data-txtend ="{{trans($theme."-app.msg_neutral.auction_coming_soon")}}" class="tiempo wait-time text-center"></div>
	<p class="clock-text">{!! trans($theme."-app.sheet_tr.text_clock") !!}</p>
</div>
