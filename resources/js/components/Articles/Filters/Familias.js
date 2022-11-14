import React from 'react'
const Familias = props => {




	return (
		<div>
			{props.familiasList.length > 0 &&
				<div>
					<div className="titleFilter" data-toggle="collapse" data-target="#familias-collapse" aria-expanded="true" aria-controls="familias-collapse" onClick={props.collapseEvent}>
						{trans('articles_js.collections')}
						<i className="fa fa-caret-up" aria-hidden="true" style={{float:'right'}}></i>
					</div>

					<div id="familias-collapse" className="collapse in">
						{
							props.familiasList.map( (familia, idx) =>
								<div key={idx} className="checkbox">
									<label>
										<input
											type="checkbox"
											checked={props.familia == familia.cod_famart}
											value={familia.cod_famart}
											onChange={(event) => props.familiaClick(event.target.value)}
										/>

										{familia.des_famart} <span className="filter-counter">{`(${familia.cuantos})`}</span>
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
export default Familias
