<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;

/**
 * @todo Eloy: 29/08
 * Solamente se utiliza en grid de lotes antiguos y de estos
 * solo se esta utilizando por Sorolla.
 * Plantearse utilizar un config para no perjudicar el rendimiento del resto.
 */
class Filters extends Model
{

    public function getFiltersDescription ($cod_sub){
		$sql="select col_subfw,  oti.\"language\", oti.\"description_column\" from FGSUBFW  SUBFW
		JOIN \"object_types_info\" oti on oti.\"id_column\" = SUBFW.col_subfw
		where emp_subfw = :emp and sub_subfw= :cod_sub ";

		$filtersDescription = DB::select($sql, array("emp" =>Config::get('app.emp'), "cod_sub" => $cod_sub ));
        $names= array();
        foreach($filtersDescription as $filter){
            if(!isset($names[$filter->language])){
                $names[$filter->language] = array();
            }
            $names[$filter->language][$filter->col_subfw] = $filter->description_column;
        }

        return $names;
    }

    public function getFiltersAuction($cod_sub){
        $sql="select col_subfw from FGSUBFW where emp_subfw = :emp and sub_subfw= :cod_sub ";
        return DB::select($sql, array("emp" =>Config::get('app.emp'), "cod_sub" => $cod_sub ));
    }
    //generará un listado con los campos que son filtros, la tabla object_types_values deberá renombrarse otv
    public function getFiltersAuctionForQuery($cod_sub){
        $columnsDB = $this->getFiltersAuction($cod_sub);
        $columns="";
        $coma="";
        foreach($columnsDB as $column){

            $columns.=$coma.'  TRIM(NVL(otv_lang."'. $column->col_subfw  .'_lang",  otv."'. $column->col_subfw  .'")) "'. $column->col_subfw  .'" ';
             $coma="," ;
        }

        return $columns;
    }
    //generamos los datos de para un filtro, filters input contiene las usquedas actuales en la web, y filter_name el filtro que vamos a cargar
    public function getFilterAuctionForSelector($type, $cod_sub,$id_auc_sessions, $category, $subcategory, $filter_name, $filters_input){



        $key_cache= "getFilterAuctionForSelector_".$type."_".$cod_sub."_".$id_auc_sessions."_". $category."_". $subcategory."_". $filter_name."_".\Tools::getLanguageComplete(Config::get('app.locale')). print_r($filters_input, true);
        $filters = \CacheLib::getCache($key_cache);
        if($filters === false){
           // ahora solo se buscará un filtro
            //$select_filter= $this->getFiltersAuctionForQuery($cod_sub);


            $subasta = new Subasta();
            $subasta->select_filter = '  TRIM(NVL(otv_lang."'. $filter_name  .'_lang",  otv."'. $filter_name .'")) "'. $filter_name .'" ';
            $subasta->join_filter = ' JOIN "object_types_values" otv on ( otv."company" =  HCES1.emp_HCES1 and  otv."transfer_sheet_number" = HCES1.NUM_HCES1 AND  otv."transfer_sheet_line" = HCES1.lin_HCES1)';
            $subasta->join_filter .= ' LEFT JOIN "object_types_values_lang" otv_lang on ( otv_lang."company_lang" =  HCES1.emp_HCES1 and  otv_lang."transfer_sheet_number_lang" = HCES1.NUM_HCES1 AND  otv_lang."transfer_sheet_line_lang" = HCES1.lin_HCES1 AND otv_lang."lang_object_types_values_lang" = :lang)';

            //que haya almenos un valor en algun idioma
            $subasta->where_filter .=' AND(  otv."'. $filter_name .'" is not null OR otv_lang."'. $filter_name  .'_lang" is not null)';
            if(!empty($type)){
                $auction_in_categories =  \Config::get('app.auction_in_categories');
                $subasta->where_filter .=" AND SUB.TIPO_SUB  IN ($auction_in_categories) AND ASIGL0.CERRADO_ASIGL0 = 'N' ";
            }else{
				$subasta->where_filter = " AND \"id_auc_sessions\" =  :id_auc_sessions";
				$subasta->params_filter["id_auc_sessions"] = $id_auc_sessions;
            }

            //filtramos por subcategoria para que solo salgan los que pertenezcan a esta subcategoria
            if(!empty($subcategory)){
				$subasta->where_filter .= " AND (HCES1.SEC_HCES1= :subcategory)";
				$subasta->params_filter["subcategory"] = $subcategory;
            }
            //filtramos por categoria para que solo salgan los que pertenezcan a esta categoria
            if(!empty($category) && is_numeric($category)){
				$subasta->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = :category)";
				$subasta->params_filter["category"] = $category;
                //los tipo categoria o temática tienen sub_ortsec a 0
                if(!empty($type)){
                  $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";
                }else{
                  $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = :cod_sub ) ";
				  $subasta->params_filter["cod_sub"] = $cod_sub;
                }

			}
			$index_filter = 1;
            //usar los filtros que ha escogido el usuario para limitar los resultados
            foreach($filters_input as $key_filter => $value_filter){
                //debe filtrar por los filtros elegidos por el usuario, pero no por el mismo filtro que estoy sacando
                if($key_filter != $filter_name){
					$subasta->where_filter  .= ' AND TRIM(NVL(otv_lang."'. $key_filter  .'_lang",  otv."'. $key_filter  .'"))  = :value_filter_'.$index_filter ;
					$subasta->params_filter["value_filter_".$index_filter] = $value_filter;
				}
				$index_filter++;
            }

            $subasta->order_by_values = ' "'.$filter_name.'"';
			$object_types_values= $subasta->getLots("small",true);

            $filters= array();
            foreach($object_types_values as $values){
                $val = $values->{$filter_name};
                if(!empty($val)){
                    if(!array_key_exists($val,$filters)){
                        $filters[$val] = 1;
                    }else{
                        $filters[$val]++;
                    }
                }
            }

            \CacheLib::putCache($key_cache, $filters, 10);
        }

        return $filters;
    }


    public function getAllFilterSelector($type, $cod_sub,$id_auc_sessions, $category, $subcategory){
         //si es una subasta de categorias o tematica
        if(!empty($type)){
           $cod_sub = 0;
           $sec_obj =  new \App\Models\Sec();

           if(!empty($category)){
               $ortsec = $sec_obj->getOrtsecByKey('0',$category);
               $category = $ortsec->lin_ortsec0;
           }else{
                $category = NULL;
           }

           if(!empty($subcategory)){
               $sec = $sec_obj->getSecByKey($subcategory);
               $subcategory = $sec->cod_sec;
           }else{
               $subcategory = NULL;
           }

        }

        //saber que filtros ha realizado el usuario
        $val_inputs = array();

        $filters_auction = $this->getFiltersAuction($cod_sub);

        //hacemos head para cohger los del primer idioma
        foreach($filters_auction as $filter_name){
           if (!empty(app('request')->input($filter_name->col_subfw.'_select'))){
               $val_inputs[$filter_name->col_subfw] = app('request')->input($filter_name->col_subfw.'_select');
           }
        }

        $filters = array();
        foreach($filters_auction as $filter_name){
            $filters[$filter_name->col_subfw] = $this->getFilterAuctionForSelector($type, $cod_sub, $id_auc_sessions,$category,$subcategory,$filter_name->col_subfw, $val_inputs);
        }

        return $filters;

    }

}
