import { transform } from 'lodash';
import React, { useEffect, useState } from 'react'


const Order = props => {

	const orderChange = e => {
		const { name, value, dataset } = e.target;

		//quitamos el chequeo si han pulsado en el check que ya está seleccionado
		if (value == props.formFields.order && dataset.direction == props.formFields.order_dir) {

			props.setFormFields({
				...props.formFields,
				order: 'id_art0',
				order_dir: 'desc'
			});


		}else{
			props.setFormFields({
				...props.formFields,
				["order"]: value,
				["order_dir"]: dataset.direction
			});

		}


	};



	return (
		<div className="orderArticles">

			<div className="titleFilter" data-toggle="collapse" data-target="#order-collapse" aria-expanded="true" aria-controls="order-collapse" onClick={props.collapseEvent}>
				{trans('articles_js.order')}
				<i className="fa fa-caret-up" aria-hidden="true" style={{ float: 'right' }}></i>
			</div>

			<div id="order-collapse" className="collapse in">
				<div className="checkbox">
					<label>
						<input
							type="checkbox"
							checked={props.formFields.order === "pvp_art0" && props.formFields.order_dir == "asc"}
							value="pvp_art0"
							data-direction="asc"
							onChange={orderChange}
						/>

					{trans('articles_js.price_asc')}
					</label>
				</div>
				<div className="checkbox">
					<label>
						<input
							type="checkbox"
							checked={props.formFields.order === "pvp_art0" && props.formFields.order_dir == "desc"}
							value="pvp_art0"
							data-direction="desc"
							onChange={orderChange}
						/>
						{trans('articles_js.price_desc')}
					</label>
				</div>
			</div>
		</div>
	)
}

export default Order
