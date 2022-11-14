import React, { useEffect, useState } from 'react'
import Article from './Article/Article.js'
import Filters from './Filters/Filters.js'
import ReactPaginate from 'react-paginate';




const Grid = () => {
//declarar estado
const [data, setData] = useState([])
//guardamos una copia de los campos para usarla cuando hagan cambio de página
const [formFields, setFormFields] = useState()

/* PAGINATION */
const [offset, setOffset] = useState(0);
const [perPage, setPerPage] = useState(0);
const [pageCount, setPageCount] = useState(0)


const handlePageClick = (e) => {
    const selectedPage = e.selected;
    setOffset(selectedPage )
	getData(formFields,selectedPage + 1)

};
/* FIN PAGINATION */


//declaracion de funcion get data


const submitForm = (fields) => {

	setFormFields(fields)
	getData(fields,1)
}


    const getData = async(fields,page) => {
        try {
			fields.page=page
            let res = await fetch('/api/es/getArticles',{
				method: 'POST',
				body: JSON.stringify(fields),
				headers: {
					'Content-Type': 'application/json'
				}
			})

            res = await res.json()


            setData(res.data)
			setPerPage(res.per_page)
			setPageCount(res.last_page)
        } catch (error) {

        }

    }



	//return de la clase
    return (
		<div>

			<div className="col-xs-12 col-sm-3" >
				<Filters submitFormFunc={submitForm} />
			</div>

			<div  className="col-xs-12 col-sm-9" >
				<div className="text-center" >
					<ReactPaginate
						forcePage={offset}
						previousLabel={"<"}
						nextLabel={">"}
						breakLabel={"..."}
						breakClassName={"break-me"}
						pageCount={pageCount}
						marginPagesDisplayed={2}
						pageRangeDisplayed={3}
						onPageChange={handlePageClick}
						containerClassName={"pagination"}
						subContainerClassName={"pages pagination"}
						activeClassName={"active"}/>
				</div>
				<div className="Grid">
					{
						data.map((i, idx) =>  <Article key={i.id_art0}  pvpArt={i.pvpFormat}  desArt={i.model_art0} urlArt={i.url} imgArt={i.img} />
						)
					}

				</div>

				<div className="row">
					<div className="col-xs-12 text-center">

					<ReactPaginate
						forcePage={offset}
						previousLabel={"<"}
						nextLabel={">"}
						breakLabel={"..."}
						breakClassName={"break-me"}
						pageCount={pageCount}
						marginPagesDisplayed={2}
						pageRangeDisplayed={3}
						onPageChange={handlePageClick}
						containerClassName={"pagination"}
						subContainerClassName={"pages pagination"}
						activeClassName={"active"}/>

					</div>
				</div>


			</div>
		</div>

    )
}
export default Grid
