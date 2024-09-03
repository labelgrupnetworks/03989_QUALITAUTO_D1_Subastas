<?php

namespace App\libs;

use Illuminate\Support\Facades\Config;

class FormLib
{


	static function Hidden($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "")
	{

		$aux = "<input type='hidden' name='" . $strNombre . "' data-placement='right' id='" . $strNombre . "' value='" . $strValue . "' " . $strExtra . " autocomplete='off'>";
		return $aux;
	}

	static function Text($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = '<input type="text" class="form-control effect-16" name="' . $strNombre . '" id="texto__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . 'data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">';

		return $aux;
	}

	static function TextReadOnly($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{
		$aux = '<input type="text" class="form-control effect-16" name="' . $strNombre . '" id="texto__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . 'data-placement="right" placeholder="' . $placeholder . '" autocomplete="off" readonly>';
		return $aux;
	}

	static function TextModalList($strNombre, $modalId, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		return '
		<div class="row">
			<div class="col-xs-2">
				<a href="#" title="Buscar" class="btn btn-primary" data-toggle="modal" data-list="'. $strNombre .'" data-target="#' . $modalId . '"><i class="fa fa-edit"></i></a>
			</div>
			<div class="col-xs-2">
				<a href="#" title="Buscar" class="btn btn-danger" id="' . $strNombre . '_delete"><i class="fa fa-trash"></i></a>
			</div>
			<div class="col-xs-8">
				<input type="hidden" class="form-control effect-16" name="' . $strNombre . '" id="texto__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '">
				<input readonly type="text" class="form-control effect-16" name="' . $strNombre . '_name" id="texto__' . $boolObligatorio . '__' . $strNombre . '_name" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . 'data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">
			</div>
		</div>
		';
	}

	static function modalToList($modalId){
		return '
		<div class="modal fade" id="' . $modalId . '" tabindex="-1" role="dialog" aria-labelledby="codSubModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title" id="editRatingModalTitle">Selecciona</h5>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  </button>
			</div>
			<div class="modal-body">
				<div class="loader-spinner" style="display:flex;">
					<div id="nprogress" style="margin: 0 5px;">
						<div calss="spinner">
							<div class="spinner-icon"></div>
						</div>
					</div>
					<p style="margin: 0 5px;">Cargando...</p>
				</div>
				<div class="form-group">
				  <label for="newRating">Opciones</label>
				  <div class="input-group col-xs-8">
					<select class="form-control" name="' . $modalId . '_select" id="' . $modalId . '_select">
					</select>
				  </div>
				</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			  <button type="button" id="seleccionar" class="btn btn-primary">Seleccionar</button>
			</div>
		  </div>
		</div>
	  </div>
		';
	}

	static function Email($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = '<input type="text" class="form-control effect-16" name="' . $strNombre . '" id="email__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . ' data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">';

		return $aux;
	}

	static function Nif($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = '<input type="text" class="form-control effect-16" name="' . $strNombre . '" id="nif__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . ' data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">';

		return $aux;
	}

	static function Password($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = '
				<img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">

				<input type="password" class="form-control effect-16" name="' . $strNombre . '" id="password__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . ' data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">

		';

		return $aux;
	}

	static function Int($strNombre, $boolObligatorio = 0, $strValue, $strExtra = "", $placeholder = "", $class="text-center")
	{

		$aux = '<input type="text" class="form-control  effect-16 '.$class.'" name="' . $strNombre . '" id="numero__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" data-placement="right"  ' . $strExtra . ' autocomplete="off">';

		return $aux;
	}

	static function Float($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = '<input type="text" class="form-control effect-16" name="' . $strNombre . '" id="decimal__' . $boolObligatorio . '__' . $strNombre . '" value="' . $strValue . '" onblur="comprueba_campo(this)" ' . $strExtra . ' data-placement="right" placeholder="' . $placeholder . '" autocomplete="off">';

		return $aux;
	}

	static function Readonly($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		$aux = "<input readonly class='form-control effect-16' type='text' name='readonly__" . $strNombre . "' id='" . $boolObligatorio . "__" . $strNombre . "' value='" . $strValue . "' onblur='comprueba_campo(this)' data-placement='right' " . $strExtra . " autocomplete='off'>";

		return $aux;
	}

	static function Textarea($strNombre, $boolObligatorio = false, $strValue = '', $strExtra = "", $placeholder = "", $rows = "10")
	{

		//$strValue=str_replace("<br>","\n",$strValue);

		$aux = "<textarea class='form-control effect-16' name='" . $strNombre . "' rows=$rows id='textogrande__" . $boolObligatorio . "__" . $strNombre . "' onblur='comprueba_campo(this)' " . $strExtra . " data-placement='right' placeholder='" . $placeholder . "' autocomplete='off'>" . $strValue . "</textarea>";

		return $aux;
	}

	static function TextAreaTiny($strNombre, $boolObligatorio = false, $strValue = '', $strExtra = "", $placeholder = "", $height = 300, $multiple = false)
	{
		$aux = "";
		//cuando es un array de inputs, que tengan el mismo id provoca errores
		if(!$multiple){
			$aux .= "<textarea class='form-control effect-16 tiny-textarea' name='" . $strNombre . "' rows=10 id='textogrande__" . $boolObligatorio . "__" . $strNombre . "' onblur='comprueba_campo(this)' " . $strExtra . " data-placement='right' placeholder='" . $placeholder . "' autocomplete='off'>" . $strValue . "</textarea>";
		}
		else{
			$aux .= "<textarea class='form-control effect-16 tiny-textarea' name='" . $strNombre . "' rows=10 " . $strExtra . " data-placement='right' placeholder='" . $placeholder . "' autocomplete='off'>" . $strValue . "</textarea>";
		}

		$aux .= "<script>
				window.addEventListener('load', function(){
					tinymce.init({
		  				selector: '.tiny-textarea',
						convert_urls: true,
						height: $height,
						max_height: 600,
						menu: {
							file: { title: 'File', items: ' restoredraft | preview | print ' },
							edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace | edit' },
							view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
							insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking toc | insertdatetime' },
							format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
							tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
							table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
							help: { title: 'Help', items: 'help' }
						},
						plugins: 'advlist autolink link image lists code table wordcount fullscreen hr preview visualblocks print autoresize paste save',
						toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
						'bullist numlist outdent indent | link image | media fullpage | ' +
						'forecolor backcolor | print pastetext | fullscreen preview',
					});
				});
	  			</script>";
		return $aux;
	}

	static function TextAreaSummer($strNombre, $boolObligatorio = false, $strValue = '', $strExtra = "", $placeholder = "", $height = 300, $toolBarOptions = "all")
	{
		/**
		 * codemirror: {theme: 'monokai',mode: 'text/html',lineNumbers: true,tabMode: 'indent',prettifyHtml: false},
		 */

		$toolbar = self::getSummernoteToolbarOption($toolBarOptions);

		$aux = "<textarea class='form-control effect-16' name='" . $strNombre . "' rows=10 id='textogrande__" . $boolObligatorio . "__" . $strNombre . "' onblur='comprueba_campo(this)' " . $strExtra . " data-placement='right' placeholder='" . $placeholder . "' autocomplete='off'>" . $strValue . "</textarea>";
		$aux .= "<script>
		$(() => {
			$('#textogrande__" . $boolObligatorio . "__" . $strNombre . "').summernote({
				toolbar: [$toolbar],
				height: '$height',
				lang: 'es-ES',
				codemirror: { theme: 'ambiance' }
		  	});
		});
	  	</script>";
		return $aux;
	}

	private static function getSummernoteToolbarOption($toolBarOptions)
	{
		if($toolBarOptions == 'all'){
			return "
			['style', ['style']],
			['font', ['bold', 'italic', 'underline', 'clear']],
			['fontsize', ['fontsize']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen', 'codeview', 'help', 'undo', 'redo']],
			['height', ['height']]
			";
		}

		return "
		['style', ['style']],
		['font', ['bold', 'italic', 'underline', 'clear']],
		['fontsize', ['fontsize']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
		['table', ['table']],
		['insert', ['link']],
		['view', ['codeview', 'help', 'undo', 'redo']],
		['height', ['height']]
		";

	}

	static function SummernoteArea($idSummer, $nameInput, $boolObligatorio = false, $strValue = '')
	{
		$aux = "<div name='' id='$idSummer' class='summernote' data-plugin-summernote data-plugin-options='{ 'height': 200, 'codemirror': { 'theme': 'ambiance' } }' placeholder='{{ trans('admin-app.placeholder.consulta') }}' class='form-control' rows='5'>
                            $strValue
                    </div>
                    <input class='inputSummer_$nameInput' id='textogrande__" . $boolObligatorio . "__" . $nameInput . "' type='hidden' name='$nameInput' value='$strValue'>";

		return $aux;
	}

	static function File($strNombre, $boolObligatorio = 0, $strExtra = "")
	{
		$maxFileSize = min(ini_get('upload_max_filesize'), ini_get('post_max_size'));

		$aux = "<input class='form-control effect-16' type='file' data-placement='right' name='" . $strNombre . "' id='file__" . $boolObligatorio . "__" . $strNombre . "' " . $strExtra . " autocomplete='off'>";
		$aux .= "<small>". trans('admin-app.general.max_file_size', ['size' => $maxFileSize]) . "</small>";

		return $aux;
	}

	static function FileWithValue($strNombre, $boolObligatorio = 0, $strExtra = "", $value = '')
	{
		$aux = self::File($strNombre, $boolObligatorio, $strExtra);

		if(!empty($value)){
			//si quisiera mostar el nombre del archivo
			//$name = explode('/', $value);
			//$name = end($name);

			$aux .= "<p><a href='" . url("$value") ."' target='_blank'>Archvio actual</a></p>";
		}

		return $aux;
	}

	static function Bool($strNombre, $boolObligatorio = 0, $checked = 0, $strValue = 0, $strExtra = "")
	{

		$checked = (bool) ($checked);

		$aux = "<span><input type='checkbox' " . ($checked ? 'checked=\'checked\'' : '') . " name='" . $strNombre . "' value='" . $strValue . "' id='bool__" . $boolObligatorio . "__" . $strNombre . "' " . $strExtra . " autocomplete='off' data-placement='bottom'></span>";

		return $aux;
	}

	static function Date($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $placeholder = "")
	{

		if ($strValue) {
			$strValue = date("Y-m-d", strtotime($strValue));
		}

		$aux = "";
		$aux .= '
					<input type="date" id="fecha__' . $boolObligatorio . '__' . $strNombre . '" data-placement="right" name="' . $strNombre . '" class="form-control effect-16" onblur="comprueba_campo(this)" value="' . $strValue . '" ' . $strExtra . ' />
		';


		return $aux;
	}

	static function DateTime($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = ""){

		if ($strValue) {
			$strValue = date("Y-m-d\TH:i", strtotime($strValue));
		}

		$aux = "";
		$aux .= '<input type="datetime-local" id="fecha__' . $boolObligatorio . '__' . $strNombre . '" data-placement="right" name="' . $strNombre . '"
			class="form-control effect-16"
			onblur="comprueba_campo(this)"
			value="' . $strValue . '" ' . $strExtra . ' />';

		return $aux;

	}

	static function DateTimeFromTo($strNombre, $fromValue = '', $toValue = '')
	{
		$fromValue = ($fromValue) ? date("Y-m-d", strtotime($fromValue)) : '';
		$toValue = ($toValue) ? date("Y-m-d", strtotime($toValue)) : '';

		$html = "<input type=\"date\" id=\"fecha__0__from_$strNombre\" data-placement=\"right\" name=\"from_{$strNombre}\" class=\"form-control effect-16\" onblur=\"comprueba_campo(this)\" value=\"$fromValue\"/>";
		$html .= "<input type=\"date\" id=\"fecha__0__to_$strNombre\" data-placement=\"right\" name=\"to_{$strNombre}\" class=\"form-control effect-16\" onblur=\"comprueba_campo(this)\" value=\"$toValue\"/>";
		return $html;
	}

	static function DateTimePicker($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = "", $onLoad = false)
	{
		$strValue = ($strValue) ? date("Y-m-d H:i:s", strtotime($strValue)) : now();

		$aux = "";
		$aux .= '<input type="text" id="fecha__' . $boolObligatorio . '__' . $strNombre . '" data-placement="right" name="' . $strNombre . '"
			class="form-control effect-16 form_datetime"
			onblur="comprueba_campo(this)"
			value="' . $strValue . '" ' . $strExtra . ' />';


		if($onLoad){
			$aux .= '<script> $(document).ready(function () { $(".form_datetime").datetimepicker({format: \'yyyy-mm-dd hh:ii:ss\'}); });</script>';
			return $aux;
		}

		$aux .= '<script> $(".form_datetime").datetimepicker({format: \'yyyy-mm-dd hh:ii:ss\'});</script>';
		return $aux;
	}

	static function Hour($strNombre, $boolObligatorio = 0, $strValue = '', $strExtra = '')
	{

		if ($strValue) {
			$strValue = date("H:i", strtotime($strValue));
		}

		$aux = '
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<div class="input-group date" id="fecha__' . $boolObligatorio . '__' . $strNombre . '">
							<input type="time"  name="' . $strNombre . '" class="form-control effect-16" onblur="comprueba_campo(this)" value="' . $strValue . '" ' . $strExtra . '/>
							<span class="input-group-addon">
								<span class="fa fa-clock"></span>
							</span>
						</div>
					</div>
				</div>
			</div>
		';

		return $aux;
	}

	static function Select($strNombre, $boolObligatorio = false, $strValue ='' , $mixOptions = array(), $strExtra = "", $placeholder = "", $void_value = true)
	{

		$aux = "<select data-placement='right' class='form-control select2' type='select' class='input-lg' name='" . $strNombre . "' id='select__" . $boolObligatorio . "__" . $strNombre . "' onblur='comprueba_campo(this)' " . $strExtra . " >";
		if($void_value){
			$aux .= "<option value=''>" . $placeholder . "</option>";
		}

		foreach ($mixOptions as $k => $v) {
			$aux .= '<option value="' . $k . '"';

			if (is_array($strValue) && in_array($k,$strValue ))  {
				$aux .= " selected='selected'";
			}
			if (!is_array($strValue) && $k == $strValue) {
				$aux .= " selected='selected'";
			}
			$aux .= ">" . $v . "</option>";
		}

		$aux .= "</select>";

		return $aux;
	}

	static function Select2($strNombre, $boolObligatorio = false, $strValue = '', $strValueHtml = ''){

		$aux =
		"<select id='select__" . $boolObligatorio . "__" . $strNombre . "' onblur='comprueba_campo(this)' class='form-control' name='" . $strNombre . "'>
			<option value='". $strValue ."' selected='selected'>". $strValue . " - " . $strValueHtml ."</option>
		</select>";


		return $aux;
	}


	static function Select2WithArray($strNombre, $boolObligatorio = false, $strValue = null, $array = [], $void_value = true, $isMultiple = false)
	{

		$id = $boolObligatorio . "__" . $strNombre;

		$aux = "<select id='select__$id' onblur='comprueba_campo(this)' class='form-control' name='$strNombre";

		//en caso de ser multiple añadimos [] al name y el atributo multiple
		$aux .= $isMultiple ? "[]' multiple='multiple'>" :"'>";

		if($void_value){
			$aux .= "<option value=''></option>";
		}

		foreach ($array as $key => $value) {

			$aux .= "<option value='$key' ";
			if ( (!$isMultiple && ($strValue ?? '') == $key) || ($isMultiple && is_array($strValue) && in_array($key, $strValue)) ) {
				$aux .= "selected='selected'";
			}

			$aux .= ">{$key}-{$value}</option>";
		}

		$aux .= "</select>";
		$aux .= "<script>$('#select__$id').select2();";
		$aux .= "$('#select__$id').on('change', function() {var data = $('#select__$id option:selected').val();$('#test').val(data);});</script>";

		return $aux;
	}

	static function Select2WithAjax($strNombre, $boolObligatorio = false, $strValue = '', $strValueHtml = '', $ruta = '', $placeholder = '', $modalId = '')
	{
		if ($modalId) $modalId = "dropdownParent: $('#".$modalId."'),";

		$id = $boolObligatorio . "__" . $strNombre;
		$aux = "<select id='select__" . $id . "' onblur='comprueba_campo(this)' class='form-control' name='" . $strNombre . "' style='width: 100%;'>";
		$aux .= "<option value='". $strValue ."' selected='selected'>". $strValue . " - " . $strValueHtml ."</option>";

		$aux .= "</select>";
		$aux .= "<script>$('#select__" . $id . "').select2({
			placeholder: '" .$placeholder ."',
			minimumInputLength: 3,
			language: 'es',
			" . $modalId . "
			ajax: {
				url: `$ruta`,
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					return {
						results: $.map(data, function (item) {
							return {
								text: item.id + ' - ' + item.html,
								id: item.id
							}
						})
					};
				},
				cache: true
			}
		});";
		$aux .= "</script>";

		return $aux;
	}
	static function SelectWithCountries($strNombre, $strValue = '', $array = [], $isDisabled = false)
	{
		$path = DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "icons" . DIRECTORY_SEPARATOR . "flags" . DIRECTORY_SEPARATOR;

		$html = "<div class='row'><div class='col-sm-12'><div class='form-group'><select class='selectpicker form-control' name='$strNombre'>";
		foreach ($array as $id => $value) {

			$pathImages = $path . strtolower($id) . ".svg";
			$publicPath = public_path($path . strtolower($id) . ".svg");
			if(!file_exists($publicPath)){
				$pathImages = $path . "xx.svg";
			}

			$html .= "<option value='$id' data-content='<img loading=\"lazy\" style=\"width: 20px;margin-right: 5px\" src=\"$pathImages\"/> <span>$value</span>'";

			if($strValue == $id){
				$html .= " selected='true'";
			}
			if($isDisabled){
				$html .= " disabled";
			}



			$html .= " </option>";
		}

		$html .= "</select></div></div></div>";

		return $html;
	}


