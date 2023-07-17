import { useForm } from "../../../hooks/useForm";
import { useTallaColores } from "../../../hooks/useTallaColores";

export function TallasColores() {
	const { formFields, setFormFields } = useForm();
	const { tallasColoresList } = useTallaColores({ fields: formFields });

	const tallaColorSelected = formFields.tallaColor;

	const tallaColorClick = async (id, value) => {

		//cogemos el valor actual de tallacolor y le añadimos o modificamos un elemento del array
		//si ya estaba seleccionado lo quitamos
		let tallaColorValues = [...formFields.tallaColor];
		if (tallaColorValues[id] == value) {
			value = "";
		}

		tallaColorValues[id] = value

		setFormFields((prevState) => ({
			...prevState,
			tallaColor: tallaColorValues,
			page: 1
		}));
	}

	if (Object.values(tallasColoresList).length == 0) return;

	return (
		<div>
			{
				/*  al ser un array indexado json lo trata como un objeto y debemos hacer el object.values para convertirlo en array  */
				Object.values(tallasColoresList).map((tallaColores, idx) =>
					<fieldset key={idx}>
						<legend className="titleFilter">
							{tallaColores[0].name_variante}
						</legend>

						{tallaColores.length > 0 && tallaColores.map((tallaColor) =>
							<div key={tallaColor.id_valvariantes} className="form-check">

								<input
									className="form-check-input"
									id={`tallaColor_${tallaColor.id_valvariantes}`}
									type="checkbox"
									checked={tallaColorSelected[tallaColor.id_variante] == tallaColor.id_valvariantes}
									value={tallaColor.id_valvariantes}
									onChange={(event) => tallaColorClick(tallaColor.id_variante, event.target.value)}
								/>
								<label
									className="form-check-label"
									htmlFor={`tallaColor_${tallaColor.id_valvariantes}`}
									>
									{tallaColor.valor_valvariante} <span className="filter-counter">{`(${tallaColor.cuantos})`}</span>
								</label>
							</div>

						)
						}


					</fieldset>
				)
			}
		</div>
	)
}
export default TallasColores
