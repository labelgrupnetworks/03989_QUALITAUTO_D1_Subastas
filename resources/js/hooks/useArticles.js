import { useEffect, useState } from "react"
import { getArticles } from "../services/articles";

export function useArticles({ fields }) {
	const [articles, setArticles] = useState([])
	const [perPage, setPerPage] = useState(0);
	const [pageCount, setPageCount] = useState(0);

	const getDataArticles = (fields) => {
		getArticles({ fields, language })
			.then(response => {
				setArticles(response.data)
				setPerPage(response.per_page)
				setPageCount(response.last_page)
			});
	}

	useEffect(() => {
		getDataArticles(fields)
	}, [fields])

	return {
		articles,
		pageCount
	}
}
