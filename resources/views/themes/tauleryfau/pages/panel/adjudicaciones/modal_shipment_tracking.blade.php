<style>
	.timeline {
    border-left: 3px solid var(--btn-gold);
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: #f9f4ef;
    /*margin: 0 auto;*/
	margin: 0 0 0 auto;
    letter-spacing: 0.2px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 50px;
    list-style: none;
    text-align: left;
    /*max-width: 40%;*/
	max-width: 60%;
}

@media (max-width: 767px) {
    .timeline {
        max-width: 98%;
        padding: 25px;
    }
}

.timeline h1 {
    font-weight: 300;
    font-size: 1.4em;
}

.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
}

.timeline .event {
    border-bottom: 2px dashed var(--btn-gold);
    padding-bottom: 25px;
    margin-bottom: 25px;
    position: relative;
}

@media (max-width: 767px) {
    .timeline .event {
        padding-top: 30px;
    }
}

.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}

.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}

.timeline .event:before {
    left: -207px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 0.9em;
    min-width: 120px;
}

@media (max-width: 767px) {
    .timeline .event:before {
        left: 0px;
        text-align: left;
    }
}

.timeline .event:after {
    -webkit-box-shadow: 0 0 0 3px var(--btn-gold);
    box-shadow: 0 0 0 3px (--btn-gold);
    left: -55.8px;
    background: #fff;
    border-radius: 50%;
    height: 9px;
    width: 9px;
    content: "";
    top: 5px;
}

@media (max-width: 767px) {
    .timeline .event:after {
        left: -31.8px;
    }
}

.rtl .timeline {
    border-left: 0;
    text-align: right;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    border-right: 3px solid (--btn-gold);
}

.rtl .timeline .event::before {
    left: 0;
    right: -170px;
}

.rtl .timeline .event::after {
    left: 0;
    right: -55.8px;
}
</style>



<!-- Modal -->
<div id="modal_shipment" class="modal fade" role="dialog">
	<div class="modal-dialog">

	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">{{ trans(\Config::get('app.theme').'-app.user_panel.shipment_tracking') }}</a></h4>
		</div>

		<div class="modal-body">
			<div class="row w-100">
				<div class="col-md-12">
					<ul class="timeline">
					</ul>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	  </div>

	</div>
</div>

<script>

$('.js-btn-shipment').on('click', function(e){

	let ul = $('.timeline');
	ul.empty();
	getShipment(e.target);
});

function getShipment(button){
	let data = {
		'_token': '{{ csrf_token() }}',
		'cod_sub': button.getAttribute('cod_sub'),
		'afral_csub': button.dataset.afral_csub,
		'nfral_csub': button.dataset.nfral_csub
	}

	$.ajax({
		type: "POST",
		url: "{{ route('panel.shipment', ['lang' => Config::get('app.locale')]) }}",
		data: data,
		success: function(response){
			addShipment(response);
		}
	});
}

function addShipment(shipments){

	let ul = $('.timeline');
	shipments.forEach(shipment => {

		let h3 = $('<h3>').append(shipment.des_estadosseg);
		let p = $('<p>').append(shipment.long_description);
		let li = $('<li>', {'class': "event", 'data-date': shipment.fecha_dvc0seg});
		li.append(h3, p);
		ul.append(li);

	});
}

</script>
