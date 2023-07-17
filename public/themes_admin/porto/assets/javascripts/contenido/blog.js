/**
 * Las siguiente variables proceden de la vista
 * @param {array} subSectionsArray
 * @param {array} images
 * @param {grapesjs} editor
 */

class Logger {
	static logger = PNotify;

	static error(message) {
		new this.logger({
			title: 'Error',
			text: message,
			type: 'error'
		});
	}

	static success(message) {
		new this.logger({
			title: 'Saved',
			text: message,
			type: 'success'
		});
	}
}

class BlogService {

	BASE_URL = '/admin/contenido/content';
	isLoading = false;

	constructor({ events }) {
		this.events = events || {};
		return this;
	}

	newContentBlock({ id, type, type_rel }) {

		const url = `${this.BASE_URL}/${id}/block`;

		return this.#postData(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				type,
				type_rel
			})
		});
	}

	updateHTMLContentBlock({ id, id_content, type_rel, html }) {

		const url = `${this.BASE_URL}/${id}/block/${id_content}`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				type_rel,
				html
			})
		});
	}

	updateIframeContentBlock({ id, id_content, type_rel, url_iframe }) {
		const url = `${this.BASE_URL}/${id}/block/${id_content}`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				type_rel,
				url_iframe
			})
		});
	}


	deleteContentBlock({ id, id_content, type_rel = 'WEB_BLOG_LANG' }) {

		const url = `${this.BASE_URL}/${id}/block/${id_content}`;

		return this.#postData(url, {
			method: 'DELETE',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				_token: document.querySelector('[name=_token]')?.value,
				type_rel
			})
		});
	}

	updateImage(id, formData) {

		const url = `/admin/contenido/blog/${id}/image`;

		return this.#postData(url, {
			method: 'POST',
			body: formData
		});
	}

	changeEnabledStatus({ id, newStatus }) {

		const url = `/admin/contenido/blog/${id}/enabled`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				_token: document.querySelector('[name=_token]')?.value,
				isEnabled: newStatus
			})
		});
	}

	changeOrder({ id, id_content, type_rel, direction }) {

		const url = `${this.BASE_URL}/${id}/block/${id_content}/order`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				type_rel,
				direction
			})
		});
	}

	setResource({ id, id_content, formData }) {

		const url = `${this.BASE_URL}/${id}/block/${id_content}/resource`;

		return this.#postData(url, {
			method: 'POST',
			body: formData
		});
	}

	createCategory({ formData }) {
		const url = `/admin/contenido/blog-category`;

		return this.#postData(url, {
			method: 'POST',
			body: formData
		});
	}

	updateOrderCategories({ categories }) {
		const url = `/admin/contenido/blog-category/order`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				categories
			})
		});
	}

	getEditCategory(id) {
		const url = `/admin/contenido/blog-category/${id}/edit`;

		return this.#postData(url, {
			method: 'GET',
			headers: { 'Content-Type': 'application/json' },
		});
	}

	updateCategory({ id, formData }) {
		const url = `/admin/contenido/blog-category/${id}`;

		return this.#postData(url, {
			method: 'POST',
			body: formData
		});
	}

	changeCategoryEnabledStatus({ id, newStatus }) {

		const url = `/admin/contenido/blog-category/${id}/enabled`;

		return this.#postData(url, {
			method: 'PUT',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				_token: document.querySelector('[name=_token]')?.value,
				isEnabled: newStatus
			})
		});
	}

	deleteCategory(id) {
		const url = `/admin/contenido/blog-category/${id}`;

		return this.#postData(url, {
			method: 'DELETE',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				_token: document.querySelector('[name=_token]')?.value,
			})
		});
	}

	#postData(url, options = {}) {

		if (this.isLoading) {
			return;
		}

		if (this.events.start) {
			this.events.start();
		}

		this.isLoading = true;

		return fetch(url, options)
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(responseJson => {
				if (responseJson.status === 'error') {
					throw new Error(responseJson.message);
				}
				return responseJson;
			})
			.finally(() => {

				this.isLoading = false;
				if (this.events.end) {
					this.events.end();
				}
			});
	}
}

const blogService = new BlogService({
	events: { start: initLoading, end: endLoading }
});

