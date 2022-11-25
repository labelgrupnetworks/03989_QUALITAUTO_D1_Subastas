<?php

namespace App\Exports;

use App\Models\V5\FxCli;
use App\Providers\ToolsServiceProvider;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;


class ClientsExport extends StringValueBinder implements FromQuery, WithHeadings, ShouldAutoSize, WithCustomValueBinder, WithMapping
{

	use Exportable;

	public function __construct(Request $request)
	{
		$this->order = $request->order;
		$this->order_dir = $request->order_dir;

		//cambiar el select por solo los campos activos en la vista
		$this->select = collect($request->selects)->filter(function ($value, $key) {
			return $value /* && $key != 'envcat_cli2' */;
		});

		$this->where = $request;
	}

	public function query()
	{
		$newslettersSelect = collect(explode(',', config('app.newsletterFamilies', ' ')))->map(function ($item) {
			return "nllist" . trim($item) . "_cliweb";
		});

		$clientes = FxCli::with('tipoCli')->with('cli2')
			->leftJoinCliWebCli()
			//->select($this->select->keys()->implode(','))
			->select('*')

			//para relacion con cli2
			->when($this->where->envcat_cli2, function ($query, $envcat_cli2) {
				return $query->whereHas('cli2', function ($query) use ($envcat_cli2) {
					return $query->where('envcat_cli2', $envcat_cli2);
				});
			})

			->when($this->where['cod_cli'], function ($query, $cod_cli) {
				return $query->where('cod_cli', 'like', "%" . $cod_cli . "%");
			})
			->when($this->where['cod2_cli'], function ($query, $cod2_cli) {
				return $query->where('upper(cod2_cli)', 'like', "%" . mb_strtoupper($cod2_cli) . "%");
			})
			->when($this->where['tipo_cli'], function ($query, $tipo_cli) {
				return $query->where('tipo_cli', $tipo_cli);
			})
			->when($this->where['nom_cli'], function ($query, $nom_cli) {
				return $query->where('upper(nom_cli)', 'like', "%" . mb_strtoupper($nom_cli) . "%");
			})
			->when($this->where['rsoc_cli'], function ($query, $rsoc_cli) {
				return $query->where('upper(rsoc_cli)', 'like', "%" . mb_strtoupper($rsoc_cli) . "%");
			})
			->when($this->where['email_cli'], function ($query, $email_cli) {
				return $query->where('upper(email_cli)', 'like', "%" . mb_strtoupper($email_cli) . "%");
			})
			->when($this->where['tel1_cli'], function ($query, $tel1_cli) {
				return $query->where('upper(tel1_cli)', 'like', "%" . mb_strtoupper($tel1_cli) . "%");
			})
			->when($this->where['pais_cli'], function ($query, $pais_cli) {
				return $query->where('upper(pais_cli)', 'like', "%" . mb_strtoupper($pais_cli) . "%");
			})
			->when($this->where['pro_cli'], function ($query, $pro_cli) {
				return $query->where('upper(email_cli)', 'like', "%" . mb_strtoupper($pro_cli) . "%");
			})
			->when($this->where['idioma_cli'], function ($query, $idioma_cli) {
				return $query->where('upper(idioma_cli)', 'like', "%" . mb_strtoupper($idioma_cli) . "%");
			})
			->when($this->where['fisjur_cli'], function ($query, $fisjur_cli) {
				return $query->where('fisjur_cli', $fisjur_cli);
			})
			->when($this->where['fecalta_cliweb'], function ($query, $fecalta_cliweb) {
				return $query->where('fecalta_cliweb', '>=', ToolsServiceProvider::getDateFormat($fecalta_cliweb, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($this->where['f_modi_cli'], function ($query, $f_modi_cli) {
				return $query->where('f_modi_cli', '>=', ToolsServiceProvider::getDateFormat($f_modi_cli, 'Y-m-d', 'Y/m/d') . ' 00:00:00');
			})
			->when($this->where['baja_tmp_cli'], function ($query, $baja_tmp_cli) {
				return $query->where('baja_tmp_cli', $baja_tmp_cli);
			});

		//newsletters
		foreach ($newslettersSelect as $value) {
			$clientes = $clientes->when($this->where[$value], function ($query, $nllist_cliweb) use ($value) {
				return $query->where($value, $nllist_cliweb);
			});
		}

		return $clientes->where('cod_cli', '!=', 9999)
			->orderBy($this->order ?? 'cast(fxcli.cod_cli as int)', $this->order_dir ?? 'desc');
	}

	public function map($clientes): array
	{
		$values = [
			$clientes->rn,
		];

		foreach ($this->select->keys() as $key) {

			switch ($key) {
				case 'envcat_cli2':
					$values[] = $clientes->cli2->envcat_cli2 ?? null;
					break;

				case 'tipo_cli':
					$values[] = $clientes->tipoCli->des_tcli ?? null;
					break;

				default:
					$values[] = $clientes[$key];
					break;
			}
		}

		return $values;
	}
	/* public function query()
	{

		$clients = FxCli::
			leftJoinCliWebCli()
			->select('cod_cli', 'cif_cli', 'tel1_cli', 'sg_cli', 'dir_cli', 'cp_cli', 'pob_cli', 'pro_cli', 'pais_cli', 'nllist1_cliweb')
			->addSelect("NVL(nom_cli, nom_cliweb) as nom_cli")
			->addSelect("NVL(email_cli, email_cliweb) as email_cli");

		if(Config::get('app.exportcli_profesion', 0)){
			$clients->addSelect('seudo_cli');
		}

		return $clients->orderBy('cod_cli', 'desc');
	} */

	public function headings(): array
	{
		$fields = [trans('admin-app.fields.rn')];
		foreach ($this->select as $key => $field) {
			$fields[] = trans("admin-app.fields.$key");
		}

		return $fields;
	}
}
