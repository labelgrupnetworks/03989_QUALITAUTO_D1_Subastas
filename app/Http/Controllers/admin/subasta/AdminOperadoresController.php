<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Models\Enums\FgOrlicTipopEnum;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgSub;
use App\Models\V5\FsOperadores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOperadoresController extends Controller
{

	protected $availableColumns = [
		'sub_orlic' => 'Subasta',
		'licit_orlic' => 'Licitador',
		'rsoc_licit' => 'Razón Social',
		'ref_orlic' => 'Lote',
		'descweb_hces1' => 'Título',
		'phoneBiddingAgent.nom_operadores' => 'Operador',
		'tipop_orlic' => 'Tipo de puja',
	];

	protected $casts = [
		'sub_orlic' => 'string',
		'licit_orlic' => 'string',
		'rsoc_licit' => 'string',
		'ref_orlic' => 'float',
		'descweb_hces1' => 'blob',
		'phoneBiddingAgent.nom_operadores' => 'editable',
		'tipop_orlic' => FgOrlicTipopEnum::class,
	];

	/**
	 * Campos base para las consultas de órdenes telefónicas
	 */
	protected $baseSelectFields = [
		'sub_orlic',
		'licit_orlic',
		'ref_orlic',
		'lin_orlic',
		'himp_orlic',
		'tipop_orlic',
		'operador_orlic',
		'descweb_hces1',
		'rsoc_licit',
		'tel1_orlic',
		'tel2_orlic',
		'tel3_orlic',
	];


	/**
	 * Obtiene la subasta por código o una instancia vacía
	 */
	private function getSubasta(?string $cod_sub = null): FgSub
	{
		if (empty($cod_sub)) {
			return new FgSub();
		}

		return FgSub::where('cod_sub', $cod_sub)->first() ?? new FgSub();
	}

	/**
	 * Query base para órdenes telefónicas
	 */
	private function getPhoneOrdersQuery(string $cod_sub, array $additionalFields = []): \Illuminate\Database\Eloquent\Builder
	{
		$selectFields = array_merge($this->baseSelectFields, $additionalFields);

		return FgOrlic::query()
			->select($selectFields)
			->with(['phoneBiddingAgent'])
			->joinLicit()
			->joinAsigl0()
			->joinFghces1()
			->where('sub_orlic', $cod_sub)
			->whereIn('tipop_orlic', [
				FgOrlicTipopEnum::TELEFONO->value,
				FgOrlicTipopEnum::TELEFONO_WEB->value
			]);
	}

	public function index(Request $request, $cod_sub = null)
	{
		$fgSub = $this->getSubasta($cod_sub);

		//si estamos dentro de la subasta no cal mostrar la columna de subasta
		$availableColumns = $cod_sub
			? array_except($this->availableColumns, ['sub_orlic'])
			: $this->availableColumns;

		$phoneOrders = $this->getPhoneOrdersQuery($fgSub->cod_sub)
			->orderBy('ref_orlic')
			->paginate(25);

		$phoneBiddingAgents = FsOperadores::toSelect();

		// Retornar la vista con los operadores
		return view('admin::pages.subasta.operadores.index', [
			'fgSub' => $fgSub,
			'phoneOrders' => $phoneOrders,
			'phoneBiddingAgents' => $phoneBiddingAgents,
			'availableColumns' => $availableColumns,
			'casts' => $this->casts,
		]);
	}

	public function store(Request $request)
	{
		// Validar los datos del formulario
		$request->validate([
			'nom_operadores' => 'required|string|max:255',
		]);

		// Crear un nuevo operador
		FsOperadores::create([
			'cod_operadores' => FsOperadores::withoutGlobalScopes(['emp'])->max('cod_operadores') + 1, // Generar un nuevo código
			'nom_operadores' => $request->input('nom_operadores')
		]);

		return redirect()->back()
			->with('success', 'Operador creado correctamente.');
	}

	/**
	 * Imprimir las paletas de los operadores de la subasta.
	 * @param string $cod_sub
	 */
	public function printBidPaddles($cod_sub)
	{
		$bidPaddles = $this->getPhoneOrdersQuery($cod_sub)
			->addSelectHasMaxBidds()
			->addSelectIsMaxOrder()
			->orderBy('ref_orlic')
			->get();

		return view('admin::pages.subasta.operadores.paddles_print', [
			'bidPaddles' => $bidPaddles,
			'subasta' => $this->getSubasta($cod_sub),
		]);
	}


	/**
	 * Imprimir listado de ordenes por operador de la subasta.
	 */
	public function printBidPaddlesByOperator($cod_sub)
	{
		$bidPaddles = $this->getPhoneOrdersQuery($cod_sub)
			->addSelectHasMaxBidds()
			->addSelectIsMaxOrder()
			->whereNotNull('operador_orlic')
			->orderBy('operador_orlic')
			->orderBy('ref_orlic')
			->get();

		return view('admin::pages.subasta.operadores.list_bidder_phoneorders_print', [
			'bidPaddles' => $bidPaddles,
			'subasta' => $this->getSubasta($cod_sub),
		]);
	}

	/**
	 * Imprimir listado de ordenes por lote
	 */
	public function printBidPaddlesByReference($cod_sub)
	{
		$bidPaddles = $this->getPhoneOrdersQuery($cod_sub)
			->addSelectHasMaxBidds()
			->addSelectIsMaxOrder()
			->orderBy('ref_orlic')
			->get();

		return view('admin::pages.subasta.operadores.list_phoneorders_print', [
			'bidPaddles' => $bidPaddles,
			'subasta' => $this->getSubasta($cod_sub),
		]);
	}
}
