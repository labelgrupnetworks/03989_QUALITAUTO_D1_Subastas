window.addEventListener('load', function (event) {
	document.getElementById('staticPageSave')?.addEventListener('click', sendForm);
});

function sendForm() {
	document.querySelector('[name="content_web_page"]').value = saveEditor(editor);
	window.staticPagesForm.submit();
}

//Componenetes a añadir una vez creado el contenedor grape
function grapeOnLoad(editor) {

	const blockManager = editor.BlockManager;
	const assetManager = editor.AssetManager;

	editor.on('load', function () {

		//Añadir selectores de estilos #id, objeto, posicion
		editor.StyleManager.addSector('general', generalConfig, { at: 0 });
		editor.StyleManager.addSector('extra', extraConfig, { at: 5 });

		//Añadir estilos y asegurarse que siempre se situa arriba
		//desactivado ya que lo realizamos en otro momento.
		//const framework = document.querySelector('[name="css_styles"]')?.value;//Añadir oja de esilos a iframe
		//editor.addComponents(`<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">`);
		//editor.addComponents(framework);
		//editor.getComponents().add(framework, {at: 0});


		//Eventos de assets (imagenes)
		//editor.on('asset:upload:start', () => console.log('start'));
		//editor.on('asset:upload:end', () => console.log('end'));
		editor.on('asset:upload:error', (err) => console.log(err.json()));
		editor.on('asset:upload:response', response => editor.AssetManager.add([response]));

		//Eventos de storage (guardado)
		//Ahora mismo no se usa, pero se puede usar para guardar en localstorage
		//editor.on('storage:start', () => console.log('start'));
		//editor.on('storage:end', () => console.log('end'));
		//editor.on('storage:error', (err) => alert(`Error: ${err}`));
		//editor.on('storage:start:store', (objectToStore) => objectToStore.customHtml = saveEditor(editor));

		//Activar contorno de componentes
		editor.runCommand('core:component-outline');

		//Añadir estilos o modificar contenido de los componentes existentes
		blockManager.get('image').getContent().style.width = '100%';

		//contenenedores
		//es necesario que los bloques tengan un padding superior e inferior para que se pueda
		//inserar contenido dentro de ellos
		const containersStyle = "padding-top: 20px; padding-bottom: 20px;";

		//Categoria de bloques
		const categoryContainer = { label: 'Contenedores', open: true, id: 'Contenedores', order: 0 };
		const categoryBlocks = { label: 'Bloques', open: true, id: 'Bloques', order: 1 };
		const categoryText = { label: 'Texto', open: true, id: 'Texto', order: 2 };
		const categoryElements = { label: 'Elementos', open: false, id: 'Elementos', order: 3 };

		blockManager.add('container-fluid-block', {
			label: 'Contenedor fluido',
			content: `<div class="container-fluid" style="${containersStyle}"></div>`,
			category: categoryContainer,
			editable: true,
			draggable: true,
			stylable: true,
			selectable: true,
			type: 'cell',
			media: `<svg width="65" height="65" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M6.611 4.833h51.778v55.333H6.611z"/><path stroke-width="2" stroke="#fff" stroke-dasharray="2,2" fill="none" d="M8.34 6.242h48.319v52.516H8.34z"/><path fill-opacity=".8" fill="#56aaff" d="M7.167 12.349h51.111v13.222H7.167z"/></svg>`,
			attributes: { title: 'Insertar container' },
		});

		blockManager.add('container-block', {
			label: 'Contenedor',
			content: `<div class="container" style="${containersStyle}"></div>`,
			category: categoryContainer,
			editable: true,
			draggable: true,
			stylable: true,
			selectable: true,
			type: 'cell',
			media: `<svg width="65" height="65" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M6.611 4.833h51.778v55.333H6.611z"/><path stroke-width="2" stroke="#fff" stroke-dasharray="2,2" fill="none" d="M8.34 6.242h48.319v52.516H8.34z"/><path fill-opacity=".8" fill="#56aaff" d="M13.833 12.349h37.333v13.222H13.833z"/></svg>`,
			attributes: { title: 'Insertar container' },
		});

		//Filas
		createColumn = (size) => `<div class="col-12 col-xs-12 col-md-${size}" style="${containersStyle}"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p></div>`;

		blockManager.get('sect100').set('category', categoryBlocks);
		blockManager.get('sect100').set('content', createColumn(12));
		blockManager.get('sect50').set('content', `<div class="row">${createColumn(6)}${createColumn(6)}</div>`).set('category', categoryBlocks);
		blockManager.get('sect30').set('content', `<div class="row">${createColumn(4)}${createColumn(4)}${createColumn(4)}</div>`).set('category', categoryBlocks);
		blockManager.get('sect37').set('content', `<div class="row">${createColumn(3)}${createColumn(9)}</div>`).set('category', categoryBlocks);

		blockManager.add('sect73', {
			label: '7/3',
			content: `<div class="row">${createColumn(9)}${createColumn(3)}</div>`,
			category: categoryBlocks,
			editable: true,
			draggable: true,
			stylable: true,
			selectable: true,
			type: 'default',
			media: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" transform="matrix(-1,0,0,1,0,0)"><path fill="currentColor" d="M2 20h5V4H2v16Zm-1 0V4a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1Zm9 0h12V4H10v16Zm-1 0V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H10a1 1 0 0 1-1-1Z"></path></svg>',
			attributes: { title: '7/3 Section' },
		});

		blockManager.add('centerBlock', {
			label: 'Center block',
			media: `<svg width="65" height="65" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M6.611 4.833h51.778v55.333H6.611z"/><path stroke-width="2" stroke="#fff" stroke-dasharray="2,2" fill="none" d="M8.34 6.242h48.319v52.516H8.34z"/><path fill-opacity=".8" fill="#56aaff" d="M9.167 12.349h46.222v18.333H9.167z"/><path fill-opacity=".9" fill="#ffaa56" d="M21.833 14.722h21.333v12.444H21.833z"/></svg>`,
			content: `
			<div style="display: flex; flex-direction: column; align-items: center; width:550px; max-width: 100%; margin: auto; padding: 20px 12px;">
				<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.</p>
			</div>`,
			category: categoryBlocks,
			selectable: true,
			attributes: { title: 'Bloque centrado' },
		});


		blockManager.add('list-ul', {
			label: 'List ul',
			attributes: { class: 'fa fa-list-ul', title: 'Insert list ul block' },
			content: '<ul><li>Añade tu lista aquí</li><li>Si quiere añadir lineas, copielas y peguelas encima de este</li></ul>',
			category: categoryElements,
		});
		blockManager.add('list-ol', {
			label: 'List ol',
			attributes: { class: 'fa fa-list-ol', title: 'Insert list ol block' },
			content: '<ol><li>Añade tu lista aquí</li><li>Si quiere añadir lineas, copielas y peguelas encima de este</li></ol>',
			category: categoryElements,
		});

		//Añadir bloques de texto
		blockManager.add('h1-block', {
			label: 'H1',
			content: '<h1>Put your title here</h1>',
			category: categoryText,
			attributes: { class: 'fa fa-header', title: 'Insert header block' },
		});

		blockManager.add('h2-block', {
			label: 'H2',
			content: '<h2>Put your title here</h2>',
			category: categoryText,
			attributes: { class: 'fa fa-header', title: 'Insert header block' },
		});

		blockManager.add('h3-block', {
			label: 'H3',
			content: '<h3>Put your title here</h3>',
			category: categoryText,
			attributes: { class: 'fa fa-header', title: 'Insert header block' },
		});

		blockManager.add('p-block', {
			label: 'Paragraph',
			content: '<p>Put your text here</p>',
			category: categoryText,
			attributes: { class: 'fa fa-paragraph', title: 'Insert paragraph block' },
		});

		blockManager.getAll().models.forEach((block) => {
			if (block.get('category') == '') {
				block.set('category', categoryElements);
			}
		});

		//ordenar categorias
		blockManager.getCategories().sortBy('label');


		blockManager.render();
	});

}