const getHtmlBlock = (layout) => {
	const LOREM_TITLE = 'Lorem ipsum dolor';
	const LOREM = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.';

	const html = {
		'1': `<div class="container-fluid"><div class="row"><div class="col col-xs-12"><h3>${LOREM_TITLE}</h3><p>${LOREM}</p></div></div></div>`,
		'2': `<div class="container"><div class="row"><div class="col col-xs-12"><h3>${LOREM_TITLE}</h3><p>${LOREM}</p></div></div></div>`,
		'3': `<div class="container"><div class="row"><div class="col col-xs-12 col-md-9"><h3>${LOREM_TITLE}</h3><p>${LOREM}</p></div><div class="col col-xs-12 col-md-3"><h3>${LOREM_TITLE}</h3><p>${LOREM}</p></div></div></div>`,
	}

	return html[layout];
}

function initLoading() {
	$('.modal').modal('hide');
	$('#loadMe').data('bs.modal', null);
	$("#loadMe").modal({
		backdrop: 'static', //remove ability to close modal with click
		keyboard: false, //remove option to close with keyboard
		show: true //Display loader!
	});
}

function endLoading() {
	$('#loadMe').modal('hide');
}

$('#layout-modal').on('show.bs.modal', function (event) {
	const button = $(event.relatedTarget);
	const relId = button.data('relId');
	const typeContent = button.data('typeContent');

	//chenge data in modal
	this.querySelectorAll('.js-btn-layout').forEach(function (button) {
		button.dataset.relId = relId;
		button.dataset.typeContent = typeContent;
	});
});

$('#edit-block-modal').on('show.bs.modal', function (event) {
	const button = $(event.relatedTarget);
	const id = button.data('id');
	const relId = button.data('relId');
	const blockHtml = document.querySelector(`.content_${id}`).querySelector('.html-block').innerHTML;

	$('[name=id_content_page_modal]').val(id);
	$('[name=rel_id]').val(relId);

	initEditor();
	editor.setComponents(blockHtml);

	const framework = document.querySelector('[name="css_styles"]')?.value;
	editor.getComponents().add(framework, {
		at: 0
	});
});

$('#text-modal').on('show.bs.modal', function (event) {
	const button = $(event.relatedTarget);
	const id = button.data('id');
	const relId = button.data('relId');
	const blockHtml = document.querySelector(`.content_${id}`).querySelector('.html-block').innerHTML;

	tinymce.activeEditor.setContent(blockHtml);

	$('[name=id_content_page_modal]').val(id);
	$('[name=rel_id]').val(relId);

});

$('#modal_message').on('hidden.bs.modal', function (e) {
	$('#modal_message').find('.modal-footer').html(`
		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	`);
});

$('#banner-modal').on('hidden.bs.modal', reloadPage);

$('[name="sec[]"]').on('change', handleChangeSecSelect);
$('[name=url_img]').on('change', updateBlogImage);
$('.js-add-new-block').on('click', addNewBlock);
$('.js-btn-layout').on('click', addNewBlock);

function handleChangeSecSelect(event) {
	const sections = $(event.target).val();

	const subSectionsSelectig = getSubSections(sections);

	const options = getOptions(subSectionsSelectig);

	//guardamos las selecciones actuales
	const actualSelection = $('[name="sub_categ[]"]').val();

	//borramos todas las opciones y cargamos las posibles
	$('[name="sub_categ[]"]').empty().append(options).trigger('change');

	//volvemos a seleccionar las opciones que estaban seleccionadas
	//si había una opción que no está en las posibles, no se seleccionará
	$('[name="sub_categ[]"]').val(actualSelection).trigger('change');
}

function getSubSections(sections) {
	return sections && subSectionsArray.filter(subSection => {
		return sections.includes(subSection.lin_ortsec1);
	}) || [];
}

function getOptions(subSectionsSelectig) {
	return subSectionsSelectig.map(subSection => {
		return new Option(`${subSection.cod_sec}- ${subSection.des_ortsec0}`, subSection.cod_sec, false, false)
	})
}

$('.js-add-iframe').on('click', addNewIframeBlock);

function addNewIframeBlock(event) {
	bootbox.prompt('Añadir dirección url de la página', function (result) {
		if (!result) {
			return;
		}
		event.target.dataset.url = result;
		addNewBlock(event);
	});
}

