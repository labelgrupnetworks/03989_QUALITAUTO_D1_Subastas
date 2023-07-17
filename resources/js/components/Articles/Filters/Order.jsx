import { useForm } from "../../../hooks/useForm";

const defaultOrder = {
	order: 'id_art0',
	order_dir: 'desc'
}

export function Order() {

	const { formFields, setFormFields } = useForm();
	const { order, order_dir } = formFields;

	const orderChange = e => {
		const { value, dataset } = e.target;

		const isSameSelected = value == order && dataset.direction == order_dir;

		const newOrder = isSameSelected ? defaultOrder : { order: value, order_dir: dataset.direction };

		setFormFields((prevState) => ({
			...prevState,
			...newOrder
		}));

	};

	return (
		<div className="orderArticles">

			<fieldset>
				<legend className="titleFilter">
					{trans('articles_js.order')}
				</legend>

				<div className="form-check">
					<input
						id="pvp_art0_asc"
						className="form-check-input"
						type="checkbox"
						checked={order === "pvp_art0" && order_dir == "asc"}
						value="pvp_art0"
						data-direction="asc"
						onChange={orderChange}
					/>
					<label className="form-check-label" htmlFor="pvp_art0_asc">
						{trans('articles_js.price_asc')}
					</label>
				</div>

				<div className="form-check">
					<input
						id="pvp_art0_desc"
						className="form-check-input"
						type="checkbox"
						checked={order === "pvp_art0" && order_dir == "desc"}
						value="pvp_art0"
						data-direction="desc"
						onChange={orderChange}
					/>
					<label className="form-check-label" htmlFor="pvp_art0_desc">
						{trans('articles_js.price_desc')}
					</label>
				</div>
			</fieldset>
		</div>
	)
}
