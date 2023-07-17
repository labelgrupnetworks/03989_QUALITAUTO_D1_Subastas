<div class="btn-group btn-grop-calendar">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        {{ trans("$theme-app.calendar.add_calendar") }} <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a target="_blank" href="{{ $links['google'] }}">
                <img src="/default/img/icons/google_calendar.svg" width="14" height="14"
                    alt="add to google calendar"><span>Google</span></a>
        </li>
        <li>
            <a target="_blank" href="{{ $links['outlook'] }}">
                <img src="/default/img/icons/outlook_calendar.svg" width="14" height="14"
                    alt="add to outlook"><span>Outlook</span>
            </a>
        </li>
        <li>
            <a target="_blank" href="{{ $links['yahoo'] }}">
                <img src="/default/img/icons/yahoo-icon.svg" width="14" height="14"
                    alt="add to yahoo calendar"><span>Yahoo</span>
            </a>
        </li>
        <li>
            <a href="{{ $links['icalendar'] }}" download="{{ $fileName }} calendar.ics">
                <img src="/default/img/icons/apple_icon.svg" width="14" height="14"
                    alt="add to yahoo calendar"><span>Ical</span>
            </a>
        </li>
    </ul>
</div>
