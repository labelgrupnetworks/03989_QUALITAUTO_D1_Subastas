<?php

namespace App\Http\Controllers\admin\subasta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\externalws\vottun\VottunController;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgNft;
use Illuminate\Http\Request;

class AdminNftController extends Controller
{

	public function __construct()
	{
		view()->share(['menu' => 'subastas']);
	}

	public function index(Request $request)
	{
		$nfts = FgAsigl0::select('sub_asigl0', 'ref_asigl0', 'numhces_asigl0', 'linhces_asigl0', 'descweb_hces1', 'himp_csub', 'base_csub', 'impsalhces_asigl0', 'licit_csub', 'prop_hces1')
			->addSelect('fxcli.nom_cli as buyer_nom_cli', 'fxcli.rsoc_cli', 'fxcli.email_cli', 'fxcli.cod_cli', 'fxcli.wallet_cli as buyer_wallet_cli')
			->addSelect('propcli.nom_cli as prop_nom_cli', 'propcli.wallet_cli as prop_wallet_cli')
			->addSelect('fgnft.*')
			->joinFghces1Asigl0()
			->joinCSubAsigl0()
			->leftJoinCliWithCsub()
			->leftJoinOwnerWithHces1('propcli')
			->joinNFT()
			->leftjoin('fgcsub0', 'fgcsub0.emp_csub0 = fgcsub.emp_csub and fgcsub0.apre_csub0 = fgcsub.apre_csub and fgcsub0.npre_csub0 = fgcsub.npre_csub')
			->leftjoin('fxcobro1', 'fxcobro1.emp_cobro1 = fgcsub.emp_csub and fxcobro1.afra_cobro1 = fgcsub.afral_csub and fxcobro1.nfra_cobro1 = fgcsub.nfral_csub')
			->where([
				['cerrado_asigl0', 'S'],
				['lic_hces1', 'S'],
			])
			->whereNotNull('hashfile_nft')
			->whereNotNull('clifac_csub')
			->where([
				['fxcobro1.lin_cobro1', '=', '1'],
				['fgcsub0.estado_csub0', '=', 'C', 'or'],
				['fgcsub.afral_csub', '=', 'L00', 'or'],
			])
			->orderBy('sub_asigl0', 'desc')->orderBy('ref_asigl0', 'asc')
			->paginate(30);

		foreach ($nfts as $nft) {
			$nft->has_all_wallets = $nft->buyer_wallet_cli && $nft->prop_wallet_cli;
			$nft->mint_state = $this->mintState($nft);
		}

		$tableParams = [
			'sub_asigl0' => 1,
			'ref_asigl0' => 1,
			'himp_csub' => 1,
			'buyer_nom_cli' => 1,
			'buyer_wallet_cli' => 1,
			'prop_nom_cli' => 1,
			'prop_wallet_cli' => 1,
		];

		return view('admin::pages.subasta.nfts.index', compact('nfts', 'tableParams'));
	}

	public function showFile($numhces_nft, $linhces_nft)
	{
		$nft = FgNft::where([
			'numhces_nft' => $numhces_nft,
			'linhces_nft' => $linhces_nft,
		])->first();


		if (!$nft || !$nft->path_nft) {
			abort(404);
		}

		$path = storage_path("app/$nft->path_nft");

		if (!file_exists($path)) {
			abort(404);
		}

		return response()->file($path);
	}

	private function mintState($nft)
	{
		if($nft->token_id_nft){
			return 'minted';
		}
		if(!$nft->mint_id_nft){
			return 'notminted';
		}
		if($nft->mint_id_nft && !$nft->token_id_nft){
			return 'minting';
		}
	}

	public function mint(Request $request)
	{
		$mintResult = (new VottunController())->mint($request->num, $request->lin);
		$mintResult->data = [
			'num' => $request->num,
			'lin' => $request->lin
		];
		return response()->json($mintResult);
	}

	public function transfer(Request $request)
	{
		$transferResult = (new VottunController())->transferNFT($request->num, $request->lin);
		$transferResult->data = [
			'num' => $request->num,
			'lin' => $request->lin
		];
		return response()->json($transferResult);
	}

	public function state(Request $request)
	{
		$response = (new VottunController())->requestStateMint($request->num, $request->lin);
		$response->data = [
			'num' => $request->num,
			'lin' => $request->lin
		];
		return response()->json($response);
	}
}
