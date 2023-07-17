import { Filters } from './Filters/Filters.jsx'
import { Order } from './Filters/Order.jsx';
import { ArticlesList } from './Article/ArticlesList.jsx';
import { TopFilters } from './Filters/TopFilters.jsx';

export default function Grid() {

	const handleCloseGridMenu = () => {
		const menu = document.querySelector('.lots-filters');
		menu.classList.remove('open');
	}

	return (
		<section className='grid-articles-lots position-relative'>
			<aside className='lots-filters'>
				<div className="lots-filters-content">
					<button type="button" className="btn-close" aria-label="Close" onClick={handleCloseGridMenu}></button>
					<div className='filters-auction-content' style={{ position: 'relative' }}>
						<div className="form-filters">
							<Filters />
						</div>
						<div className="order-auction-lot">
							<Order />
						</div>
					</div>
				</div>
			</aside>

			<div className='container'>
				<div className="top-filters-wrapper">
					<TopFilters />
				</div>

				<ArticlesList />
			</div>

		</section>
	)
}

