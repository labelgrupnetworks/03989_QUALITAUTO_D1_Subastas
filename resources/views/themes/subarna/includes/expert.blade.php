<div class="expert-card">
    <img class="expert-card_image" src="/themes/subarna/assets/img/placeholder_round.svg" alt="">
    <div class="expert-card_name_block">
        <h2 class="expert-card_name">{{ $specialist->nom_especial1 }} </h2>
        <p class="expert-card_specilty">{{ $specialist->specialty->title }}</p>
    </div>
    <div class="expert-card_desc">
        <p>
            {{ $specialist->description }}
        </p>
    </div>

    @if ($specialist->relationLoaded('ortsec'))
        <p class="expert-card_link">
            <a href="{{ $specialist->ortsec->department_route_page }}">
                {{ trans("$theme-app.about_us.know_department") }}
            </a>
        </p>
    @else
        <p class="expert-card_contact">
            <a href="mailto:{{ $specialist->email_especial1 }}">{{ $specialist->email_especial1 }}</a>
            <br>
            <a href="tel:{{ $specialist->phone_especial1 }}">{{ $specialist->phone_especial1 }}</a>
        </p>
    @endif
</div>
