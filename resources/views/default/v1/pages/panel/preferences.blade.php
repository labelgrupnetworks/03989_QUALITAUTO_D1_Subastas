@extends('layouts.default')

@section('title')
	{{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
	<div class="color-letter">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 text-center">
					<h1 class="titlePage">{{ trans("$theme-app.user_panel.preferences_mayus") }}</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="container preferences-container">
		<div class="row">
			<div class="col-xs-12 col-sm-3">
				@php $tab="form-preferencias"; @endphp
				@include('pages.panel.menu_micuenta')
			</div>
			<div class="col-xs-12 col-sm-9">
				<div class="text-right">
					<button class="btn btn-warning" id="preference_form_btn">{{ trans("$theme-app.user_panel.create_preference") }}</button>
				</div>
				<div id="form-container" data-open="false" style="display: {{ $ifQueryPreferences ? 'none' : 'block' }};">
					<div class="mt-2 mb-4">
						<form action="{{ \Routing::slug('user/panel/preferences/create') }}" method="POST" id="frm_preference">
							@csrf
							<input type="hidden" id="user_code" value="{{ session('user')['cod'] }}">
							<div class="row">
								<div id="error-msg-preference-name" class="col-xs-12 hidden">
									<p class="text-center error-msg">{{ trans("$theme-app.user_panel.non_input_desc") }}</p>
								</div>
								<div id="error-msg-all-empty" class="col-xs-12 hidden">
									<p class="text-center error-msg">{{ trans("$theme-app.user_panel.input_all_empty") }}</p>
								</div>
								<div id="error-msg-keyword-space" class="col-xs-12 hidden">
									<p class="text-center error-msg">{{ trans("$theme-app.user_panel.only_word_text") }}</p>
								</div>
								<div id="error-msg-keyword-numeric" class="col-xs-12 hidden">
									<p class="text-center error-msg">{{ trans("$theme-app.user_panel.keywords_non_alphanumeric") }}</p>
								</div>
								<div class="col-xs-12 mt-2 mb-2">
									<label for="preference_name">{{ trans("$theme-app.user_panel.desc_input_pref") }}</label>
									<input class="form-control" type="text" name="preference_name" id="preference_name">
								</div>
								<div class="col-xs-12 col-sm-6">
									<div class="form-group pl-5 pr-5">
										<label for="family_selector">{{ trans("$theme-app.user_panel.family_selector") }}</label>
										<select class="form-select w-100" name="family-selector" id="family_selector">
											<option value="-">{{ trans("$theme-app.user_panel.family_selector") }}</option>
											@foreach ($queryFamily as $family)
												<option value="{{ $family->sub_ortsec0 }}-{{ $family->lin_ortsec0 }}">{{ $family->des_ortsec0 }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6">
									<div class="form-group pl-5 pr-5">
										<label for="subfamily_selector">{{ trans("$theme-app.user_panel.subfamily_selector") }}</label>
										<select class="form-select w-100" name="subfamily-selector" id="subfamily_selector">
										</select>
									</div>
								</div>
							</div>
							<div class="row mt-2 mb-2">
								<div class="col-xs-12 col-sm-4">
									<label for="keyword1">{{ trans("$theme-app.user_panel.keyword_input_one") }}</label>
									<input class="form-control" name="keyword1" type="text" id="keyword1" aria-describedby="keyword1Help">
								</div>
								<div class="col-xs-12 col-sm-4">
									<label for="keyword2">{{ trans("$theme-app.user_panel.keyword_input_two") }}</label>
									<input class="form-control" name="keyword2" type="text" id="keyword2" aria-describedby="keyword2Help">
								</div>
								<div class="col-xs-12 col-sm-4">
									<label for="keyword3">{{ trans("$theme-app.user_panel.keyword_input_three") }}</label>
									<input class="form-control" name="keyword3" type="text" id="keyword3" aria-describedby="keyword3Help">
								</div>
								<div class="col-xs-12 text-center">
									<button class="btn btn-primary mt-3 mb-1" id="preferences_submit"
										for="frm_preference" type="button">{{ trans("$theme-app.user_panel.create_preference") }}</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="{{ $ifQueryPreferences ? '' : 'd-none' }}">
					<div class="preferences-table-container">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>{{ trans("$theme-app.user_panel.name") }}</th>
									<th>{{ trans("$theme-app.user_panel.family") }}</th>
									<th>{{ trans("$theme-app.user_panel.subfamily") }}</th>
									<th>{{ trans("$theme-app.user_panel.keywords") }}</th>
									<th>{{ trans("$theme-app.user_panel.actions") }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($queryPreferences as $preference)
									<tr>
										<td>{{ $preference->desc_pref }}</td>
										<td>{{ $preference->des_ortsec0 }}</td>
										<td>{{ $preference->des_sec }}</td>
										<td>{{ $preference->keyword1_pref }} {{ $preference->keyword2_pref }} {{ $preference->keyword3_pref }}</td>
										<td>
											<form action="{{ \Routing::slug('user/panel/preferences/delete') }}" method="POST">
												@csrf
												<input type="hidden" name="preference_code" value="{{ $preference->id_pref }}">
												<button class="btn btn-danger" type="submit">{{ trans("$theme-app.user_panel.delete") }}</button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>




	<script>
		$('#family_selector').on('change', function() {
			var family_selector = $(this).val();
			var family_selector_split = family_selector.split('-');
			var auc_family = family_selector_split[0];
			var cod_family = family_selector_split[1];

			$.ajax({
				url: '{{ route('panel.preferences_subfamily') }}',
				type: 'POST',
				data: $('#frm_preference').serialize(),
				dataType: 'json',
				success: function(data) {
					if ($('#subfamily_selector').children().length > 0) {
						$('#subfamily_selector').children().remove();
					}
					$('#subfamily_selector').append('<option value="">{{ trans("$theme-app.user_panel.subfamily_selector") }}</option>');
					data.querySubfamily.forEach(element => {
						$('#subfamily_selector').append('<option value="' + element.cod_sec +
							'">' + element.des_sec + '</option>');
					});
				},
			});
		});

		$('#preference_form_btn').on('click', function() {
			if ($('#form-container').data('open')) {
				$('#form-container').slideUp(500, function() {
					$('#form-container').data('open', false);
				});
			} else {
				$('#form-container').slideDown(500, function() {
					$('#form-container').data('open', true);
				});
			}
		});

		$('#preferences_submit').on('click', function(e) {
			e.preventDefault();
			$('#error-msg-preference-name').addClass('hidden');
			$('#error-msg-all-empty').addClass('hidden');
			$('#error-msg-keyword-space').addClass('hidden');
			$('#error-msg-keyword-numeric').addClass('hidden');


			if ($('#family_selector').val() == '-' && !$('#subfamily_selector').val() && !$('#keyword1').val() && !$('#keyword2').val() && !$('#keyword3').val()) {
				$('#error-msg-all-empty').removeClass('hidden');
			}
			if (!$('#preference_name').val()) {
				$('#error-msg-preference-name').removeClass('hidden');
			}
			if ($('#keyword1').val()) {
				var keyword1 = $('#keyword1').val();
				var keyword1_split = keyword1.split(' ');
				if (keyword1_split.length > 1) {
					$('#error-msg-keyword-space').removeClass('hidden');
				} else {
					if ((keyword1.match(/^[a-zA-Z]+$/) === null)) {
						$('#error-msg-keyword-numeric').removeClass('hidden');
					}
				}
			}
			if ($('#keyword2').val()) {
				var keyword2 = $('#keyword2').val();
				var keyword2_split = keyword2.split(' ');
				if (keyword2_split.length > 1) {
					$('#error-msg-keyword-space').removeClass('hidden');
				} else {
					if ((keyword2.match(/^[a-zA-Z]+$/) === null)) {
						$('#error-msg-keyword-numeric').removeClass('hidden');
					}
				}
			}
			if ($('#keyword3').val()) {
				var keyword3 = $('#keyword3').val();
				var keyword3_split = keyword3.split(' ');
				if (keyword3_split.length > 1) {
					$('#error-msg-keyword-space').removeClass('hidden');
				} else {
					if ((keyword3.match(/^[a-zA-Z]+$/) === null)) {
						$('#error-msg-keyword-numeric').removeClass('hidden');
					}
				}
			}

			if ($('#error-msg-preference-name').hasClass('hidden') && $('#error-msg-all-empty').hasClass('hidden') && $('#error-msg-keyword-space').hasClass('hidden') && $('#error-msg-keyword-numeric').hasClass('hidden')) {
				$('#frm_preference').submit();
			}

		});

	</script>






@stop
