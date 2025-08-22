
import { FormProvider } from '../context/form.jsx';
import Grid from '../components/Articles/Grid.jsx'
import ReactDOM from 'react-dom/client';

const rootElement = document.getElementById('grid');

const queryParams = new URLSearchParams(window.location.search);
const startPage = queryParams.get('page') || "1";

const tallasColoresList = queryParams.get('tallaColor') || "";

const search = queryParams.get('search') || "";
//buscar y remplazar simbolos '+' por espacios
const searchInput = search.replace(/\+/g, ' ');

const initialState = {
	ortsec: rootElement.dataset.ortSec,
	sec: rootElement.dataset.sec,
	familia: rootElement.dataset.familia,
	tallaColor: tallasColoresList.split(','),
	order: queryParams.get('order') || "id_art0",
	orderDir: queryParams.get('order_dir') || "desc",
	search: searchInput || "",
	page: startPage,
}

ReactDOM.createRoot(document.getElementById('grid')).render(
	<FormProvider initialState={initialState} >
		<Grid />
	</FormProvider>
);

