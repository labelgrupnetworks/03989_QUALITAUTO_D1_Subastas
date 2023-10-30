import { useState } from "react";
import { Paginate } from "./Paginate.jsx";
import { Article } from "./Article.jsx";
import { useArticles } from "../../../hooks/useArticles";
import { useForm } from "../../../hooks/useForm";

export function ArticlesList() {

	const { formFields, setFormFields } = useForm();
	const { articles, pageCount } = useArticles({ fields : formFields});

	const startPageValue = parseInt(formFields.page - 1, 10);
	const [offset, setOffset] = useState(startPageValue);

	const handlePageClick = (event) => {
		const selectedPage = event.selected;
		setOffset(selectedPage);

		setFormFields((prevState) => ({
			...prevState,
			page: selectedPage + 1
		}));
	};

	return (
		<div className="grid-container">

			<div className="top-pagination-wrapper text-center">
				<Paginate offset={offset} pageCount={pageCount} handlePageClick={handlePageClick} />
			</div>

			<div className="Grid articles-container">
				{articles.map(({ id_art0, pvpFormat, model_art0, url, img }) =>
					<Article key={id_art0} pvpArt={pvpFormat} desArt={model_art0} urlArt={url} imgArt={img} />
				)}
			</div>

			<div className="bottom-pagination-wrapper text-center">
				<Paginate offset={offset} pageCount={pageCount} handlePageClick={handlePageClick} />
			</div>
		</div>
	)
}
