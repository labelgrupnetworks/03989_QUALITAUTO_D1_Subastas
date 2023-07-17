import { OrtSec } from './OrtSec.jsx'
import { Search } from './Search.jsx'
import { TallasColores } from './TallasColores.jsx'
import { Marcas } from './Marcas.jsx'
import { Familias } from './Familias.jsx'

export function Filters() {

	return (
		<>
			<Search />

			<div className="ortsec_rcomponent">
				<OrtSec />
			</div>

			<Familias />

			<Marcas />

			<TallasColores />
		</>
	)
}

