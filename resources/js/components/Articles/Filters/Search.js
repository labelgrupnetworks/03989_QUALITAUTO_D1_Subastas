import React, { useEffect,useState } from 'react'
const Search = props => {

	const [textVal, setText] = useState("")

	const keyDown = async (key) => {
		if (key === 'Enter') {
			props.searchText(textVal)
		  }
	 }

	 const DeleteSearchText =  () => {
		setText("")
		props.searchText("")

	 }

	 return (
		<div >
			<div className="infoText">{trans('articles_js.search_text')}</div>
			<div >
				<input className="searchText" name="description"   type="text" value = {textVal} onChange={(e)=>setText(e.target.value)} onKeyDown={(e)=>keyDown(e.key)}></input>

				<span className=" deleteSearchText" onClick={()=>DeleteSearchText()}>X</span>
			</div>
			<div><input className="filtrarBtn" type="button" value={trans('articles_js.search')} onClick={()=>props.searchText(textVal)} /></div>
		</div>

	 )

}
export default Search
