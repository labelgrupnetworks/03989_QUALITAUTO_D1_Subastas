export function TopFilters() {

	const handleToogleGridFilters = () => {
		const menu = document.querySelector('.lots-filters');
		const filtersMenu = document.querySelector('.form-filters');
		const orderMenu = document.querySelector('.order-auction-lot');

		filtersMenu.classList.remove('d-none');
		orderMenu.classList.add('d-none');
		menu.classList.add('open');
	};

	const handleToogleGridOrders = () => {
		const menu = document.querySelector('.lots-filters');
		const filtersMenu = document.querySelector('.form-filters');
		const orderMenu = document.querySelector('.order-auction-lot');

		filtersMenu.classList.add('d-none');
		orderMenu.classList.remove('d-none');
		menu.classList.add('open');
	}


	return (
		<>
			<button id="toogleFilters" className="btn" onClick={handleToogleGridFilters}>
				<svg width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
					<line x1="1.5" y1="2.28965e-08" x2="1.5" y2="22" stroke="#0F0E0D" />
					<line x1="12.5" y1="2.28965e-08" x2="12.5" y2="22" stroke="#0F0E0D" />
					<line x1="24.5" y1="2.28965e-08" x2="24.5" y2="22" stroke="#0F0E0D" />
					<rect y="15" width="3" height="3" fill="#0F0E0D" />
					<rect x="11" y="4" width="3" height="3" fill="#0F0E0D" />
					<rect x="23" y="15" width="3" height="3" fill="#0F0E0D" />
				</svg>
				<span>{trans('articles_js.search_filter')}</span>
			</button>

			<button id="toogleOrders" className="btn" onClick={handleToogleGridOrders}>
				<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<line x1="22" y1="0.5" x2="-4.47521e-08" y2="0.499998" stroke="#0F0E0D" />
					<line x1="22" y1="9.5" x2="-4.47521e-08" y2="9.5" stroke="#0F0E0D" />
					<line x1="22" y1="18.5" x2="-4.47521e-08" y2="18.5" stroke="#0F0E0D" />
				</svg>
				<span>{trans('articles_js.ordered_for')}</span>
			</button>
		</>
	)
}
