export function Article({ urlArt, imgArt, desArt, pvpArt }) {
	return (
		<article className="article-card">
			<a href={urlArt}>
				<div className="article-card-image-wrapper">
					<img className="article-card-img" src={imgArt} alt={`Imagén de ${desArt}`}></img>
				</div>
			</a>

			<div className="article-card-content">
				<p className="article-card-title"> {desArt}</p>
				{pvpArt != "0 €" && <p className="article-card-price">{pvpArt}</p>}
			</div>
		</article>
	)
}
