import React from 'react'
import Pujas from './components/Tiempo_real/Pujas';
import ReactDOM from 'react-dom';

/**
 * Para que funcione añadir siguiente script al final de:
 * resources/views/default/layouts/tiempo_real.blade
 * <script src="{{ URL::asset('/js/default/TiempoRealComponents.js') }}" ></script>
 * Y los contenedores necesarios
 */

const socket = io.connect(routing.node_url, { 'forceNew': true });

socket.on('connect', () => {

	//Establecemos conexión a sala
	socket.emit('room', {cod_sub: auction_info.subasta.cod_sub, id: socket.id});

	//Controlamos que exita el contenedor
	if(document.getElementById('pujasContainer')){

		//Inicializamos componentes con las variables globales
		const pujas = auction_info.lote_actual.pujas || [];

		//Rendericamos los componentes con las variables iniciales
		ReactDOM.render(<Pujas socket={socket} pujasIniciales={pujas} />, document.getElementById('pujasContainer') );
	}

});