async function addNewBlock(event) {

	const type_rel = 'WEB_BLOG_LANG';
	const button = event.currentTarget;
	const type = button.dataset.typeContent;
	const id = button.dataset.relId;

	let response;
	try {
		response = await blogService.newContentBlock({ id, type, type_rel });
	} catch (error) {
		Logger.error(error.message);
		return;
	}

	const actionsToType = {
		'HTML': () => {
			const layout = button.dataset.layout;
			const html = getHtmlBlock(layout);
			blogService.updateHTMLContentBlock({ id, id_content: response.data.id_content_page, type_rel, html })
				.then(reloadPage)
				.catch(error => Logger.error(error.message));

		},
		'TEXT': reloadPage,
		'BANNER': () => addBlockBanner(id, response.data.id_content_page),
		'IMAGE': reloadPage,
		'VIDEO': reloadPage,
		'IFRAME': () => {
			const url_iframe = button.dataset.url;
			blogService.updateIframeContentBlock({ id, id_content: response.data.id_content_page, type_rel, url_iframe })
				.then(reloadPage)
				.catch(error => Logger.error(error.message));
		},
	}

	actionsToType[type]();
}

function addBlockBanner(id, id_conent) {

	const iframe = $('#content-frame');
	const url = new URL('/admin/newbanner/nuevo', window.location.origin);
	url.searchParams.append('to_frame', 1);
	url.searchParams.append('ubicacion', 'BLOG');
	url.searchParams.append('rel_id', id);
	url.searchParams.append('id_content', id_conent);

	iframe.attr('src', url);

	$('#banner-modal').modal('show');
}

function editBlockBanner(rel_id, id_conent, type_id) {

	//Si no tiene type_id significa que se ha creado el bloque pero no se ha llegado a crear el banner
	if (!type_id) {
		addBlockBanner(rel_id, id_conent);
		return;
	}

	const iframe = $('#content-frame');
	const url = new URL(`/admin/newbanner/editar/${type_id}`, window.location.origin);
	url.searchParams.append('to_frame', 1);

	iframe.attr('src', url);
	$('#banner-modal').modal('show');
}

function deleteBlock(id, id_content) {
	$('#modal_message').find('.modal-body').html('<p>¿Está seguro de que desea eliminar el bloque?</p>');
	$('#modal_message').find('.modal-footer').html(`
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
		<button type="button" class="btn btn-danger" onclick="deleteBlockConfirm(${id}, ${id_content})">Eliminar</button>
	`);
	$('#modal_message').modal('show');
}

function deleteBlockConfirm(id, id_content) {

	const type_rel = 'WEB_BLOG_LANG';
	blogService.deleteContentBlock({ id, id_content, type_rel })
		.then(reloadPage)
		.catch(error => Logger.error(error.message));
}

function saveTextEditor() {
	const html = tinymce.activeEditor.getContent();
	const id_content = $('[name=id_content_page_modal]').val();
	const type_rel = 'WEB_BLOG_LANG';
	const id = $('[name=rel_id]').val();

	blogService.updateHTMLContentBlock({ id, id_content, type_rel, html })
		.then(reloadPage)
		.catch(error => Logger.error(error.message));
}

function saveHtmlBlock() {
	editor.getComponents().at(0).remove();

	const html = saveEditor(editor);
	const id_content = $('[name=id_content_page_modal]').val();
	const type_rel = 'WEB_BLOG_LANG';
	const id = $('[name=rel_id]').val();

	editor.destroy();
	editor = null;

	$('#edit-block-modal').modal('hide');

	blogService.updateHTMLContentBlock({ id, id_content, type_rel, html })
		.then(reloadPage)
		.catch(error => Logger.error(error.message));
}

function initEditor() {

	if (editor) {
		return;
	}

	const id = $('[name=id]').val();
	const url = `/admin/contenido/content/${id}/assets`;

	editor = grapesjs.init({
		...defaultGrapeOptions,
		storageManager: null,
		assetManager: {
			...defaultAssetsManager,
			assets: images,
			upload: url,
			params: {
				"_token": document.querySelector('[name=_token]')?.value,
				'id': $('[name=rel_id]').val(),
				'type_rel': 'WEB_BLOG_LANG'
			}
		}
	});

	grapeOnLoad(editor);
}

