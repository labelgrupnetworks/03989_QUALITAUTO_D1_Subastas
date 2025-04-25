<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFgSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fgsub', function (Blueprint $table) {
            $table->string('cod_sub')->primary();
            $table->string('emp_sub');
            $table->string('des_sub')->nullable();
            $table->string('tipo_sub')->nullable();
            $table->string('subc_sub')->nullable();
            $table->text('descdet_sub')->nullable();
            $table->string('subabierta_sub')->nullable();
            $table->date('dfec_sub')->nullable();
            $table->string('hfec_sub')->nullable();
            $table->date('dfecorlic_sub')->nullable();
            $table->string('hfecorlic_sub')->nullable();
            $table->string('webmetat_sub')->nullable();
            $table->text('webmetad_sub')->nullable();
            $table->string('ordentel_sub')->nullable();
            $table->string('agrsub_sub')->nullable();
            $table->string('expofechas_sub')->nullable();
            $table->string('expohorario_sub')->nullable();
            $table->string('expolocal_sub')->nullable();
            $table->string('expomaps_sub')->nullable();
            $table->string('sesfechas_sub')->nullable();
            $table->string('seshorario_sub')->nullable();
            $table->string('seslocal_sub')->nullable();
            $table->string('sesmaps_sub')->nullable();
            $table->string('usr_update_sub')->nullable();
            $table->string('valorcol_sub')->nullable();
            $table->string('dhora_sub')->nullable();
            $table->string('hhora_sub')->nullable();

            // Create index on emp_sub field
            $table->index('emp_sub');

            // Create composite index on emp_sub and cod_sub
            $table->unique(['emp_sub', 'cod_sub']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fgsub');
    }
}
