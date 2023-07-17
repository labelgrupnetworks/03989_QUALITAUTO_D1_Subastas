import { Fragment } from "react";
import { useForm } from "../../../hooks/useForm.js";
import { useSections } from "../../../hooks/useSections.js";
import { RadioInput } from "../../Forms/RadioInput.jsx";

export function OrtSec() {

	const { formFields, setFormFields } = useForm();
	const { ortSecList, secList } = useSections({ fields: formFields });

	const ortSecValue = formFields.ortsec;
	const secValue = formFields.sec;

	const ortSecClick = (ortsec) => {

		setFormFields((prevState) => ({
			...prevState,
			ortsec: ortsec,
			sec: "",
			page: 1
		}));
	}

	const secClick = (sec) => {

		setFormFields({
			...formFields,
			sec: sec,
			page: 1
		});

	}

	if (ortSecList.length == 0) return;

	return (

		<fieldset>

			<legend className="titleFilter">
				{trans('articles_js.catalogue')}
			</legend>

			<RadioInput id="ortsec_all" text={trans('articles_js.all')} checked={ortSecValue === ""} onChange={() => ortSecClick("")} />

			{ortSecList.map((ortSec) =>
				<Fragment key={ortSec.lin_ortsec0}>
					<RadioInput id={`ortsec_${ortSec.lin_ortsec0}`} text={`${ortSec.des_ortsec0} (${ortSec.cuantos})`} checked={ortSec.lin_ortsec0 === ortSecValue} onChange={() => ortSecClick(ortSec.lin_ortsec0)} />

					{ortSec.lin_ortsec0 == ortSecValue && secList.length > 0
						&& <div className="sections">
							<RadioInput id="sec_all" text={trans('articles_js.all')} checked={secValue == ""} onChange={() => secClick("")} />

							{secList.map((sec) => {
								return (
									<RadioInput key={"sec_" + sec.cod_sec} id={"sec_" + sec.cod_sec} text={`${sec.des_sec} (${sec.cuantos})`} checked={sec.cod_sec === secValue} onChange={() => secClick(sec.cod_sec)} />
								)
							})}
						</div>
					}
				</Fragment>
			)}
		</fieldset>
	)
}
