<style>
#acceptedTermsModal .modal-content {
    background: white;
    color: #707070;
}

#acceptedTermsModal .modal-body {
    font-size: 14px;
}

#acceptedTermsModal label {
	display: flex;
}

#acceptedTermsModal input[type=checkbox] {
	position: relative;
    top: -5px;
    display: inline-block;
	min-width: 20px;
    width: 20px;
    height: 20px;
    margin-right: 10px;
}

.btn-custom-primary {
	background-color: var(--btn-primary-background, #414145);
	color: var(--btn-primary-text, #fff);
	padding: 6px 25px;
}

.btn-custom-primary:hover, .btn-custom-primary:focus {
	background-color: var(--btn-primary-background-hover, #00664F);
	color: var(--btn-primary-text-hover, #fff);
}

</style>

@if(Session::has('user'))
@php
$newsletters = (new App\Models\User())->getClientNllist(session('user.cod'));
@endphp

@if($newsletters->nllist20_cliweb == 'N')
<div class="modal fade" id="acceptedTermsModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-tr">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{{ trans("$theme-app.login_register.accept_new_terms") }}</h4>
			</div>
			<div class="modal-body">
				<p>{!! trans("$theme-app.login_register.must_accept_new_terms") !!}</p>
				<form id="acceptedTermsForm">
					<input name="condiciones" type="hidden" value="1">
					<input name="email" type="hidden" value="{{session('user.usrw')}}">

					<label for="acceptedTerms" class="d-flex alig-items-center">
						<input type="checkbox" id="acceptedTerms" value="20" name="families[]">
						{{ trans("$theme-app.login_register.read_conditions") }}
					</label>

					@if($newsletters->nllist1_cliweb == 'N')
					<label for="newsletter" class="d-flex alig-items-center">
						<input type="checkbox" name="families[]" value="1">
						{{ trans("$theme-app.login_register.recibir_newsletter_modal") }}
					</label>
					@else
					<input type="hidden" name="families[]" value="1">
					@endif

				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-custom-primary" id="acceptNewTerms">{{ trans("$theme-app.home.confirm") }}</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("$theme-app.login_register.cancel") }}</button>
			</div>
		</div>
	</div>
</div>
@endif
@endif

<script>
	document.getElementById('acceptNewTerms')?.addEventListener('click', acceptNewTerms);

	function acceptNewTerms(event) {
		$.ajax({
			type: "POST",
			url: '/api-ajax/newsletter/add',
			data: $('#acceptedTermsForm').serialize(),
			success: (response) => {
				if (response.status == 'success') {
					location.reload();
					return;
				}

				$('.insert_msg').html(messages.error.generic);
				$.magnificPopup.open({ items: { src: '#newsletterModal' }, type: 'inline' }, 0);
			}
		});
	}
</script>