function updateBlogImage(e) {
	const formData = new FormData();
	formData.append("file", e.target.files[0]);
	formData.append("_token", $('input[name="_token"]').val());

	const id = $('[name=id]').val();

	if (!id) {
		return;
	}

	blogService.updateImage(id, formData)
		.then(response => {
			const pathToFile = response.data;

			//const isImage = pathToFile.match(/\.(jpeg|jpg|gif|png|webp|svg)$/) != null;
			const isImage = pathToFile.match(/\.(jpe?g|png|gif|svg|webp)$/i) != null;

			$('.front-wrapper').empty();

			if (isImage) {
				$('.front-wrapper').append(`<img src="${pathToFile}" alt="blog image" />`);
			} else {
				$('.front-wrapper').append(`<video src="${pathToFile}" controls></video>`);
			}
			Logger.success(response.message);

		})
		.catch(error => Logger.error(error.message));
}

function changeOrder(id, id_content, direction) {

	const type_rel = 'WEB_BLOG_LANG';

	const isChanged = changeOrderInDom(id_content, direction);
	if (!isChanged) {
		Logger.error('No se puede cambiar el orden');
		return;
	}

	blogService.changeOrder({ id, id_content, type_rel, direction })
		.then(response => {
			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function changeOrderInDom(id_content, direction) {
	const block = $(`.content-block[data-id=${id_content}]`);

	//Si es el primer bloque y se quiere subir o el último y se quiere bajar, no se puede
	if ((direction === 'up' && !block.prev().length) || (direction === 'down' && !block.next().length)) {
		return false;
	}

	const prevBlock = block.prev();
	const nextBlock = block.next();

	if (direction === 'up') {
		prevBlock.before(block);
	} else {
		nextBlock.after(block);
	}

	const parent = block.closest('.tab-pane');
	const orderPositions = parent.find('.js-order-position');
	orderPositions.each(function (index) {
		$(this).text(index + 1);
	});

	return true;
}

/**
 * @param {HTMLButtonElement} buttonElement
 */
async function handleClickChangeEnabledStatus(buttonElement) {

	const id = $('[name=id]').val() || buttonElement.dataset.id;
	const actualStatus = buttonElement.dataset.isEnabled !== 'false';
	const newStatus = !actualStatus;

	blogService.changeEnabledStatus({ id, newStatus }).
		then(response => {
			buttonElement.dataset.isEnabled = newStatus;
			buttonElement.classList.toggle('btn-success');
			buttonElement.classList.toggle('btn-danger');
			if (buttonElement.dataset.disabledMessage) {
				buttonElement.innerText = newStatus ? buttonElement.dataset.disabledMessage : buttonElement.dataset.enabledMessage;
			}
			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function handleClickShowPage() {
	$('#modal_message').find('.modal-body').html('<p>Para poder visitar la página debe tener fecha de publicación y url validos</p>');
	$('#modal_message').modal('show');
}

function dragNdrop(event) {

	if (event.target.files.length === 0) {
		return;
	}

	const formData = new FormData();
	const file = event.target.files[0];
	formData.append("file", file);
	formData.append("_token", $('input[name="_token"]').val());
	formData.append("type_rel", 'WEB_BLOG_LANG');

	const type = event.target.dataset.typeContent;
	formData.append("type", type);

	const id = event.target.dataset.relId;
	const id_content = event.target.dataset.idContent;

	blogService.setResource({ id, id_content, formData })
		.then(response => {
			const content = document.querySelector(`.content_${id_content}`);
			content.querySelector('.uploadOuter').classList.add('d-none');

			if (type === 'IMAGE') {
				content.querySelector('.image-wrapper').classList.remove('d-none');
				//content.querySelector('img').setAttribute('src', response.data);
				content.querySelector('img').setAttribute('src', URL.createObjectURL(file));
			}
			else {
				content.querySelector('.video-wrapper').classList.remove('d-none');
				content.querySelector('video').setAttribute('src', URL.createObjectURL(file));
			}

			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function drag(input) {
	input.parentNode.className = 'draging dragBox';
}
function dropOrLeave(input) {
	input.parentNode.className = 'dragBox';
}

$('#form-category').on('submit', handleSubmitNewCategory);
$('#form-category-update').on('submit', handleUpdateCategory);
$('.js-edit-category').on('click', handleEditCategory);

function handleSubmitNewCategory(event) {
	event.preventDefault();

	const formData = new FormData(event.target);

	blogService.createCategory({ formData })
		.then(response => {
			const category = response.data.category;
			const navs = document.querySelector('#categoires-navs');

			const li = createButtonCategory(category.id_category_blog, category.title_category_blog);

			navs.insertBefore(li, navs.querySelector('.js-add-new-category').parentNode);

			$('#category-modal').modal('hide');
			$('#form-category [name]').val('');

			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function createButtonCategory(id, name) {
	const li = document.createElement('li');
	li.setAttribute('role', 'presentation');
	li.setAttribute('data-id', id);
	li.classList.add('categories-items', 'btn-group-xs', 'btn');

	li.innerHTML = `
        	<button class="btn btn-link js-soratble-button"><i class="fa fa-reorder"></i></button>
			<a href="#tab_empty" aria-controls="${name}" role="tab" data-toggle="tab">
				${name}
			</a>
			<button class="btn btn-link js-edit-category" onclick="handleEditCategory(event)"><i class="fa fa-edit fa-2x"></i></button>
			`;

	return li;
}

function handleEditCategory(event) {
	const button = event.currentTarget;
	const buttonGroup = button.closest('li');
	const id = buttonGroup.dataset.id;
	const name = buttonGroup.querySelector('a').innerText;

	blogService.getEditCategory(id)
		.then(({ data }) => {
			const modal = $('#category-edit-modal');
			modal.find('#category-title').text(name);

			const $form = modal.find('form');
			$form.find('.form-content').html(data.html);
			$form.attr('data-id', id);

			modal.modal('show');
		})
		.catch(error => Logger.error(error.message));
}

function handleUpdateCategory(event) {
	event.preventDefault();

	const formData = new FormData(event.target);
	const id = event.target.dataset.id;

	blogService.updateCategory({ id, formData })
		.then(response => {
			const navs = document.querySelector('#categoires-navs');
			const li = navs.querySelector(`li[data-id="${id}"]`);
			li.querySelector('a').innerText = response.data.category.title_category_blog;

			$('#category-edit-modal').modal('hide');

			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function hadleChangeEnabledCategory(event) {
	const button = event.currentTarget;
	const id = button.closest('li').dataset.id;
	const actualStatus = button.dataset.isEnabled !== 'false';
	const newStatus = !actualStatus;

	blogService.changeCategoryEnabledStatus({ id, newStatus }).
		then(response => {
			button.dataset.isEnabled = newStatus;
			button.querySelector('i').classList.toggle('fa-eye');
			button.querySelector('i').classList.toggle('fa-eye-slash');
			Logger.success(response.message);
		})
		.catch(error => Logger.error(error.message));
}

function handleDeleteCategory(event) {
	const button = event.currentTarget;
	const id = button.closest('li').dataset.id;

	bootbox.confirm({
		message: "¿Está seguro de que desea eliminar la categoría?",
		buttons: {
			confirm: {
				label: 'Eliminar',
				className: 'btn-danger'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-secondary'
			}
		},
		callback: function (result) {
			if (!result) {
				return;
			}

			blogService.deleteCategory(id)
				.then(response => {
					const navs = document.querySelector('#categoires-navs');
					const li = navs.querySelector(`li[data-id="${id}"]`);
					li.remove();
					Logger.success(response.message);
				})
				.catch(error => Logger.error(error.message));
		}
	});
}


$('#categoires-navs').sortable({
	handle: '.js-soratble-button',
	items: '.categories-items',
	axis: 'y',
	cursor: "grabbing",
	cancel: '',
	update: function (event, ui) {
		const order = $(this).sortable('toArray', { attribute: 'data-id' });
		const orderModels = order.map((id_category_blog, index) => {
			return {
				id_category_blog,
				order: index + 1
			}
		});

		blogService.updateOrderCategories({ categories: orderModels })
			.then(response => Logger.success(response.message))
			.catch(error => Logger.error(error.message));
	}
});

function reloadPage() {
	location.reload();
}