	static function Submit($strValue = "Enviar", $strFormId)
	{

		$aux = '<a onclick="javascript:submit_form(document.getElementById(\'' . $strFormId . '\'),0);" class="button-principal submitButton btn btn-lb-primary">' . $strValue . '</a>';

		return $aux;
	}


	static function Image($text, $boolObligatorio = 0)
	{

		$aux = '<div id="dropzone" class="imagen__' . $boolObligatorio . '">';
		$aux .= '<div class="color-letter text-dropzone">' . $text . '</div>';
		$aux .= '<div class="mini-file-content d-flex align-items-center" style="position:relative"></div>';
		$aux .= '<input id="images" type="file" name="images[]" />';
		$aux .= '</div>';

		return $aux;
	}

	static function SelectRange($name, $id, $inputClass = '', $minRange, $minValue, $maxRange, $maxValue)
	{
		$minSelection = !empty($minValue) ? $minValue : $minRange;
		$maxSelection = !empty($maxValue) ? $maxValue : $maxRange;

		$aux = '<div class="slider-wrapper custom-ui-slider" style="position: relative" id="selectrange_'. $name .'">';
		$aux .=		'<span class="slider-min" id="slider_'. $id .'_0">' . number_format($minRange, 0, ',', '.') .'</span>';
		$aux .=		'<div class="slider-range"></div>';
		$aux .=		'<span class="slider-max" id="slider_'. $id .'_1">' . number_format($maxRange, 0, ',', '.') .'</span>';
		$aux .=		'<span class="slider-value" id="slider_'. $id .'_value">' . $maxRange .'</span>';
		$aux .=	'</div>';
		$aux .= '<input type="hidden" name="'. $name .'[]" value="'. $minValue .'" class="'. $inputClass .'" id="'. $id .'_0">';
		$aux .= '<input type="hidden" name="'. $name .'[]" value="'. $maxValue .'" class="'. $inputClass .'" id="'. $id .'_1">';

		$aux .= '<script>
		$(document).ready(function(){
			let container = document.getElementById(`selectrange_'. $name .'`);

			$(container.querySelector(`.slider-range`)).slider({
				range: true,
				min: '. $minRange .',
				max: '. $maxRange .',
				values: [ '. $minSelection .', '. $maxSelection .' ],
				slide: function( event, ui) {

				  let value = ui.values[ui.handleIndex];
				  let element = ui.handle;

				  let slideValue = document.getElementById(`slider_'. $id .'_value`);
				  slideValue.style.display = `block`;
				  slideValue.style.left = (element.offsetLeft + 9 - (slideValue.offsetWidth / 2)) + `px`;

				  document.querySelectorAll(`[name="'. $name .'[]"]`)[ui.handleIndex].value = value;
				  slideValue.innerHTML = value;
				},
				stop: function( event, ui) {
					let slideValue = document.getElementById(`slider_'. $id .'_value`);
					slideValue.style.display = `none`;
				}

		  });
		});
	  </script>';

	  return $aux;

	}


