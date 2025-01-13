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
        Schema::create('fxcliweb', function (Blueprint $table) {
			$table->string('gemp_cliweb', 2);
            $table->string('cod_cliweb', 8);
            $table->string('usrw_cliweb', 115);
            $table->string('pwdw_cliweb', 20)->nullable();
            $table->string('emp_cliweb', 3)->default('001');
            $table->string('tipacceso_cliweb', 1)->nullable();
            $table->string('tipo_cliweb', 1)->default('C');
            $table->string('dirm_cliweb', 2)->nullable();
            $table->string('nom_cliweb', 60)->nullable();
            $table->string('email_cliweb', 80)->nullable();
            $table->string('per_cliweb', 4)->nullable();
            $table->date('fecalta_cliweb')->nullable();
            $table->string('usralta_cliweb', 10)->nullable();
            $table->date('fecmodi_cliweb')->nullable();
            $table->string('usrmodi_cliweb', 10)->nullable();
            $table->date('fecmodipwd_cliweb')->nullable();
            $table->string('usrmodipwd_cliweb', 10)->nullable();

            for ($i = 1; $i <= 10; $i++) {
                $table->string("nllist{$i}_cliweb", 1)->default('N');
            }

            $table->string('tk_cliweb', 64)->nullable();
            $table->string('pwdwencrypt_cliweb', 256)->nullable();
            $table->string('idioma_cliweb', 2)->default('ES');

            for ($i = 11; $i <= 20; $i++) {
                $table->string("nllist{$i}_cliweb", 1)->default('N');
            }

            $table->string('publi_cliweb', 1)->default('N');
            $table->string('cod2_cliweb', 8)->nullable();
            $table->unsignedBigInteger('tienda_cliweb')->default(0);
            $table->string('type_update_cliweb', 20)->nullable();
            $table->date('date_update_cliweb')->nullable();
            $table->string('usr_update_cliweb', 100)->nullable();
            $table->unsignedBigInteger('permission_id_cliweb')->nullable();
            $table->date('bloqueado_en_cliweb')->nullable();

            $table->primary('cod_cliweb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fxcliweb');
    }
};
