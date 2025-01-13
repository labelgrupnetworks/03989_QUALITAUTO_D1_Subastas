<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fxcli', function (Blueprint $table) {
            $table->string('gemp_cli', 2);
            $table->string('cod_cli', 8);
            $table->string('cod_c_cli', 4)->default('4300');
            $table->string('tipo_cli', 2)->default('0')->nullable();
            $table->string('rsoc_cli', 60)->nullable();
            $table->string('nom_cli', 60)->nullable();
            $table->string('sg_cli', 2)->nullable();
            $table->string('dir_cli', 30)->nullable();
            $table->string('cp_cli', 15)->nullable();
            $table->string('pob_cli', 30)->nullable();
            $table->string('pro_cli', 30)->nullable();
            $table->string('cif_cli', 20)->nullable();
            $table->string('tel1_cli', 40)->nullable();
            $table->string('tel2_cli', 40)->nullable();
            $table->string('baja_tmp_cli', 1)->default('N')->nullable();
            $table->date('f_alta_cli')->nullable();
            $table->date('f_modi_cli')->nullable();
            $table->date('f_pago_cli')->nullable();
            $table->date('f_ufra_cli')->nullable();
            $table->string('nom2_cli', 40)->nullable();
            $table->string('sg2_cli', 2)->nullable();
            $table->string('cp2_cli', 10)->nullable();
            $table->string('dir2_cli', 30)->nullable();
            $table->string('pob2_cli', 30)->nullable();
            $table->string('pro2_cli', 30)->nullable();
            $table->string('tel3_cli', 40)->nullable();
            $table->string('tel4_cli', 40)->nullable();
            $table->text('obs_cli')->nullable();
            $table->string('banco_cli', 30)->nullable();
            $table->string('dirb_cli', 30)->nullable();
            $table->string('entb_cli', 4)->nullable();
            $table->string('ofib_cli', 4)->nullable();
            $table->string('dcb_cli', 2)->nullable();
            $table->string('ctab_cli', 10)->nullable();
            $table->string('tarifa_cli', 1)->default('0')->nullable();
            $table->decimal('dtoc_cli', 5, 2)->default(0.0)->nullable();
            $table->decimal('dtopp_cli', 5, 2)->default(0.0)->nullable();
            $table->string('iva_cli', 1)->default('1')->nullable();
            $table->string('fpag_cli', 1)->default('0')->nullable();
            $table->unsignedTinyInteger('diap1_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('diap2_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('diap3_cli')->default(0)->nullable();
            $table->unsignedSmallInteger('pgir_cli')->default(0)->nullable();
            $table->unsignedSmallInteger('inter_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('ngir_cli')->default(0)->nullable();
            $table->string('estado_cli', 1)->default('N')->nullable();
            $table->string('contacto_cli', 60)->nullable();
            $table->string('repres_cli', 4)->nullable();
            $table->decimal('comi_cli', 5, 2)->default(0.0)->nullable();
            $table->string('local_cli', 40)->nullable();
            $table->decimal('rpvp_cli', 4, 2)->default(0.0)->nullable();
            $table->string('cod2_cli', 8)->nullable();
            $table->string('cod3_cli', 8)->nullable();
            $table->decimal('rap_cli', 4, 2)->default(0.0)->nullable();
            $table->string('seudo_cli', 100)->nullable();
            $table->string('banco2_cli', 30)->nullable();
            $table->string('dirb2_cli', 30)->nullable();
            $table->string('entb2_cli', 4)->nullable();
            $table->string('ofib2_cli', 4)->nullable();
            $table->string('dcb2_cli', 2)->nullable();
            $table->string('ctab2_cli', 10)->nullable();
            $table->decimal('ries_cli', 14, 2)->default(0.0)->nullable();
            $table->decimal('maxf_cli', 14, 2)->default(0.0)->nullable();
            $table->string('tipv_cli', 1)->nullable();
            $table->string('usr1_cli', 10)->nullable();
            $table->string('usr2_cli', 10)->nullable();
            $table->string('usr3_cli', 10)->nullable();
            $table->string('usr4_cli', 10)->nullable();
            $table->unsignedTinyInteger('diap12_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('diap22_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('diap32_cli')->default(0)->nullable();
            $table->unsignedSmallInteger('pgir2_cli')->default(0)->nullable();
            $table->unsignedSmallInteger('inter2_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('ngir2_cli')->default(0)->nullable();
            $table->string('fpag2_cli', 1)->default('0')->nullable();
            $table->string('cod4_cli', 8)->nullable();
            $table->string('pre1_cli', 1)->nullable();
            $table->string('pre2_cli', 1)->nullable();
            $table->string('pre3_cli', 1)->nullable();
            $table->string('pre4_cli', 1)->nullable();
            $table->string('email_cli', 260)->nullable();
            $table->unsignedTinyInteger('ncopias_cli')->default(1)->nullable();
            $table->unsignedTinyInteger('vac1_cli')->default(0)->nullable();
            $table->unsignedTinyInteger('vac2_cli')->default(0)->nullable();
            $table->string('zona_cli', 4)->nullable();
            $table->decimal('dtoc2_cli', 5, 2)->default(0.0)->nullable();
            $table->decimal('dtopp2_cli', 6, 2)->default(0.0)->nullable();
            $table->decimal('rap2_cli', 4, 2)->default(0.0)->nullable();
            $table->string('iban1_cli', 4)->nullable();
            $table->string('iban2_cli', 4)->nullable();
            $table->string('pais_cli', 50)->nullable();
            $table->string('ffac_cli', 4)->nullable();
            $table->string('codpais_cli', 3)->nullable();
            $table->string('ctaiban_cli', 34)->nullable();
            $table->string('ctaiban2_cli', 34)->nullable();
            $table->string('autodomic_cli', 1)->default('N')->nullable();
            $table->string('fecautodomic_cli', 12)->nullable();
            $table->string('fisjur_cli', 1)->nullable();
            $table->string('bic_cli', 11)->nullable();
            $table->string('bic2_cli', 11)->nullable();
			$table->string('docid_cli', 20)->nullable();
			$table->string('tdocid_cli', 2)->default('02')->nullable();
			$table->date('fcaddocid_cli')->nullable();
			$table->string('lugnac_cli', 30)->nullable();
			$table->string('codpainac_cli', 3)->nullable();
			$table->date('fecnac_cli')->nullable();
			$table->string('codpainad_cli', 3)->nullable();
			$table->string('sexo_cli', 1)->nullable();
			$table->string('envcorr_cli', 1)->default('N')->nullable();
			$table->string('lopd_cli', 1)->default('N')->nullable();
			$table->string('cnae_cli', 5)->nullable();
			$table->string('idioma_cli', 2)->default('ES')->nullable();
			$table->string('cod_div_cli', 4)->default('EUR')->nullable();
			$table->string('blockpuj_cli', 1)->default('N')->nullable();
			$table->string('ambassador_cli', 20)->nullable();
			$table->decimal('riesini_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('riesmax_cli', 14, 2)->default(0.0)->nullable();
			$table->string('preftel_cli', 4)->nullable();
			$table->string('revendedor_cli', 1)->default('N')->nullable();
			$table->string('profesion_cli', 100)->nullable();
			$table->string('trat_cli', 100)->nullable();
			$table->date('revendedorfec_cli')->nullable();
			$table->string('gastoscat_cli', 1)->default('N')->nullable();
			$table->string('seguro_cli', 1)->default('N')->nullable();
			$table->decimal('porccat_cli', 6, 2)->default(0.0)->nullable();
			$table->decimal('porcseguro_cli', 6, 2)->default(0.0)->nullable();
			$table->unsignedInteger('origen_cli')->nullable();
			$table->string('wallet_cli', 255)->nullable();
			$table->string('mtrans_cli', 1)->nullable();
			$table->string('portes_cli', 3)->nullable();
			$table->string('naturaleza_cli', 3)->nullable();
			$table->string('regestadistico_cli', 3)->nullable();
			$table->decimal('cubaereo_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('cubmaritimo_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('cubterrestre_cli', 14, 2)->default(0.0)->nullable();
			$table->unsignedBigInteger('tarnacional_cli')->nullable();
			$table->unsignedBigInteger('tarinternacional_cli')->nullable();
			$table->decimal('segurotar_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('minsegurotar_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('reembolso_cli', 14, 2)->default(0.0)->nullable();
			$table->decimal('minreembolso_cli', 14, 2)->default(0.0)->nullable();
			$table->string('maxreembolso_cli', 14, 2)->default(0.0)->nullable();
			$table->string('bancoemp_cli', 2)->nullable();
			$table->string('doccompleta_cli', 1)->default('N')->nullable();
			$table->string('siinacext_cli', 1)->nullable();
			$table->string('soloemail_cli', 1)->default('N')->nullable();
			$table->string('vip_cli', 1)->default('N')->nullable();
			$table->string('comerciante_cli', 1)->default('N')->nullable();
			$table->string('cta_cli', 12)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fxcli');
    }
};
