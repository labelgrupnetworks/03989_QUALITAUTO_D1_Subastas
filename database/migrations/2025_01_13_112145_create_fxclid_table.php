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
        Schema::create('fxclid', function (Blueprint $table) {
            $table->string('gemp_clid', 2);
            $table->string('cli_clid', 8);
            $table->string('codd_clid', 4);
            $table->string('nomd_clid', 60)->nullable();
            $table->string('sg_clid', 2)->nullable();
            $table->string('dir_clid', 30)->nullable();
            $table->string('cp_clid', 10)->nullable();
            $table->string('pob_clid', 30)->nullable();
            $table->string('pro_clid', 30)->nullable();
            $table->string('tel1_clid', 40)->nullable();
            $table->string('tel2_clid', 40)->nullable();
            $table->string('tipo_clid', 2)->nullable();
            $table->string('cargo_clid', 30)->nullable();
            $table->string('obs_clid', 40)->nullable();
            $table->string('sec_clid', 2)->nullable();
            $table->string('codcli_clid', 8)->nullable();
            $table->string('rsoc_clid', 50)->nullable();
            $table->string('pais_clid', 50)->nullable();
            $table->string('email_clid', 80)->nullable();
            $table->string('var1_clid', 10)->nullable();
            $table->string('var2_clid', 10)->nullable();
            $table->string('var3_clid', 10)->nullable();
            $table->string('codd2_clid', 2)->nullable();
            $table->string('rsoc2_clid', 40)->nullable();
            $table->string('pre1_clid', 1)->default('N');
            $table->string('pre2_clid', 1)->default('N');
            $table->string('cod2_clid', 2)->nullable();
            $table->string('dir2_clid', 40)->nullable();
            $table->string('zona_clid', 4)->nullable();
            $table->string('ven_clid', 4)->nullable();
            $table->string('refcli_clid', 20)->nullable();
            $table->string('poper_clid', 20)->nullable();
            $table->string('codpro_clid', 20)->nullable();
            $table->string('baja_temp_clid', 1)->default('N');
            $table->date('fecb_clid')->nullable();
            $table->string('usrb_clid', 10)->nullable();
            $table->string('trans_clid', 3)->nullable();
            $table->string('banco_clid', 30)->nullable();
            $table->string('dirb_clid', 30)->nullable();
            $table->string('entb_clid', 4)->nullable();
            $table->string('ofib_clid', 4)->nullable();
            $table->string('dcb_clid', 2)->nullable();
            $table->string('ctab_clid', 10)->nullable();
            $table->string('iban1_clid', 4)->nullable();
            $table->string('ctaiban_clid', 32)->nullable();
            $table->string('autodomic_clid', 1)->nullable();
            $table->string('fpag_clid', 1)->nullable();
            $table->unsignedTinyInteger('vac1_clid')->nullable();
            $table->unsignedTinyInteger('diap1_clid')->nullable();
            $table->unsignedTinyInteger('diap2_clid')->nullable();
            $table->unsignedTinyInteger('diap3_clid')->nullable();
            $table->unsignedSmallInteger('pgir_clid')->nullable();
            $table->unsignedSmallInteger('ngir_clid')->nullable();
            $table->unsignedSmallInteger('inter_clid')->nullable();
            $table->string('codpais_clid', 3)->default('ES');
            $table->string('catal_clid', 1)->default('N');
            $table->string('mater_clid', 1)->default('N');
            $table->string('factu_clid', 1)->default('N');
            $table->string('vario_clid', 1)->default('N');
            $table->string('cli2_clid', 8)->nullable();
            $table->string('preftel_clid', 4)->nullable();
			$table->string('portesx_clid', 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fxclid');
    }
};
