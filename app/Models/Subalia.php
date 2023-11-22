<?php

# Ubicacion del modelo
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Config;


class Subalia extends Model
{



    public function getTokenByLicit($codLicit)
    {
		$emp = config::get('app.APP_SUBALIA_EMP', '001');
        $gemp = config::get('app.APP_SUBALIA_GEMP', '01');

        $sql = "SELECT FXCLIWEB.TK_CLIWEB FROM FGLICIT
                JOIN FXCLI   ON (FXCLI.COD_CLI = FGLICIT.CLI_LICIT)
                JOIN FXCLIWEB  ON ( FXCLIWEB.GEMP_CLIWEB = FXCLI.GEMP_CLI AND FXCLIWEB.EMP_CLIWEB = FGLICIT.EMP_LICIT AND  FXCLIWEB.COD_CLIWEB = FGLICIT.CLI_LICIT )

                WHERE
                    FGLICIT.EMP_LICIT = :emp
                AND FXCLI.GEMP_CLI = :gemp
                AND FGLICIT.SUB_LICIT = :cod_sub
                AND FGLICIT.COD_LICIT = :licit  ";

        $bindings = array(
                        'emp'           => $emp,
                        'gemp'           => $gemp,
                        'cod_sub'       => '0',
                        'licit'         => $codLicit,
                        );


        $tk = DB::connection('subalia')->select($sql, $bindings);

        if(!empty($tk)){
            return head($tk)->tk_cliweb;
        }

        return null;

    }
}
