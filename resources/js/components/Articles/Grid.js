import React, { useEffect, useState } from 'react'
import Article from './Article/Article.js'
import Filters from './Filters/Filters.js'
import ReactPaginate from 'react-paginate';
import Order from './Filters/Order.js';




const Grid = () => {
//declarar estado
const [data, setData] = useState([])
//guardamos una copia de los campos para usarla cuando hagan cambio de página
//const [formFields, setFormFields] = useState()

/* PAGINATION */
const [offset, setOffset] = useState(startPage-1);
const [perPage, setPerPage] = useState(0);
const [pageCount, setPageCount] = useState(0);

const [formFields, setFormFields] = useState({ ["ortsec"]: ortSec, ["sec"]: sec,  ["marca"]: '', ["familia"]: familia, ["search"]: '', ["order"]: order, ["order_dir"]: orderDir,["tallaColor"]: tallaColor.split(",")});

const handlePageClick = (e) => {
    const selectedPage = e.selected;
    setOffset(selectedPage )
	getData(formFields,selectedPage + 1)

};

const handleFilterView = (e) => {

	$('#order-container').hide('fast');

	const element = document.getElementById('filter-container')
	const estateFilter = window.getComputedStyle(element, null).display;

	estateFilter == 'none' ? $('#filter-container').show('slow') : $('#filter-container').hide('slow');
}

const handleOrderView = (e) => {

	$('#filter-container').hide('fast');

	const element = document.getElementById('order-container')
	const estateFilter = window.getComputedStyle(element, null).display;

	estateFilter == 'none' ? $('#order-container').show('slow') : $('#order-container').hide('slow');
}


/* FIN PAGINATION */


//declaracion de funcion get data


const submitForm = (fields) => {

	setFormFields(fields)
	//recuperamos las variables por get
	const queryParams = new URLSearchParams(window.location.search)
	//cojemos la página de la variable global, de manera que la primera vez es la que marca la url y el resto es 1 ya que en la url siempre pondra algo pro que la modificamos
	var newPage = startPage;
	startPage = 1;

	getData(fields,newPage);
	setOffset(newPage-1 );
}


    const getData = async(fields,newPage) => {
        try {

			fields.page=newPage
            let res = await fetch(`/api/${language}/getArticles`,{
				method: 'POST',
				body: JSON.stringify(fields),
				headers: {
					'Content-Type': 'application/json'
				}
			})


			//ponemos las variables en la url

			var pegamento="?";
			var variables="";

			Object.keys(fields).forEach(function(e){
				variables+=  pegamento + e + "=" + fields[e];
				pegamento="&";
			});

			//la primera vez se carga la url que ya venia por url, a partir de ahí usamos la generica que está en urlArticulos
			history.pushState(null, "", startUrl + variables );

			startUrl = urlArticulos;


/*
			//recorremos todos lso objetos
			pegamento="";
			vars="?";
			Object.keys(fields).forEach(function(e){
				console.log(e);
				vars+= pegamento + e + "=" + $(this)[e];
				pegamento="&";
				//hay que guardar las vaiables para luego montarlo todo
			})

		console.log(vars);
		var url = location.origin + location.pathname+vars ;
		history.pushState(null, "", url);
*/


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
			<div className="col-xs-12 mt-1 mb-1">
				<div className="btn-filter" onClick={handleFilterView}><span>{trans('articles_js.search_filter')}</span><i className="fa fa-sort-down"></i></div>
				<div className="btn-filter" onClick={handleOrderView}><span>{trans('articles_js.ordered_for')}</span><i className="fa fa-sort-down"></i></div>
			</div>

			<div className="col-xs-12">
				<div className="d-flex gridpage-container">

					<div id="filter-container">
						<Filters submitFormFunc={submitForm} formFields={formFields} setFormFields={setFormFields}/>
					</div>
					<div id="order-container">
						<Order submitFormFunc={submitForm} formFields={formFields} setFormFields={setFormFields}/>
					</div>

					<div className="grid-container">

						<div className="text-center">
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
								containerClassName={"pagination pagination-top"}
								subContainerClassName={"pages pagination "}
								activeClassName={"active"}
							/>
						</div>

						<div className="Grid articles-container">
						{
							data.map(
								(i, idx) =>  <Article key={i.id_art0}  pvpArt={i.pvpFormat}  desArt={i.model_art0} urlArt={i.url} imgArt={i.img} />
							)
						}
						</div>

						<div className="text-center">
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
								containerClassName={"pagination pagination-bottom"}
								subContainerClassName={"pages pagination "}
								activeClassName={"active"}
							/>
						</div>
					</div>

				</div>
			</div>

		</div>

    )
}
export default Grid
