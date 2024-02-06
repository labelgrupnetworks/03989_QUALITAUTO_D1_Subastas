<div class="modal auction-modal" id="repre_auction_modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <img class="w-100" src="/themes/cmoriones/assets/img/popup_moriones.jpg"
                    alt="Información de Cristina Moriones">
            </div>
            <div class="modal-footer">
                <button class="btn btn-lb-primary" data-bs-dismiss="modal"
                    type="button">{{ trans("$theme-app.head.close") }}</button>
            </div>
        </div>
    </div>
</div>

<script>
	const showRepreModal = () => {
		const auctionModal = new bootstrap.Modal(document.getElementById('repre_auction_modal'));
		auctionModal.show();
	}

	executeOnceToDay('repre_auction_modal', showRepreModal);
</script>
