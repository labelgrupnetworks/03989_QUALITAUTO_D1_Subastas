import { useState } from 'react';
import { useForm } from "../../../hooks/useForm.js";

export function Search() {

	const { formFields, setFormFields } = useForm();
	const [textVal, setText] = useState("")

	const searchText = async (text) => {

		/* IMPORTANTE, resetea todas las variables, solo habrá search */
		let tallaColor = [...formFields.tallaColor];

		//inicializamos todas las opciones de talla color
		for (let i of tallaColor.keys()) {
			tallaColor[i] = "";
		}

		setFormFields({
			tallaColor: tallaColor,
			marca: "",
			familia: "",
			ortsec: "",
			sec: "",
			search: text,
			page: 1
		});

	}

	const keyDown = (key) => {
		if (key === 'Enter') {
			searchText(textVal)
		}
	}

	const deleteSearchText = () => {
		setText("")
		searchText("")
	}

	return (
		<div className='filters-auction-texts'>
			<div className="form-floating">
				<input
					value={textVal}
					onChange={(e) => setText(e.target.value)}
					onKeyDown={(e) => keyDown(e.key)}
					type="text" className="form-control" name="description" placeholder="BUSCAR POR TEXTO" aria-label="buscar lote por texto" />
				<label htmlFor="description">{trans('articles_js.search_text')}</label>
			</div>

			{textVal.length > 0 &&
				<span className="delete-text-button" onClick={() => deleteSearchText()}>
					<svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
						<line x1="1.17678" y1="1.09666" x2="10.9065" y2="10.8264" stroke="black" strokeWidth="0.5" />
						<line y1="-0.25" x2="13.7599" y2="-0.25" transform="matrix(-0.707107 0.707107 0.707107 0.707107 11 1)" stroke="black" strokeWidth="0.5" />
					</svg>
				</span>
			}

			<button onClick={() => searchText(textVal)} type="submit" className="btn btn-lb-primary btn-medium">
				{trans('articles_js.search')}
			</button>
		</div>
	)
}

