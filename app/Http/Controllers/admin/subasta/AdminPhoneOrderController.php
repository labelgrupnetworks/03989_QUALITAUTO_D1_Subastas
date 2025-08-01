<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Models\Enums\FgOrlicTipopEnum;
use Illuminate\Http\Request;
use App\Models\V5\FgOrlic;
use App\Models\V5\FgPrmSub;
use App\Models\V5\FgSub;

class AdminPhoneOrderController extends Controller
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

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
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

		// Retornar la vista con los operadores
		return view('admin::pages.subasta.operadores.index', [
			'fgSub' => $fgSub,
			'phoneOrders' => $phoneOrders,
			'availableColumns' => $availableColumns,
			'casts' => $this->casts,
		]);
	}

	public function store(Request $request, $cod_sub)
	{
		$validated = $request->validate([
			'phoneBiddingAgent' => 'required|string',
			'ref_orlic' => 'required|string',
			'lin_orlic' => 'required|string',
		]);

		$order = FgOrlic::query()
			->where([
				'sub_orlic' => $cod_sub,
				'ref_orlic' => $validated['ref_orlic'],
				'lin_orlic' => $validated['lin_orlic'],
			])->first();

		if (!$order) {
			return back()->withErrors(['error' => 'Orden no encontrada']);
		}

		$separationBetweenLots = FgPrmSub::getIntervalPhoneBiddingAgents();
		$existingOperators = FgOrlic::query()
			->joinAsigl0()
			->where([
				'sub_orlic' => $cod_sub,
				'operador_orlic' => $validated['phoneBiddingAgent'],
			])
			->where('licit_orlic', '!=', $order->licit_orlic)
			->whereBetween('ref_asigl0', [$validated['ref_orlic'] - $separationBetweenLots, $validated['ref_orlic'] - 1])
			->exists();

		if ($existingOperators) {
			return back()->withErrors(['error' => 'Ya existe un operador asignado en este rango de lotes']);
		}

		FgOrlic::query()
			->where([
				'sub_orlic' => $cod_sub,
				'ref_orlic' => $validated['ref_orlic'],
				'lin_orlic' => $validated['lin_orlic'],
			])
			->update(['operador_orlic' => $validated['phoneBiddingAgent']]);

		return back()->with(['success' => 'Agente de pujas telefónicas agregado correctamente']);
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
}
