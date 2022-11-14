import React from 'react'
const Marcas = props => {

	return (
		<div>
			{props.marcasList.length > 0 &&
				<div>
					<div className="titleFilter" data-toggle="collapse" data-target="#marcas-collapse" aria-expanded="true" aria-controls="marcas-collapse" onClick={props.collapseEvent}>
						{trans('articles_js.brands')}
						<i className="fa fa-caret-up" aria-hidden="true" style={{float:'right'}}></i>
					</div>

					<div id="marcas-collapse" className="collapse in">
						{
							props.marcasList.map( (marca, idx) =>
								<div key={idx} className="checkbox">
									<label>
										<input
											type="checkbox"
											checked={props.marca == marca.marca_marca}
											value={marca.marca_marca}
											onChange={(event) => props.marcaClick(event.target.value)}
										/>
										{marca.des_marca} <span className="filter-counter">{`(${marca.cuantos})`}</span>
									</label>
								</div>
							)
						}
					</div>
				</div>
			}
		</div>
	)
}
export default Marcas