//Funcion para guardar html con css en linea
function saveEditor(editor) {
	return editor.runCommand('gjs-get-inlined-html');
}

const defaultGrapeOptions = {
	container: '#gjs', //id contenedor
	plugins: ['grapesjs-preset-newsletter'], //plugins activos
	pluginsOpts: {}, //configuracion de plugins
	storageManager: {}, //congiguracion de gaurdado #local o remota
	commands: {
		defaults: [
			{
				id: 'store-data',
				run(editor) {
					editor.store();
				},
			}
		]
	},
};

const defaultSotreManager = {
	id: 'gjs-',             // Prefix identifier that will be used inside storing and loading
	type: 'remote',          // Type of the storage
	autosave: false,         // Store data automatically
	autoload: true,         // Autoload stored data on init
	stepsBeforeSave: 1,     // If autosave enabled, indicates how many changes are necessary before store method is triggered
	storeComponents: true,  // Enable/Disable storing of components in JSON format
	storeStyles: true,      // Enable/Disable storing of rules in JSON format
	storeHtml: true,        // Enable/Disable storing of components as HTML string
	storeCss: true,         // Enable/Disable storing of rules as CSS string
	urlStore: '',
	urlLoad: '',
	params: {
		"_token": ''
	}, // Custom parameters to pass with the remote storage request, eg. CSRF token
	headers: {}, // Custom headers for the remote storage request
	noticeOnUnload: false //Mostrar o no alertas al refrescar o guardar la pagina
};

const defaultAssetsManager = {
	params: {
		"_token": ''
	},
}

const generalConfig = {
	name: 'General',
	open: false,
	buildProps: ["float", "display", "position", "top", "right", "left", "bottom"]
};

const extraConfig = {
	name: "Extra",
	open: false,
	buildProps: ["opacity", "transition"], //["opacity", "transition", "perspective", "transform"],
	properties: [
		{
			type: "slider",
			property: "opacity",
			defaults: 1,
			step: 0.01,
			max: 1,
			min: 0
		}
	]
};