	static function Link($text, $url)
	{

		$aux = "<p><a href=\"$url\" target=\"_blank\"> $text</a></p>";

		return $aux;
	}









	/****************************************************************************************************/
	//
	//  GetForm - Recogemos los campos de una tabla y autogeneramos los elementos de formulario referentes a los campos dfe la tabla
	//
	//	@table 	-	Tabla a partir de la cual obtendremos la estructura de nuestros campos
	//	@pk 	-	Primary Key de un registro. Si existe este campo rellenaremos el fomulario con el valor de este registro
	//	@lang 	-	Parametro para tener en cuenta el lenguage
	//
	//****************************************************************************************************/

	static function GetForm($table, $pk = false, $lang = false)
	{

		$items = array();
		$form = array();
		$values = array();
		$field_data = \DB::select("SELECT * FROM user_tab_cols WHERE table_name = :tabla ", array("tabla" =>strtoupper($table) ));

		if (!$lang) {
			$lang = "";
		} else {
			$lang = "_" . $lang;
		}

		foreach ($field_data as $field) {
			$items[$field->column_name] = $field;
		}

		// Obtenemos los valores del registro en base al $pk
		if ($pk) {
			$obj = \DB::table($table);
			foreach ($pk as $k => $v) {
				$obj = $obj->where($k, $v);
			}
			$values = $obj->first();
		}

		foreach ($items as $column => $item) {

			if ($item->nullable == 'Y')
				$required = "0";
			else
				$required = "1";

			if (isset($values->{strtolower($column)})) {
				$value = $values->{strtolower($column)};
			} else {
				$value = "";
			}

			if ($lang) {
				$item->column_name = $item->column_name . $lang;
			}

			if ($pk && in_array($column, array_keys($pk))) {
				$form[$item->column_name] = FormLib::Hidden($item->column_name, $required, $value);
			} elseif ($item->data_type == "VARCHAR2") {
				$form[$item->column_name] = FormLib::Text($item->column_name, $required, $value);
			} elseif ($item->data_type == "CLOB") {
				echo $required;
				$form[$item->column_name] = FormLib::TextArea($item->column_name, $required, $value);
			}
		}

		$form['SUBMIT' . $lang] = FormLib::Submit("Guardar", "form" . $table . $lang);

		return $form;
	}


