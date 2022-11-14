import React, { useEffect,useState } from 'react'
const TallasColores = props => {













		return (


			<div>
				{
/*  al ser un array indexado json lo trata como un objeto y debemos hacer el object.values para convertirlo en array  */
 Object.values(props.tallasColoresList).length	 > 0 &&
				Object.values(props.tallasColoresList).map((tallaColor, idx) =>
						<div key={idx} >
							<div  className="titleFilter" >
							<strong>	{tallaColor[0].name_variante}</strong>
							<br/>
							</div>

							<div className="tallaColorOption" key={idx} >
									<input  type="radio" name={"tallaColor[" + tallaColor[0].id_variante + "]"} id= { "tallaColor_all" } value="0"  checked={ true  }    onChange={()=>props.tallaColorClick(tallaColor[0].id_variante, "")} />
									<label  htmlFor={ "tallaColor_" + tallaColor[0].id_variante + "_all"}>Todos  </label>
								</div>

							{tallaColor.length > 0   &&
								tallaColor.map((i, idx) =>
								<div className="tallaColorOption" key={idx} >
									<input  type="radio" name={"tallaColor[" + i.id_variante + "]"} id= { "tallaColor_" + i.id_valvariantes} value={i.id_valvariantes}  checked={ props.tallaColor && i.id_valvariantes === props.tallaColor[i.id_variante] }    onChange={()=>props.tallaColorClick(i.id_variante, i.id_valvariantes)} />
									<label  htmlFor={ "tallaColor_" + i.id_valvariantes}>{i.valor_valvariante} ({i.cuantos}) </label>
								</div>
								)
							}
						</div>
					)



				}


			</div>
		)
	}
	export default TallasColores
