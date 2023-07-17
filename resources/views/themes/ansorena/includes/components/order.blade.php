<div class="position-relative">
    <div class="select-container order-select">

        <div class="order-select-icon">
            <svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <line x1="22" y1="0.5" x2="-4.47521e-08" y2="0.499998" stroke="#0F0E0D" />
                <line x1="22" y1="9.5" x2="-4.47521e-08" y2="9.5" stroke="#0F0E0D" />
                <line x1="22" y1="18.5" x2="-4.47521e-08" y2="18.5" stroke="#0F0E0D" />
            </svg>
            <span class="text-uppercase">{{ trans("$theme-app.global.sort") }}</span>
        </div>
        <div class="order-select-container" id="order-select-container">
            <select name="order_dir" id="order-select">
                <option value="asc">
                    A-Z
                </option>
                <option value="desc" @if (request('order_dir') == 'desc') selected @endif>
                    Z-A
                </option>
            </select>
        </div>
    </div>
</div>

<script>
    $('#order-select').select2({
        minimumResultsForSearch: Infinity,
        width: 'resolve',
        dropdownParent: $('#order-select-container')
    })

	$('.order-select-icon').on('click', function(event) {
		$('#order-select').select2('open')
	})
</script>
