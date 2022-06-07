<?php

namespace App\Http\Controllers;

use App\Exports\ViewExcelExport;
use App\Providers\ToolsServiceProvider;
use App\Models\V5\FgAsigl0;
use App\Http\Controllers\V5\GaleriaArte;
use App\Models\V5\Web_Artist;
use App\Models\V5\FgSub;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel as Excel;

class CustomControllers extends Controller
{
	function exportPackengers($codSub)
	{
		$gemp = \Config::get('app.gemp');
		$dataForExport = FgAsigl0::select(
			" sub_asigl0  || '-' || ref_asigl0 as id",
			'ANCHO_HCES1 as length',
			'GRUESO_HCES1 as depth',
			'ALTO_HCES1 as height',
			"DES_UMED as metrics_unit",
			'IMPSALHCES_ASIGL0 as value',
			"'Eur' as currency",
			"'Aquí va photo_url' as photo_url",
			"'Aquí va lot_url' as lot_url",
			"NOM_CLI as owner_name",
			'DIR_ALM as picking_address',
			'CODPAIS_ALM as picking_country',
			'POB_ALM as picking_city',
			'CP_ALM as picking_zipcode',
			"nvl(DESCWEB_HCES1, TITULO_HCES1) || ' <br>' || DESC_HCES1 as description",
			'REF_ASIGL0 as lot_number',
			'DES_SUB as catalog_name',
			'SUB_ASIGL0 as calalog_reference',
			'"start" as catalog_date',
			'COD_SUB as cod_sub',
			'"id_auc_sessions" as id_auc_sessions',
			'"name" as name',
			'NUM_HCES1 as num_hces1',
			'WEBFRIEND_HCES1 as webfriend_hces1',
			'LIN_HCES1 as lin_hces1'
		)
			->joinFghces1Asigl0()
			->leftJoinAlm()
			->joinSubastaAsigl0()
			->joinSessionAsigl0()
			->leftjoin('FXCLI', "FXCLI.GEMP_CLI = $gemp AND FXCLI.COD_CLI = FGHCES1.PROP_HCES1")
			->join('FXUMED', "FXUMED.GEMP_UMED = $gemp AND FXUMED.COD_UMED = nvl(FGHCES1.ALTOUMED_HCES1, 1)")
			->where('SUB_ASIGL0', $codSub)
			->orderBy('lot_number', 'asc')
			->get();

		$fileName = "Packengers_" . $codSub;


		foreach ($dataForExport as $key => $value) {
			$url_friendly = \Tools::url_lot($value->cod_sub, $value->id_auc_sessions, $value->name, $value->lot_number, $value->num_hces1, $value->webfriend_hces1, $value->description);
			$dataForExport[$key]["lot_url"] = $url_friendly;
			$dataForExport[$key]["photo_url"] = \Tools::url_img('lote_medium', $value->num_hces1, $value->lin_hces1);

			#quitar código html en la descripción
			$dataForExport[$key]["description"] = strip_tags($value->description);

			/* Borrar variables innecesarias debajo del catalog_date */
			unset($dataForExport[$key]["cod_sub"]);
			unset($dataForExport[$key]["id_auc_sessions"]);
			unset($dataForExport[$key]["name"]);
			unset($dataForExport[$key]["num_hces1"]);
			unset($dataForExport[$key]["webfriend_hces1"]);
			unset($dataForExport[$key]["lin_hces1"]);
		}


		return ToolsServiceProvider::exportCollectionToExcel($dataForExport, $fileName);
	}



	public function excelExhibition($codSub, $reference)
	{
		$galeriaArte = new GaleriaArte();

		$fgsub = new Fgsub();
		$auction = $fgsub->getInfoSub($codSub, $reference);

		\Tools::exit404IfEmpty($auction);
		if ($auction->tipo_sub != 'E') {
			exit(\View::make('front::errors.404'));
		}

		$fgasigl0 = new FgAsigl0();

		$lots = $fgasigl0->select('FGHCES1.NUM_HCES1, FGHCES1.LIN_HCES1, IMPSALHCES_ASIGL0,  DESCWEB_HCES1, REF_ASIGL0,   DES_SUB, DFEC_SUB, HFEC_SUB,IDVALUE_CARACTERISTICAS_HCES1')
			->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '" . \Config::get("app.ArtistCode") . "'")
			->where("COD_SUB", $codSub)
			#ordenamos por orden, pero tambien tenemos en cuenta la referencia ya que por defecto el orden esta a nully rompia la ordenacion
			->ActiveLotAsigl0()->orderby("orden_hces1,ref_hces1")->get();


		$caracteristicasTmp = $fgasigl0->select('NUM_HCES1, LIN_HCES1, ID_CARACTERISTICAS, nvl(VALUE_CARACTERISTICAS_VALUE,VALUE_CARACTERISTICAS_HCES1 ) VALUE_CARACTERISTICAS')
			->LeftJoinCaracteristicasAsigl0()
			->where("COD_SUB", $codSub)
			->ActiveLotAsigl0()->get();
		$caracteristicas = array();

		foreach ($caracteristicasTmp as $caracteristica) {

			$numLin = $caracteristica->num_hces1 . "_" . $caracteristica->lin_hces1;
			if (empty($caracteristicas[$numLin])) {
				$caracterizsticas[$numLin] = array();
			}
			if ($caracteristica->id_caracteristicas == 1) {
				$caracteristicas[$numLin][$caracteristica->id_caracteristicas] = $galeriaArte->explodeComillas($caracteristica->value_caracteristicas);
			} else {
				$caracteristicas[$numLin][$caracteristica->id_caracteristicas] = $caracteristica->value_caracteristicas;
			}
		}
		#buscamos los artistas de la exposición
		$idArtists = [];
		foreach ($lots as $lot) {
			if (empty($idArtists[$lot->idvalue_caracteristicas_hces1])) {
				$idArtists[$lot->idvalue_caracteristicas_hces1] = $lot->idvalue_caracteristicas_hces1;
			}
		}

		$artists = [];

		if (count($idArtists) > 0) {
			$web_artist = new WEB_ARTIST();
			$artists = $web_artist->select("NAME_ARTIST, ID_ARTIST")->LeftJoinLang()->wherein("WEB_ARTIST.ID_ARTIST", $idArtists)->get();

			if (\Config::get("app.ArtistNameSurname")) {
				$artists =	 $galeriaArte->nameSurname($artists);
			}
		}

		$fileName = $codSub . '_Exhibition';

		$export = new ViewExcelExport($artists,$caracteristicas,$lots,$auction);
    	return Excel::download($export, "$fileName.xlsx");

	}
}
