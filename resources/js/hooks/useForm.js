import { useContext } from "react";
import { FormContext } from "../context/form.jsx";

export function useForm() {
	const { formFields, setFormFields } = useContext(FormContext);

	return {
		formFields,
		setFormFields
	}
}
