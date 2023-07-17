import { useForm } from "../../../hooks/useForm.js";
import { useMarcas } from "../../../hooks/useMarcas.js";

export function Marcas() {

	const { formFields, setFormFields } = useForm();
	const { marcasList } = useMarcas({ fields: formFields });

	const marcaSelected = formFields.marca;

	const marcaClick = (marca) => {
		setFormFields((prevState) => ({
			...prevState,
			marca: marca,
			page: 1
		}))
	}

	if (marcasList.length == 0) return;


	return (

		<fieldset>
			<legend className="titleFilter">
				{trans('articles_js.brands')}
			</legend>

			{marcasList.map((marca) =>
				<div key={marca.marca_marca} className="form-check">
					<input
						id={`marca_${marca.marca_marca}`}
						className="form-check-input"
						type="checkbox"
						checked={marcaSelected === marca.marca_marca}
						value={marca.marca_marca}
						onChange={(event) => marcaClick(event.target.value)}
					/>
					<label className="form-check-label" htmlFor={`marca_${marca.marca_marca}`}>
						{marca.des_marca} <span className="filter-counter">{`(${marca.cuantos})`}</span>
					</label>
				</div>
			)}
		</fieldset>
	)
}
