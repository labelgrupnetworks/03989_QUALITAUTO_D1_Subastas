import React, { useState } from 'react'

const Article = props => {
/*
    const [click, setClick] = useState(false)
    const [nuevoTitulo, setnuevoTituylo] = useState(null)

    const mifuncion = async (id) => {
       let res = await fetch('https://jsonplaceholder.typicode.com/todos/' + id)
        res = await res.json()
        setnuevoTituylo(res.title)
    }
*/
    return (

		<a className="article article-element" href={props.urlArt}>

			<div className="article-foto" style={{ backgroundImage: `url("${props.imgArt}")`  }}></div>

			<div className="article-content">
				<div className="artTitle"> {props.desArt}</div>
				{props.pvpArt !="0 €" &&
				<div className="artPrice">  {props.pvpArt}</div>
				}
			</div>
		</a>
    )
}

export default Article
