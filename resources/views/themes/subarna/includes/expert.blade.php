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
	<p class="expert-card_contact">
		<a href="mailto:{{ $specialist->email_especial1 }}">{{ $specialist->email_especial1 }}</a>
		<br>
		<a href="tel:{{ $specialist->phone_especial1 }}">{{ $specialist->phone_especial1 }}</a>
	</p>
</div>
