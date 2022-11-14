import React, { useEffect,useState } from 'react'

import OrtSec from './OrtSec.js'
import Search from './Search.js'
import TallasColores from './TallasColores.js'
import Marcas from './Marcas.js'
import Familias from './Familias.js'




const Filters = props => {


/* PUEDEN VENIR FAMILIA Y SUBFAMILIA POR LA URL  */
//const [props.formFields, props.setFormFields] = useState({ ["ortsec"]: ortSec, ["sec"]: sec, ["tallaColor"]: [], ["marca"]: '', ["familia"]: familia})


useEffect(() => {
	/* llama a la funcion del padre para que recargue el formulario   */
	props.submitFormFunc(props.formFields);
}, [props.formFields])

const collapseWithIconClick = (e) => {

	const element = (e.target.nodeName != 'I') ? e.target : e.target.closest('div[data-toggle="collapse"]');
	const icon = element.querySelector('i');

	if(element.ariaExpanded === "true") {
		icon.classList.remove('fa-caret-down');
		icon.classList.add('fa-caret-up');
		return;
	}

	icon.classList.add('fa-caret-down');
	icon.classList.remove('fa-caret-up');
}


/* TEXT */

const searchText = async (text) => {
	/* IMPORTANTE, resetea todas las variables, solo habrá search */
	let tallaColor = [...props.formFields.tallaColor];
	//inicializamos todas las opciones de talla color
	for (let i of tallaColor.keys()){
		tallaColor[i] = "";
	  }
	props.setFormFields({
		["tallaColor"]:tallaColor,
		["marca"]:"",
		["familia"]:"",
		["ortsec"]: "",
		["sec"]: "",
		["search"]: text
	});

 }

/* FIN TEXT */

/* ORTSEC */
const [ortSecList, setOrtSecList] = useState([])
/*const [ortSecValue, setOrtSecValue] = useState()*/
	useEffect(() => {
		getOrtsec()
	}, [props.formFields.search, props.formFields.tallaColor, props.formFields.marca, props.formFields.familia])


	/* carga la lista de ortsec */
	const getOrtsec = async() => {
		try {
			let res = await fetch('/api/es/getOrtsec',{
				method: 'POST',
				body: JSON.stringify(props.formFields),
				headers: {
					'Content-Type': 'application/json'
				}
			})

			res = await res.json()

			setOrtSecList(res)
		} catch (error) {

		}

	}

	const ortSecClick = async (id) => {
		// modificamos los parametros, sec hay que ponerlo a cero
			props.setFormFields({
				...props.formFields,
				["ortsec"]: id,
				["sec"]: ""
			});
	 }

/* FIN ORTSEC */


/* SEC */

//Si cambia ortsec, cargamos sec
useEffect(() => {
	getSec()
}, [props.formFields.ortsec, props.formFields.tallaColor, props.formFields.marca, props.formFields.familia])


const [secList, setSecList] = useState([])

/*  carga el listado de secciones teniendo en cuenta el resto de variables */
const getSec = async() => {
	try {
		// vaciamos el valor de la seccion para que desaparezca del listado
		setSecList([]);

		let res = await fetch('/api/es/getSec',{
			method: 'POST',
			body: JSON.stringify(props.formFields),
			headers: {
				'Content-Type': 'application/json'
			}
		})


		res = await res.json()
		setSecList(res)
	} catch (error) {
		console.log(error);
	}

}

const secClick = async (id) => {
	/* llamada a la función del padre */
	props.setFormFields({
		...props.formFields,
		["sec"]: id
	});

 }


/* FIN SEC */





/* TALLASCOLORES */
const [tallasColoresList, setTallasColoresList] = useState([])

	useEffect(() => {
		getTallasColores()
	}, [props.formFields.search, props.formFields.ortsec, props.formFields.sec, props.formFields.tallaColor, props.formFields.marca,  props.formFields.familia])



	const getTallasColores = async() => {
		try {
			let res = await fetch('/api/es/getTallasColores',{
				method: 'POST',
				body: JSON.stringify(props.formFields),
				headers: {
					'Content-Type': 'application/json'
				}
			})

			res = await res.json()
			// debemos pasar aobject values por que si no n oes un array
			res = Object.values(res);

			setTallasColoresList( res)

		} catch (error) {
			console.log(error);
		}

	}

	const hideFilters = () => {
		$('#filter-container').hide('slow');
		$('#order-container').hide('slow');
	 }

	const tallaColorClick = async ( id,value) => {

		hideFilters();
		//cogemos el valor actual de tallacolor y le añadimos o modificamos un elemento del array
		//si ya estaba seleccionado lo quitamos
			let tallaColor = [...props.formFields.tallaColor];
			if( tallaColor[id] == value){
				value="";
			}

			tallaColor[id]=value

			props.setFormFields({
				...props.formFields,
				["tallaColor"]: tallaColor,

			});
	 }



/* FIN TALLASCOLORES */

/* MARCAS */
const [marcasList, setMarcasList] = useState([]);

useEffect(() => {
	getMarcas();
}, [props.formFields.search, props.formFields.ortsec, props.formFields.sec, props.formFields.tallaColor, props.formFields.familia])


const getMarcas = async() => {
	try {
		let res = await fetch('/api/es/getMarcas',{
			method: 'POST',
			body: JSON.stringify(props.formFields),
			headers: {
				'Content-Type': 'application/json'
			}
		})

		res = await res.json()
		// debemos pasar aobject values por que si no n oes un array
		res = Object.values(res);

		setMarcasList(res)

	} catch (error) {
		console.log(error);
	}

}

const marcaClick = async (marca) => {

	hideFilters();

	/* llamada a la función del padre */
	props.setFormFields({
		...props.formFields,
		["marca"]: marca
	});

 }

 /* FIN MARCAS */


 /* FAMILIAS */
const [familiasList, setFamiliasList] = useState([]);

useEffect(() => {
	getFamilias();
}, [props.formFields.search, props.formFields.ortsec, props.formFields.sec, props.formFields.tallaColor, props.formFields.marca])


const getFamilias = async() => {
	try {
		let res = await fetch('/api/es/getFamilias',{
			method: 'POST',
			body: JSON.stringify(props.formFields),
			headers: {
				'Content-Type': 'application/json'
			}
		})

		res = await res.json()
		// debemos pasar aobject values por que si no n oes un array
		res = Object.values(res);

		setFamiliasList(res)

	} catch (error) {
		console.log(error);
	}

}

const familiaClick = async (familia) => {

	hideFilters();

	/* llamada a la función del padre */
	if(familia === props.formFields.familia){
		props.setFormFields({...props.formFields, familia: ''});
		return;
	}

	props.setFormFields({
		...props.formFields,
		["familia"]: familia
	});

 }

 /* FIN FAMILIAS */


    return (
        <div className="filtersArticles">
			<h1>{trans('articles_js.filters')}</h1>

			<div><Search searchText={searchText}></Search></div>

			<div className="ortsec_rcomponent">
				<OrtSec
					ortSecList = {ortSecList}
					ortSecValue = {props.formFields.ortsec}
					ortSecClick = {ortSecClick}
					secList = {secList}
					secValue = {props.formFields.sec}
					secClick = {secClick}
					collapseEvent = {collapseWithIconClick}
				/>
			</div>
			<div><Familias familiasList={familiasList} familiaClick={familiaClick} familia={props.formFields.familia} collapseEvent = {collapseWithIconClick}/></div>
			<div><Marcas marcasList={marcasList} marcaClick={marcaClick} marca={props.formFields.marca} collapseEvent = {collapseWithIconClick}/></div>

			<div><TallasColores tallasColoresList={tallasColoresList}  tallaColorClick = {tallaColorClick}  tallaColor= {props.formFields.tallaColor} collapseEvent = {collapseWithIconClick}/></div>
        </div>
    )
}

export default Filters
