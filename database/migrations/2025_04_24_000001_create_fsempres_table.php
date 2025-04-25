<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFsEmpresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fsempres', function (Blueprint $table) {
            $table->string('cod_emp')->primary();
            $table->string('gemp_emp');
            $table->string('nom_emp')->nullable();
            $table->string('dir_emp')->nullable();
            $table->string('cp_emp')->nullable();
            $table->string('pob_emp')->nullable();
            $table->string('pais_emp')->nullable();
            $table->string('tel1_emp')->nullable();
            $table->string('email_emp')->nullable();

            // Create composite index on cod_emp and gemp_emp as they appear in global scope
            $table->unique(['cod_emp', 'gemp_emp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fsempres');
    }
}
