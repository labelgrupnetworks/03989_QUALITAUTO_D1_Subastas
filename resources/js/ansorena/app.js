
import { FormProvider } from '../context/form.jsx';
import Grid from '../components/Articles/Grid.jsx'
import ReactDOM from 'react-dom/client';

const rootElement = document.getElementById('grid');
const root = ReactDOM.createRoot(rootElement);

const queryParams = new URLSearchParams(window.location.search);
const startPage = queryParams.get('page') || "1";

const tallasColoresList = queryParams.get('tallaColor') || "";

const initialState = {
	ortsec: rootElement.dataset.ortSec,
	sec: rootElement.dataset.sec,
	familia: rootElement.dataset.familia,
	tallaColor: tallasColoresList.split(','),
	order: queryParams.get('order') || "id_art0",
	orderDir: queryParams.get('order_dir') || "desc",
	//search: queryParams.get('search') || "desc",
	page: startPage,
}

root.render(
	<FormProvider initialState={initialState} >
		<Grid />
	</FormProvider>
);
