<footer class="menu-footer">
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-lg-7">
			</div>
			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>

				</div>
			</div>

		</div>
	</div>
</footer>

<script>
	@if(request("recoveryPassword"))

	$(document).ready(function () {

		var loader = $('.loader.copyme').clone().removeClass('hidden');

		$('#modalAjax .modal-content .modal-title').html("Actualizar contraseña");




		$.ajax({
			url: "/es/password_recovery",
			dataType: "text",

			beforeSend: function (xhr) {
				$('#modalAjax .modal-content .modal-body').html(loader);
			},

			success: function (data) {

				try {

					info = $.parseJSON(data);

					if (info.status == 'error') {
						window.location.href = '/';
					}

					//$('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>"+info.msg+"</div>");

				} catch (e) {
					// not json
					$('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>" + data + "</div>");
				}

				$('#modalAjax .modal-content .loader.copyme').remove();
				@if(request("emailRecovery"))
					$("#password_recovery [name=email]").val("{{request("emailRecovery")}}");
				@endif

				$('#modalAjax').modal("show");
			}
		});
	});
	@endif

	</script>
