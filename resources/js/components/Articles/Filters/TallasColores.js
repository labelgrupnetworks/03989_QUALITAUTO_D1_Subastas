import React, { useEffect, useState } from 'react'
const TallasColores = props => {

	return (
		<div>
			{
				/*  al ser un array indexado json lo trata como un objeto y debemos hacer el object.values para convertirlo en array  */
				Object.values(props.tallasColoresList).length > 0 &&
				Object.values(props.tallasColoresList).map((tallaColor, idx) =>
					<div key={idx} >

						<div className="titleFilter" data-toggle="collapse" data-target={`#tallascolores_${idx}-collapse`} aria-expanded="true" aria-controls="familias-collapse" onClick={props.collapseEvent}>
							{tallaColor[0].name_variante}
							<i className="fa fa-caret-up" aria-hidden="true" style={{float:'right'}}></i>
						</div>

						<div id={`tallascolores_${idx}-collapse`} className="collapse in">
							{
								tallaColor.length > 0 && tallaColor.map((i, idx) =>
								<div key={idx} className="checkbox">
									<label>
										<input
											type="checkbox"
											checked={props.tallaColor[tallaColor[0].id_variante] == i.id_valvariantes}
											value={i.id_valvariantes}
											onChange={(event) => props.tallaColorClick(tallaColor[0].id_variante, event.target.value)}
										/>

										{i.valor_valvariante} <span className="filter-counter">{`(${i.cuantos})`}</span>
									</label>
								</div>

								)
							}
						</div>
					</div>
				)
			}
		</div>
	)
}
export default TallasColores
