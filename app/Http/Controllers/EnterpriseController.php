<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use App\Models\V5\FgOrtsec0;

class EnterpriseController extends Controller
{

        public function index(){

            $enterprise = new Enterprise();
            $profes = array();
            $especial = $enterprise->infEspecialistas();

            foreach($especial as $esp){
                $profes[$esp->lin_especial1][] = $esp;
            }

            $data['especialista'] = $profes;
             return \View::make('front::pages.specialists', array('data' => $data));
        }

        public function department($text){

			$fgOrtsec = new FgOrtsec0();
			$ortsec = $fgOrtsec->getInfoWithKeyOrtsec($text);

			if(empty($ortsec)){
				exit (\View::make('front::errors.404'));
			}

			//dd($departments);
			$enterprise = new Enterprise();
            $especialistas= array();
            $especial = $enterprise->infEspecialistas();

			foreach($especial as $esp){
                if($esp->lin_especial1 == $ortsec->lin_ortsec0){
                    $especialistas[$esp->per_especial1] = $esp;
                }
            }

			$data['seo'] = new \stdClass();
			$data['seo']->meta_title = $ortsec->meta_titulo_ortsec0 ?? trans(\Config::get('app.theme').'-app.head.title_app');
			$data['seo']->meta_description = $ortsec->meta_description_ortsec0 ?? trans(\Config::get('app.theme').'-app.head.meta_description');

			return \View::make('front::pages.department', compact('ortsec', 'especialistas', 'data'));
        }
}
