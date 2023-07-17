import { useEffect, useState } from "react";
import { getOrtsec, getSec } from "../services/articles";

export function useSections({ fields }) {

	const [ortSecList, setOrtSecList] = useState([]);
	const [secList, setSecList] = useState([])

	useEffect(() => {
		getOrtsec({ fields })
			.then((res) => {
				setOrtSecList(res);
			})

	}, [fields.search, fields.tallaColor, fields.marca, fields.familia])

	useEffect(() => {
		setSecList([]);

		getSec({ fields })
			.then((res) => {
				setSecList(res);
			})

	}, [fields.ortsec, fields.tallaColor, fields.marca, fields.familia])

	return {
		ortSecList,
		secList
	}
}
