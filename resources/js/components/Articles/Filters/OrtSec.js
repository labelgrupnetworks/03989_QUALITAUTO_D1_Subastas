import React, { useEffect,useState } from 'react'
const OrtSec = props => {













		return (


			<div>
				{props.ortSecList.length > 0 &&
				<div>
					<div className="titleFilter">
						{trans('articles_js.catalogue')}
					</div>
					<div className="allOrtsec">
						<input  type="radio" name="ortsec" id="ortsec_all" value=""  checked={props.ortSecValue == ""} onChange={()=>props.ortSecClick("")} />
						<label  id="label_ortsec_all"  htmlFor="ortsec_all"> Todas</label>
					</div>
				</div>
				}
				{props.ortSecList.length > 0 &&
					props.ortSecList.map((i, idx) =>
						<div key={i.lin_ortsec0} >
							<div className="ortsec">
								<input  type="radio" name="ortsec" id= { "ortsec_" + i.lin_ortsec0} value={i.lin_ortsec0}  checked={i.lin_ortsec0 === props.ortSecValue} onChange={()=>props.ortSecClick(i.lin_ortsec0)} />
								<label id= { "label_ortsec_" + i.lin_ortsec0}  htmlFor={ "ortsec_" + i.lin_ortsec0}>{i.des_ortsec0}  ({i.cuantos})</label>
							</div>

							{/* ponemos la opcion de todas las secciones  */}
							{props.secList.length > 0 && props.ortSecValue == i.lin_ortsec0  &&
								<div className="sec">
									<input  type="radio" name="sec" id="sec_all" value=""  checked={props.secValue == ""} onChange={()=>props.secClick("")} />
									<label  htmlFor="sec_all"> {trans('articles_js.all')}</label>
								</div>
							}
							{props.secList.length > 0 && props.ortSecValue == i.lin_ortsec0  &&
								props.secList.map((i, idx) =>
								<div className="sec" key={i.cod_sec} >
									<input  type="radio" name="sec" id= { "sec_" + i.cod_sec} value={i.cod_sec}  checked={i.cod_sec === props.secValue}    onChange={()=>props.secClick(i.cod_sec)} />
									<label  htmlFor={ "sec_" + i.cod_sec}>{i.des_sec} ({i.cuantos})</label>
								</div>
								)
							}
						</div>
					)



				}


			</div>
		)
	}
	export default OrtSec