	/****************************************************************************************************/
	//
	//  getFields - Existen algunos campos de fomulario que son muy típicos. Ej: nombre, email,...
	// 			  Esta función pretenede simplificar el código para genear estos campos, de forma que con
	// 			  1 sola llamada obtengamos lo que necesitamos.
	//
	//	@fields -	Lista de campos a obtener
	//
	/****************************************************************************************************/

	static public function getFields($fields = null)
	{

		if (empty($fields)) {
			return false;
		}
		if (!is_array($fields)) {
			$fields = explode(",", $fields);
		}

		$theme = Config::get('app.theme');

		$formulario = array('_token' => array("formulario" => Formlib::hidden("_token", 1, csrf_token()), "type" => "Hidden", "mandatory" => 1));

		foreach ($fields as $item) {
			$item=trim($item);
			if ($item == "email" || $item == "mail")
				$formulario['email'] = array("formulario" => FormLib::Email("email", 1, "", "", trans("$theme-app.global.$item")), "type" => "Email", "mandatory" => 1);

			if ($item == "nombre")
				$formulario["nombre"] = array("formulario" => FormLib::Text("nombre", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "apellidos")
				$formulario["apellidos"] = array("formulario" => FormLib::Text("apellidos", 0, ""), "type" => "Text", "mandatory" => 0);

			if ($item == "telefono" || $item == "phone" || $item == "tel")
				$formulario["telefono"] = array("formulario" => FormLib::Text("telefono", 1, "", "", trans("$theme-app.global.$item")), "type" => "Text", "mandatory" => 1);

			if ($item == "description" || $item == "desc" || $item == "descripcion")
				$formulario["descripcion"] = array("formulario" => FormLib::Textarea("descripcion", 1, ''), "type" => "TextArea", "mandatory" => 1);

			if ($item == "mensaje" ){
					$formulario["mensaje"] = array("formulario" => FormLib::Textarea("mensaje", 0, ''), "type" => "TextArea", "mandatory" => 0);
			}
			if (substr($item, 0, 5) == "image" || substr($item, 0, 6) == "imagen")
				$formulario["imagen"] = array("formulario" => FormLib::Image(trans(\Config::get('app.theme') . '-app.global.imagen')), "type" => "Image", "mandatory" => 0);

			if (substr($item, 0, 4) == "file" ){
				$formulario[$item] = array("formulario" => FormLib::File("files[]",1), "type" => "File", "mandatory" => 1);
			}

			if ($item == "nif" || $item == "cif")
				$formulario["nif"] = array("formulario" => FormLib::Text("nif", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "profesion")
				$formulario["profesion"] = array("formulario" => FormLib::Text("profesion", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "direccion")
				$formulario["direccion"] = array("formulario" => FormLib::Text("direccion", 1, ""), "type" => "Text", "mandatory" => 1);


			if ($item == "poblacion")
				$formulario["poblacion"] = array("formulario" => FormLib::Text("poblacion", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "cp")
				$formulario["cp"] = array("formulario" => FormLib::Text("cp", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "provincia")
				$formulario["provincia"] = array("formulario" => FormLib::Text("provincia", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "pais")
				$formulario["pais"] = array("formulario" => FormLib::Text("pais", 1, ""), "type" => "Text", "mandatory" => 1);

			if ($item == "nomApell")
				$formulario["nomApell"] = array("formulario" => FormLib::Text("nomApell", 1, "", "", trans("$theme-app.global.$item")), "type" => "Text", "mandatory" => 1);

			if ($item == "precio")
				$formulario["precio"] = array("formulario" => FormLib::Float("precio", 1, ""), "type" => "Text", "mandatory" => 1);

		}

		return $formulario;
	}
}
