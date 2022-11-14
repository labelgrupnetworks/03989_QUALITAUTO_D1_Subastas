import React, { useEffect,useState } from 'react'
const OrtSec = props => {
	const [ortSecList, setOrtSecList] = useState([])
	const [secList, setSecList] = useState([])
	const [ortSecValue, setOrtSecValue] = useState()
	const [secValue, setSecValue] = useState()

		useEffect(() => {
			getOrtsec()
		}, [])



		const getOrtsec = async() => {
			try {
				let res = await fetch('/es/getOrtsec')
				res = await res.json()

				setOrtSecList(res)
			} catch (error) {

			}

		}


		/*  carga el listado de secciones con */
		const getSec = async(ortSec) => {
			try {
				let res = await fetch('/es/getSec?ortsec=' + ortSec)
				res = await res.json()
				setSecList(res)
			} catch (error) {

			}

		}

		const ortSecChange = async (id) => {
			setOrtSecValue(id)
			getSec(id)
			/* llamada a la función del padre */
			props.submitFormFunc("ortsec", id);
		 }

		 const secChange = async (id) => {
			setSecValue(id)

			/* llamada a la función del padre */
			props.submitFormFunc("sec", id);
		 }


		return (


			<div>
				{ortSecList.length > 0 &&
					<select name="ortsec" value={ortSecValue} onChange={(event) => ortSecChange(event.target.value)}>
						{
							ortSecList.map((i, idx) => <option key={i.lin_ortsec0} value={i.lin_ortsec0} > {i.des_ortsec0}</option>)
						}
					</select>
				}

					<br/>

				{secList.length > 0 &&
					<select name="sec" value={secValue} onChange={(event) => secChange(event.target.value)}>
						{
							secList.map((i, idx) => <option key={i.cod_sec} value={i.cod_sec} > {i.des_sec}</option>)
						}
					</select>
				}
			</div>
		)
	}
	export default OrtSec
