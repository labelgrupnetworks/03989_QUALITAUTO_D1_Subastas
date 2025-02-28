<h1>{{  mb_convert_case(trans("web.user_panel.wallet"), MB_CASE_TITLE, "UTF-8") }}</h1>

<form method="post" name="save-wallet" id="save-wallet">
	@csrf

	<div class="row gy-1">
		<div class="col-12">
			<label class="form-label" for="wallet">{{ trans("web.user_panel.wallet_direction") }}</label>
			<input type="text" class="form-control" id="wallet" name="wallet_dir" value="{{ $data['user']->wallet_cli ?? '' }}" required>
		</div>
		<div class="col-12">
			<button class="btn btn-lb-primary mb-1" type="submit" for="save-wallet">{{ trans("web.user_panel.save") }}</button>
			<button class="btn btn-lb-secondary mb-1" id="create-wallet">{{ trans("web.user_panel.wallet_new") }}</button>
		</div>
		<div id="wallet-call-result"></div>
	</div>
</form>
