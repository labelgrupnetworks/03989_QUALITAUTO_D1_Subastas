import { useFamilies } from "../../../hooks/useFamilies";
import { useForm } from "../../../hooks/useForm";

export function Familias() {

	const { formFields, setFormFields } = useForm();
	const { familiasList } = useFamilies({ fields: formFields });

	const selectedFamily = formFields.familia;

	const familiaClick = (familia) => {

		const isSameFamily = (familia === selectedFamily);

		setFormFields({
			...formFields,
			familia: isSameFamily ? '' : familia,
			page: 1,
		});
	}

	if (familiasList.length == 0) return;

	return (
		<fieldset>
			<legend className="titleFilter">
				{trans('articles_js.collections')}
			</legend>

			{familiasList.map((familia) =>
				<div className="form-check" key={familia.cod_famart}>
					<input
						className="form-check-input"
						type="checkbox"
						id={`familia_${familia.cod_famart}`}
						checked={selectedFamily == familia.cod_famart}
						value={familia.cod_famart}
						onChange={(event) => familiaClick(event.target.value)}
					/>
					<label className="form-check-label" htmlFor={`familia_${familia.cod_famart}`}>
						{familia.des_famart} <span className="filter-counter">({familia.cuantos})</span>
					</label>
				</div>
			)}
		</fieldset>
	)
}
